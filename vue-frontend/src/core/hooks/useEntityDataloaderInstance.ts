import {computed, ComputedRef, isRef, onMounted, onUnmounted, ref, Ref, watch} from "vue";
import {DataloaderSubscriberCallbacks, UseEntityDataloader} from "./useEntityDataloader";

let globalEntityCounter = 1;

function generateSubscriberId(options?: { prefix?: string; random?: boolean }) {
    if (options?.random) {
        const rand = Math.random().toString(36).slice(2, 8);
        return options.prefix ? `${options.prefix}-${rand}` : rand;
    } else {
        const id = globalEntityCounter++;
        return options?.prefix ? `${options.prefix}-${id}` : String(id);
    }
}

export interface UseEntityDataloaderOptions<T> {
    loader: UseEntityDataloader<T>;
    ids:
        | Ref<(string | number)[]>
        | ComputedRef<(string | number)[]>
        | (string | number)[];
    subscriberId?: string;
    subscriberIdStrategy?: { prefix?: string; random?: boolean };
    callbacks?: DataloaderSubscriberCallbacks<T>;
    reactiveFromStore?: boolean;
}

export function useEntityDataloaderInstance<T>(params: UseEntityDataloaderOptions<T>) {
    const {
        loader,
        ids,
        subscriberId,
        subscriberIdStrategy,
        callbacks,
        reactiveFromStore = true,
    } = params;

    const entities = ref<T[]>([]);
    const id = subscriberId || generateSubscriberId(subscriberIdStrategy);
    const idsRef = isRef(ids) ? ids : ref(ids);

    // Дефолтный обработчик для внешних событий
    const onExternalChange = (
        items: Record<string, T>,
        type: "onExternalFetched" | "onExternalUpdated"
    ) => {
        if (reactiveFromStore) {
            const store = loader.getStore();
            entities.value = idsRef.value.map((id) => store[id]) as T[]
        } else {
            const idList = idsRef.value.map(String);
            entities.value = idList.map((id) => {
                const entity = items[id];
                return entity;
            }) as T[]
        }
        console.log("[HOOK]", type, entities);
    };

    const update = async () => {
        console.log("[HOOK] update for ids:", idsRef.value);
        entities.value = (await loader.loadMany(idsRef.value, id)) as T[]
        console.log("[HOOK] loaded products:", entities.value);
    };

    update();

    onMounted(() => {
        loader.onExternalFetched(id, (items) => onExternalChange(items, "onExternalFetched"));
        loader.onExternalUpdated(id, (items) => onExternalChange(items, "onExternalUpdated"));
    })

    onUnmounted(() => {
        loader.unsubscribe(idsRef.value, id);
    });

    watch(
        () => [...idsRef.value],
        async (newIds, oldIds) => {
            loader.unsubscribe(oldIds, id);
            entities.value = (await loader.loadMany(newIds, id)) as T[]
        },
        {deep: false}
    );

    function emitUpdates(entitiesArr: T[]) {
        loader.emitUpdated(entitiesArr);
    }

    const entitiesById = computed(() => {
        return entities.value.reduce<Record<string, T>>((map, item) => (map[loader.getId(item as T)] = item as any, map), {})
    })

    return {entities, entitiesById, emitUpdates, subscriberId: id};
}
