import {defineStore, storeToRefs} from "pinia";
import {computed} from "vue";
import {
    BasketItemCalculated,
    BasketItemCalculation,
    BasketRuleActionDiscountIndexed,
    calcPriceDiscount,
    getMaxDiscount,
    ProductDiscountsCalculation,
    ProductDiscountsCalculationOptions,
} from "./util/benefit";
import {useVorderBasketStore} from "./basket";
import {useVorderBenefitStore} from "@/modules/shop/store/vorder/benefit";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

const STORE_NAME = 'vorder-calculator'

export const useVorderCalculatorStore = defineStore(STORE_NAME, () => {

    const basket = useVorderBasketStore()
    const benefitStore = useVorderBenefitStore()

    const {basketItemsModels} = storeToRefs(basket)
    const {
        activeBenefits,
        activeDiscountsByProductId,
        activeDiscountsBySectionId
    } = storeToRefs(benefitStore)

    const basketItemsCalculated = computed<BasketItemCalculated[]>(() => {

        const itemCommonDiscounts = [
            ...activeBenefits.value.discounts.total,
            ...activeBenefits.value.discounts.basket,
        ]

        return basketItemsModels.value.map(model => {

            let calc = {} as BasketItemCalculation

            const itemDiscounts = [
                ...itemCommonDiscounts,
                ...(activeDiscountsByProductId.value[model.fields.PRODUCT_ID] || []),
                ...model.product.IBLOCK_SECTION_IDS.reduce<BasketRuleActionDiscountIndexed[]>((map, sectionId) => {
                    return [...map, ...(activeDiscountsBySectionId.value[sectionId] || [])]
                }, [])
            ]

            const priceBase = model.getPriceBase()

            let priceDiscounted = model.getPriceBase()
            let priceTotalDiscounted = model.getPriceTotalBase()

            let discountPercent = 0

            const maxDiscount = getMaxDiscount(itemDiscounts, priceBase, model.fields.QUANTITY)

            if (maxDiscount) {
                const priceDiscount = calcPriceDiscount(maxDiscount, priceBase, model.fields.QUANTITY)
                priceDiscounted = model.fields.PRICE_BASE - priceDiscount
                priceTotalDiscounted = priceDiscounted * model.fields.QUANTITY
                discountPercent = 100 - (priceDiscounted / priceBase) * 100
            }

            calc = {
                discountPercent,
                price: priceDiscounted,
                priceSource: priceTotalDiscounted,
            }

            return {
                model,
                calc
            }
        })
    })

    const basketItemsCalculatedByProduct = computed(() =>
        basketItemsCalculated.value.reduce<Record<string, BasketItemCalculated>>((map, item) =>
            (map[item.model.fields.PRODUCT_ID] = item, map), {}))

    const calculateProductDiscounts = (product: ProductElementModel, options: ProductDiscountsCalculationOptions = {}): ProductDiscountsCalculation => {

        const discounts = [
            ...activeBenefits.value.discounts.total,
            ...activeBenefits.value.discounts.basket,
            ...(activeDiscountsBySectionId.value[product.ID] || []),
            ...product.IBLOCK_SECTION_IDS.reduce<BasketRuleActionDiscountIndexed[]>((map, sectionId) => {
                return [...map, ...(activeDiscountsBySectionId.value[sectionId] || [])]
            }, [])
        ]

        const maxDiscount = getMaxDiscount(discounts)

        let price = product.getPriceSource()
        let priceSource = price
        let discountPercent = 0

        if (maxDiscount) {
            price = calcPriceDiscount(maxDiscount, price)
            if (maxDiscount.AMOUNT) discountPercent += maxDiscount.AMOUNT
        }

        return {
            discountPercent: discountPercent,
            priceSource: priceSource,
            price: price
        }
    }

    return {
        basketItemsCalculated,
        basketItemsCalculatedByProduct,
        calculateProductDiscounts
    }
})

