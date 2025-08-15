import {StoreConfig} from "@/core/types";
import {storeToRefs} from "pinia";
import {getTypedEntries} from "@/core/util/getTypedEntries";
import {computed, ComputedRef, ref} from "vue";

export function createRootStore(stores: Record<string, StoreConfig>) {

    const storesLoaded = ref(false);

    const storeLoad = (name: string): any => {
        const storeInfo = stores[name];
        if (storeInfo) {
            if (!storeInfo.store) storeInfo.store = storeInfo.callback();
            return storeInfo.store;
        }
    };

    const storeRefsLoad = (name: string): any => {
        const store = storeLoad(name);
        if (store) {
            const storeInfo = stores[name];
            if (storeInfo) {
                if (!storeInfo.refs) storeInfo.refs = storeToRefs(store);
                return storeInfo.refs;
            }
        }
    };

    const storesBoot = async () => {
        if (storesLoaded.value) return;

        for (const [storeName, storeInfo] of getTypedEntries(stores)) {
            if (!storeInfo.boot) continue;

            const store = storeLoad(storeName);

            if (store) {
                if (store.register) await store.register();
                if (store.load) await store.load();
                //if (store.rootGetters) rootGetters[storeName] = store.rootGetters
            }
        }

        storesLoaded.value = true;
    };

    const computedCache = new Map<string, ComputedRef>();
    const getterCache = new Map<string, () => any>();

    const storeGetter: any = new Proxy(
        {},
        {
            get(target, path: string) {
                if (computedCache.has(path)) {
                    return computedCache.get(path);
                }

                const [storeName, getterName] = path.split(":");

                if (!storeName) throw new Error(`Store name not setted`);

                if (!getterName) throw new Error(`Store name not setted`);

                const store = storeLoad(storeName as string);

                const getterKey = `${storeName}.${getterName}`;

                if (!getterCache.has(getterKey)) {
                    const getter = () => store[getterName];
                    getterCache.set(getterKey, getter);
                }

                const computedRef = computed(getterCache.get(getterKey)!);
                computedCache.set(path, computedRef);

                return computedRef;
            },
        }
    );

    const storeAction = (store: string, action: string, params?: any) => {
        const def = stores[store];
        if (def) {
            const store = def.callback();
            if (store[action]) {
                store[action](params);
            }
        }
    };

    return {
        storeAction,
        storeGetter,
        storeLoad,
        storesBoot,
        storesLoaded
    }
}
