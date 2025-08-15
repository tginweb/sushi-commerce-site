import DataLoader from "dataloader";
import {reactive} from "vue";
import {Maybe} from "@/core/types";

export interface DataLoaderOptions<T> {
    fetchByIds: (ids: (string | number)[]) => Promise<Maybe<T>[]>;
    getStore: () => Record<string, T>;
    setStore: (map: Record<string | number, T>) => void;
    onLoadedNew?: (items: T[]) => void;
    batchTimeout?: number;
    getId: (item: T) => string | number;
    orphanedTtl?: number;
    globalTtl?: number;
    gcOrphanedInterval?: number;
    gcGlobalInterval?: number;
    gcGlobalMinCount?: number;
    gcGlobalOrphaned?: number;
}

// Типы для коллбэков
export type DataloaderSubscriberCallbacks<T> = {
    onExternalFetched?: (entities: Record<string, T>) => void;
    onExternalUpdated?: (entities: Record<string, T>) => void;
};

export type UseEntityDataloader<T> = ReturnType<typeof useEntityDataloader<T>>

export function useEntityDataloader<T>(options: DataLoaderOptions<T>) {
    const batchTimeout = options.batchTimeout ?? 100;

    const getId =
        options.getId ?? ((item: any) => item.id || item.ID || item._id);

    const orphanedTtl = options.orphanedTtl ?? 0;
    const globalTtl = options.globalTtl ?? 0;
    const gcOrphanedInterval = options.gcOrphanedInterval ?? 0;
    const gcGlobalInterval = options.gcGlobalInterval ?? 0;

    const idInfo = reactive<
        Record<string, { subscribers: Set<string>; lastAccessed: number }>
    >({});

    const subscriberOnExternalFetched = new Map<
        string,
        (entities: Record<string, T>) => void
    >();
    const subscriberOnExternalUpdated = new Map<
        string,
        (entities: Record<string, T>) => void
    >();

    const loader = new DataLoader<string | number, T>(
        async (ids: any) => {
            return options.fetchByIds(ids) as any
        },
        {
            cache: false,
            batchScheduleFn: (callback) => setTimeout(callback, batchTimeout),
        }
    );

    function onExternalFetched(
        subscriberId: string,
        callback: (entities: Record<string, T>) => void
    ) {
        subscriberOnExternalFetched.set(subscriberId, callback);
    }

    function onExternalUpdated(
        subscriberId: string,
        callback: (entities: Record<string, T>) => void
    ) {
        subscriberOnExternalUpdated.set(subscriberId, callback);
    }

    async function load(
        id: string | number,
        subscriberId: string,
        refetch?: boolean
    ): Promise<(T | undefined)> {
        const result = await loadMany([id], subscriberId, refetch)
        if (result) {
            return result[0]
        }
    }

    async function loadMany(
        ids: (string | number)[],
        subscriberId: string,
        refetch?: boolean
    ): Promise<(T | undefined)[]> {
        //if (callbacks) subscriberCallbacks.set(subscriberId, callbacks);
        const now = Date.now();
        const store = options.getStore();
        let missingIds: (string | number)[] = [];
        if (refetch) {
            missingIds = ids;
        } else {
            ids.forEach((id) => {
                const key = String(id);
                if (!idInfo[key]) {
                    idInfo[key] = {subscribers: new Set(), lastAccessed: now};
                }
                const info = idInfo[key]
                if (info) {
                    info.subscribers.add(subscriberId);
                    info.lastAccessed = now;
                }
                if (!store[key]) missingIds.push(id);
            });
        }

        if (missingIds.length === 0) {
            const result = ids.map((id) => store[id]);
            console.log(
                "[DATALOADER] allInStore, return:",
                result.map((i) => i && getId(i))
            );
            return result;
        }

        const items = await loader.loadMany(missingIds);
        const map = Object.fromEntries(
            items
                .filter((item): item is T => !!item && !(item instanceof Error))
                .map((item) => [getId(item), item])
        );

        options.setStore(map);
        options.onLoadedNew?.(items as T[]);

        const newStore = options.getStore();
        const loadedMap = Object.fromEntries(
            missingIds
                .map((id) => [id, newStore[id]])
                .filter(([, v]) => !!v)
        );

        if (Object.keys(loadedMap).length)
            notifyToSubscribers(missingIds, "onExternalFetched", loadedMap);

        const now2 = Date.now();

        for (const id of ids) {
            const info = idInfo[String(id)];
            if (info) info.lastAccessed = now2;
        }

        // Возвращаем результат для всех ids
        return ids.map((id) => newStore[id]).filter(item => !!item)
    }

    function notifyToSubscribers(
        ids: (string | number)[],
        type: "onExternalFetched" | "onExternalUpdated",
        entities: Record<string, T>
    ) {
        const notified = new Set<string>();
        ids.forEach((id) => {
            const key = String(id);
            if (!idInfo[key]) return;
            const info = idInfo[key]
            if (info) {
                for (const subId of info.subscribers) {
                    if (notified.has(subId)) continue;

                    const cbMap =
                        type === "onExternalFetched"
                            ? subscriberOnExternalFetched
                            : subscriberOnExternalUpdated;
                    const cb = cbMap.get(subId);

                    if (cb && typeof cb === "function") {
                        cb(entities);
                        notified.add(subId);
                    }
                }
            }
        });
    }

    function emitUpdated(entities: T[]) {
        console.log("emitUpdated", entities);
        const map = Object.fromEntries(entities.map((e) => [getId(e), e]));
        options.setStore(map);
        const ids = entities.map((e) => String(getId(e)));
        const notifyMap: Record<string, T> = {};
        for (const e of entities) notifyMap[String(getId(e))] = e;
        notifyToSubscribers(ids, "onExternalUpdated", notifyMap);
    }

    function unsubscribe(ids: (string | number)[], subscriberId: string) {
        const now = Date.now();
        ids.forEach((id) => {
            const key = String(id);
            const info = idInfo[key]
            if (info) {
                info.subscribers.delete(subscriberId);
                info.lastAccessed = now;
            }
        });
        subscriberOnExternalFetched.delete(subscriberId);
        subscriberOnExternalUpdated.delete(subscriberId);
    }

    // GC: удаляет из кеша id без подписчиков, если lastAccessed старше orphanedTtl
    function gcOrphaned(force = false) {
        const store = options.getStore();
        const now = Date.now();
        let changed = false;
        const minCount = options.gcGlobalOrphaned ?? 0;
        let storeKeys = Object.keys(store);
        if (storeKeys.length <= minCount) return;

        // Сортируем по lastAccessed (от самого старого)
        const sortedEntries = Object.entries(idInfo)
            .filter(
                ([_, info]) =>
                    info.subscribers.size === 0 &&
                    (force || now - info.lastAccessed > orphanedTtl)
            )
            .sort((a, b) => a[1].lastAccessed - b[1].lastAccessed);

        for (const [key, info] of sortedEntries) {
            if (storeKeys.length <= minCount) break;
            delete store[key];
            changed = true;
            delete idInfo[key];
            storeKeys = Object.keys(store);
            if (typeof window !== "undefined") {
                console.log("[gcOrphaned] removed (orphaned):", key, {
                    idInfo: {...idInfo},
                    store,
                });
            }
        }
        if (changed) options.setStore({...(store || {})});
    }

    // Глобальный GC: удаляет любые id, если lastAccessed старше globalTtl
    function gcGlobal(force = false) {
        const store = options.getStore();
        const now = Date.now();
        let changed = false;
        const minCount = options.gcGlobalMinCount ?? 0;
        let storeKeys = Object.keys(store);
        if (storeKeys.length <= minCount) return;

        // Сортируем по lastAccessed (от самого старого)
        const sortedEntries = Object.entries(idInfo)
            .filter(([_, info]) => force || now - info.lastAccessed > globalTtl)
            .sort((a, b) => a[1].lastAccessed - b[1].lastAccessed);

        for (const [key, info] of sortedEntries) {
            if (storeKeys.length <= minCount) break;
            delete store[key];
            changed = true;
            delete idInfo[key];
            storeKeys = Object.keys(store);
            if (typeof window !== "undefined") {
                console.log("[gcGlobal] removed (global TTL):", key, {
                    idInfo: {...idInfo},
                    store,
                });
            }
        }
        if (changed) options.setStore({...(store || {})});
    }

    setInterval(gcOrphaned, gcOrphanedInterval);
    setInterval(gcGlobal, gcGlobalInterval);

    // Диагностика
    function getRefCount(id: string | number) {
        const info = idInfo[String(id)];
        return info ? info.subscribers.size : 0;
    }

    function getSubscribers(id: string | number) {
        const info = idInfo[String(id)];
        return info ? Array.from(info.subscribers) : [];
    }

    function hasCleanupTimer(id: string | number) {
        const info = idInfo[String(id)];
        if (!info) return false;
        return (
            info.subscribers.size === 0 &&
            Date.now() - info.lastAccessed > orphanedTtl / 2
        );
    }

    function getAllRefCounts() {
        const result: Record<string, number> = {};
        for (const [key, info] of Object.entries(idInfo))
            result[key] = info.subscribers.size;
        return result;
    }

    function getAllCleanupTimers() {
        return Object.keys(idInfo).filter((key) => hasCleanupTimer(key));
    }

    function getAllSubscribers() {
        const result: Record<string, string[]> = {};
        for (const [key, info] of Object.entries(idInfo))
            result[key] = Array.from(info.subscribers);
        return result;
    }

    function getAllKnownIds() {
        return Object.keys(idInfo);
    }

    return {
        getId: options.getId,
        getStore: options.getStore,
        idInfo,
        loadMany,
        unsubscribe,
        getRefCount,
        getSubscribers,
        hasCleanupTimer,
        getAllRefCounts,
        getAllCleanupTimers,
        getAllSubscribers,
        getAllKnownIds,
        gcOrphaned,
        gcGlobal,
        globalTtl,
        orphanedTtl,
        emitUpdated,
        onExternalFetched,
        onExternalUpdated,
    };
}
