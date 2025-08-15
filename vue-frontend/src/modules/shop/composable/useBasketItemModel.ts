import {BasketItem, BasketItemBenefitType, BasketRuleActionDiscount, ProductBenefit} from "@/gql/gen";
import {randomNum} from "@/core/util/randomNum";
import {randID} from "@/core/util/randID";
import Result from "@/core/classes/Result";
import {fillBasketItemByProduct} from "@/modules/shop/store/vorder/util/basket";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";
import {useCatalogStore} from "@/modules/shop/store/catalog";

export interface BasketItemModel {
    fields: BasketItem
    product: ProductElementModel

    _isModel: boolean

    savedItem: BasketItem
    commited: boolean

    clientChangedAt: number
    needSync: boolean
    synced: boolean

    children: BasketItemModel[]

    setGift: (productId: number) => void
    setSpecial: (productId: number, quantity: number, benefit: ProductBenefit, result: Result) => void
    fillByProduct: (product: ProductElementModel) => void
    updateFromServer: (item: BasketItem) => void
    childrenByBenefitType: (benefitType: BasketItemBenefitType) => BasketItemModel[]
    getPriceTotalBase: () => number
    getPriceBase: () => number

    isNeedSync: () => boolean
    isSaved: () => boolean
    isRoot: () => boolean

    onDelete: () => boolean
    onChange: () => void

    updateAfterSave: (item: BasketItem) => void

    discount?: BasketRuleActionDiscount

    getFakeSync: () => any
}

function createClientId() {
    return randomNum(-10000000, -1000000)
}

export const useBasketItemModel = (fields: BasketItem, product: ProductElementModel, commited = false): BasketItemModel => {

    const {ensureProduct} = useCatalogStore()

    fields.ID = fields.ID || createClientId()
    fields.CLIENT_ID = fields.CLIENT_ID || randID()
    fields.CLIENT_CHANGED_AT = fields.CLIENT_CHANGED_AT || Date.now()

    let _isModel = true
    let needSync = false
    let synced = true
    let children: BasketItemModel[] = []
    let savedItem = {} as BasketItem
    let clientChangedAt = fields.CLIENT_CHANGED_AT || Date.now()

    const childrenByBenefitType = (benefitType: BasketItemBenefitType) => {
        return children.filter(item => item.fields.BENEFIT === benefitType)
    }

    const fillByProduct = (product: ProductElementModel) => {
        fillBasketItemByProduct(fields, product)
    }

    const setGift = async (productId: number) => {

        let giftModel = children.find(item => item.fields.BENEFIT === 'gift')

        if (giftModel) {
            children = children.filter(item => item.fields.BENEFIT !== 'gift')
        }

        const giftItem = {
            ID: createClientId(),
            PARENT_ID: fields.ID,
            PRODUCT_ID: productId,
            QUANTITY: 1
        } as BasketItem

        const product = await ensureProduct(productId)

        if (product) {
            fillBasketItemByProduct(giftItem, product)
            giftModel = useBasketItemModel(giftItem, product, commited)
            children.push(giftModel)
        }
    }

    const setSpecial = async (productId: number, quantity: number, benefit: ProductBenefit, result: Result) => {

        const specialModels = childrenByBenefitType('special')

        const specialModelsQuantity = specialModels.reduce((acc, specialModel) => {
            acc += productId === specialModel.fields.PRODUCT_ID ? quantity : specialModel.fields.QUANTITY
            return acc
        }, 0)

        const specialModelsLimit = fields.QUANTITY

        if (specialModelsQuantity > specialModelsLimit) {
            result.addError('Не больше ' + specialModelsLimit)
            return
        }

        if (quantity > 5) {
            result.addError('Не больше 5')
            return
        }

        let specialModel = children.find(item => item.fields.PRODUCT_ID === productId)

        if (!specialModel) {

            if (quantity > 0) {

                const specialItem = {
                    ID: createClientId(),
                    PARENT_ID: fields.ID,
                    PRODUCT_ID: productId,
                    QUANTITY: quantity,
                    BENEFIT: 'special'
                } as BasketItem

                const product = await ensureProduct(productId)

                if (product) {
                    //fillBasketItemByProduct(specialItem, product)
                    const specialModel = useBasketItemModel(specialItem, product, commited)
                    children.push(specialModel)
                }
            }

        } else {
            if (quantity) {
                specialModel.fields.QUANTITY = quantity
            } else {
                children = children.filter(specialModel => specialModel !== specialModel)
            }
        }

        return true
    }

    const isSaved = () => {
        return fields.ID > 0
    }

    const isRoot = () => {
        return !fields.PARENT_ID
    }

    const onDelete = () => {
        return true
    }

    const isNeedSync = () => {
        return (clientChangedAt > fields.CLIENT_CHANGED_AT)
    }

    const onChange = () => {
        clientChangedAt = Date.now()
        needSync = isNeedSync()
        synced = false
    }

    const updateAfterSave = (item: BasketItem) => {
        savedItem = item
    }

    const updateFromServer = (item: BasketItem) => {
        fields.ID = item.ID
        fields.PROPS = item.PROPS
        fields.CLIENT_CHANGED_AT = item.CLIENT_CHANGED_AT

        fields.PRICE_BASE = item.PRICE_BASE
        fields.FINAL_PRICE_BASE = item.FINAL_PRICE_BASE

        /*
        if (false) {
            model.fields.PRICE = item.PRICE
            model.fields.FINAL_PRICE = item.FINAL_PRICE
        }
         */

        needSync = isNeedSync()
        synced = true
    }

    const getPriceBase = () => {
        return fields.PRICE_BASE || 0
    }

    const getPriceTotalBase = () => {
        return getPriceBase() * fields.QUANTITY
    }

    const getFakeSync = () => {
        return {
            PROPS: fields.PROPS,
            CLIENT_ID: fields.CLIENT_ID,

            PRICE_BASE: getPriceBase(),
            FINAL_PRICE_BASE: getPriceTotalBase(),

            PRICE: getPriceBase(),
            FINAL_PRICE: getPriceTotalBase(),

            CLIENT_CHANGED_AT: clientChangedAt,
        }
    }

    return {
        fields,
        product,
        _isModel,
        commited,
        children,
        needSync,
        synced,
        savedItem,
        clientChangedAt,
        updateAfterSave,
        childrenByBenefitType,
        setGift,
        setSpecial,
        isSaved,
        isRoot,
        onDelete,
        isNeedSync,
        onChange,
        updateFromServer,
        fillByProduct,
        getPriceTotalBase,
        getPriceBase,
        getFakeSync
    }
}


