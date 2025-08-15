import {defineStore} from "pinia";
import {computed, nextTick, ref, watch} from "vue";
import {BasketItem, ProductElement} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {useDebounceFn} from "@vueuse/core";
import {useTask} from "@/core/hooks/useTask";
import {sleep} from "@/core/util/sleep";
import {BasketItemModel, useBasketItemModel,} from "@/modules/shop/composable/useBasketItemModel";
import {useBus} from "@/core/store/bus";
import {Maybe} from "@/core/types";
import {fillBasketItemByProduct} from "@/modules/shop/store/vorder/util/basket";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export type TBasketOpAdd = {
    action: 'add',
    quantity: number
    productId?: number
    itemId?: number
    item?: BasketItemModel
    product?: ProductElementModel | ProductElement
}

export type TBasketOpSetQuantity = {
    action: 'quantity',
    itemId?: number
    item?: BasketItemModel
    productId?: number
    quantity: number
}

export type TBasketOpDelete = {
    action: 'delete',
    itemId?: number
    item?: BasketItemModel
    productId?: number
}

export type TBasketOpClear = {
    action: 'clear',
}

export type TBasketOpAddMultipleProduct = {
    itemId?: number
    productId?: number
    product?: ProductElementModel
    quantity: number
}

export type TBasketOpAddMultiple = {
    action: 'add-multiple',
    products: TBasketOpAddMultipleProduct[],
}

export type TBasketOpAddConstructor = {
    action: 'add-constructor',
    constructor: any
    sectionId: number
    sectionCode: string
}

export type TBasketOp =
    TBasketOpAdd
    | TBasketOpAddMultiple
    | TBasketOpAddConstructor
    | TBasketOpSetQuantity
    | TBasketOpDelete
    | TBasketOpClear

export const basketItemPriceRound = (price: number) => {
    return Math.round(price)
}

const STORE_NAME = 'vorder-basket'

export type BasketCalculationMode = 'server' | 'client' | 'client-server'

export const useVorderBasketStore = defineStore(STORE_NAME, () => {

    const catalogStore = useCatalogStore()

    const {bus} = useBus()
    const {registerScopeQuery} = useScopeQuery()
    const {ensureProduct, ensureProducts, ensureProductsById} = catalogStore

    // STATE

    const changedAt = ref<number>(0)
    const syncedChangedAt = ref<number>(0)
    const calculationMode = ref<BasketCalculationMode>('client')
    const pendingSync = ref(false)

    const itemsModels = ref<BasketItemModel[]>([])
    const itemsModelsByProduct = computed(() =>
        itemsModels.value.reduce<Record<string, BasketItemModel>>((map, item) => (map[item.fields.PRODUCT_ID], map), {})
    )

    const items = computed(() => itemsModels.value.map((item) => item.fields))
    const itemsWatchable = computed(() => items.value.map((item) => ({
        ID: item.ID,
        QUANTITY: item.QUANTITY,
        PROPS: item.PROPS,
    })))

    // GETTERS

    const itemsModelsByProductId = computed(() =>
        itemsModels.value.reduce<Record<string, BasketItemModel>>((map, item) => {
            if (item.fields.PRODUCT_ID) map[item.fields.PRODUCT_ID] = item
            return map
        }, {}))

    const synced = computed(() => changedAt.value <= syncedChangedAt.value)

    const price = computed(() => itemsModels.value.reduce((acc, item) => (acc += item.getPriceTotalBase(), acc), 0))

    const priceLevel = computed(() => Math.round(price.value / 1500))

    const isEmpty = computed(() => !items.value.length)

    const isNotEmpty = computed(() => !!items.value.length)

    // ACTIONS

    const fill = async (newItems: BasketItem[], preserveItems = false) => {
        if (!preserveItems) {
            const productsById = await ensureProductsById(newItems.map(item => item.ELEMENT || item.PRODUCT_ID))
            itemsModels.value = newItems.map(item => useBasketItemModel(item, productsById[item.PRODUCT_ID]!))
        } else {
            itemsModels.value.forEach(oldItem => {
                newItems.forEach(newItem => {
                    if (oldItem.fields.ID === newItem.ID || oldItem.fields.CLIENT_ID === newItem.CLIENT_ID) {
                        oldItem.updateFromServer(newItem)
                    }
                })
            })
        }
        //syncedChangedAt.value = data.CLIENT_CHANGED_AT || 0
        //syncedChangedAt.value = Date.now()
    }

    const itemIndex = ref(0)

    const sync = useTask(async () => {
        const v = changedAt.value
        const serverItems = itemsModels.value.map(item => ({
            ID: item.fields.ID < 0 ? itemIndex.value++ : item.fields.ID,
            ...item.getFakeSync()
        }))
        //fetch('https://www.vsp.ru')
        console.log('DO SYNC')
        await sleep(3000)
        fill(serverItems, true)
        syncedChangedAt.value = v
    })

    const save = useTask(async () => {
        await sleep(3000)
    })

    const queueSync = useDebounceFn(async () => {

        pendingSync.value = true

        if (sync.isProcessing.value)
            return;

        pendingSync.value = false

        await sync.run()

        //console.log('SYNC DONE', pendingBasketSync.value)

        if (pendingSync.value) {
            queueSync()
        }

    }, 500)

    const queueSave = useDebounceFn(() => save.run(), 10000)

    const findItemModel = (
        data: Omit<TBasketOpAdd | TBasketOpSetQuantity | TBasketOpDelete, 'action'>
    ) => {
        const {
            item: targetItem,
            itemId,
            productId,
        } = data
        return itemsModels.value.find((item) => {
            if (targetItem) {
                return item === targetItem || item.fields.ID === targetItem.fields.ID || item.fields.PRODUCT_ID === targetItem.fields.PRODUCT_ID
            } else if (itemId) {
                return itemId === item.fields.ID
            } else if (productId) {
                return productId === item.fields.PRODUCT_ID
                //&& (!basketPropsHash || item.INPUT_PROPS_HASH === basketPropsHash)
            }
        })
    }

    const op = async (data: TBasketOp): Promise<Maybe<BasketItemModel>> => {

        let itemModel: BasketItemModel | undefined | null

        switch (data.action) {

            case "add": {
                itemModel = findItemModel(data)
                if (itemModel) {
                    itemModel.fields.QUANTITY = data.quantity
                    itemModel.onChange()
                } else {
                    if (data.product || data.productId) {
                        const item = {
                            PRODUCT_ID: data.productId,
                            QUANTITY: data.quantity,
                        } as BasketItem
                        const product = await ensureProduct(data.product || data.productId)
                        if (product) {
                            fillBasketItemByProduct(item, product)
                            itemModel = useBasketItemModel(item, product)
                            itemsModels.value.push(itemModel)
                            itemModel.onChange()
                        }
                    }
                }
                break
            }
            case "quantity": {
                itemModel = findItemModel(data)
                if (itemModel) {
                    if (data.quantity > 0) {
                        itemModel.fields.QUANTITY = data.quantity
                        itemModel.onChange()
                    } else {
                        itemsModels.value = itemsModels.value.filter((item) => item.fields.ID !== itemModel?.fields.ID)
                    }
                }
                break;
            }
            case "delete": {
                itemModel = findItemModel(data)
                if (itemModel) {
                    if (itemModel.onDelete()) {
                        itemsModels.value = itemsModels.value.filter(item => item !== itemModel)
                    }
                }
                break;
            }
            case "clear": {
                itemsModels.value = []
                break;
            }
        }

        removeOfferItems()
        nextTick(onChange)
        changedAt.value = Date.now()
        return itemModel
    }

    const removeOfferItems = () => {

    }

    // WATCHERS

    watch(changedAt, () => {
        if (changedAt.value > syncedChangedAt.value) {
            if (calculationMode.value !== 'client') {
                queueSync()
            } else if (10000) {
                queueSave()
            }
        }
    })

    // EVENTS

    const onChange = () => {
        bus.emit('vorder:basket.change')
    }

    // SCOPE QUERY

    registerScopeQuery(STORE_NAME, 'user', {
        sale_vorder_current: {
            __fragment: 'VorderCurrentFields'
        },
    }, (data) => {

    })

    return {
        basketOp: op,
        basketItems: items,
        basketItemsWatchable: itemsWatchable,
        basketItemsModels: itemsModels,
        basketItemsModelsByProduct: itemsModelsByProduct,
        basketPendingSync: pendingSync,
        basketOnChange: onChange,
        basketSync: sync,
        basketSave: save,
        basketFill: fill,
        basketQueueSync: queueSync,
        basketQueueSave: queueSave,
        basketPrice: price,
        basketPriceLevel: priceLevel,
        basketIsEmpty: isEmpty,
        basketIsNotEmpty: isNotEmpty,
    }
})

