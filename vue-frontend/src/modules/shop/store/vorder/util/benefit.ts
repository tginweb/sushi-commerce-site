import {
    BasketRule,
    BasketRuleActionDiscount,
    BasketRuleActionInterface,
    BasketRuleActionTypeEnum,
    BasketRuleCondition,
    BasketRuleConditionTypeEnum,
    DiscountTargetEnum,
    OrderAttributesValue,
    Scalars
} from "@/gql/gen";
import {checkNumberRange} from "@/core/util/checkNumberRange";
import {isIntersects} from "@/core/util/isIntersects";

import {BasketItemModel} from "@/modules/shop/composable/useBasketItemModel";
import isEmpty from "@/core/util/isEmpty";

export type BenefitTypeCode = 'discount' | 'bonus' | 'promocode'

export type BasketRulesSummaryBenefitProduct = {
    TRIGGER_PRODUCT_ID?: number
    BENEFIT_PRODUCT_ID: number
    MAX: number | null
}

export type BasketRulesSummary = {
    ALLOW: (Scalars['String'] | null)[]
    BENEFIT_PRODUCTS: Record<string, BasketRulesSummaryBenefitProduct[]>
    BENEFIT_PRODUCTS_WITH_LIMITS: ({
        BENEFIT_PRODUCT_ID: number
        TRIGGER_PRODUCT_IDS: number[]
        LIMIT: number
    })[]
    DENY: (Scalars['String'] | null)[]
    DISCOUNTS_BASKET: BasketRuleActionDiscount[]
    DISCOUNTS_DELIVERY: BasketRuleActionDiscount[]
    DISCOUNTS_PRODUCT: BasketRuleActionDiscount[]
    DISCOUNTS_SECTION: BasketRuleActionDiscount[]
    DISCOUNTS_TOTAL: BasketRuleActionDiscount[]
}

export type BasketRulesEvaluateContext = {
    basketItems: BasketItemModel[]
    basketProductIds: number[]
    basketSectionIds: number[]
    basketPrice: number
    deliveryPrice: number
    bonuses: number
    coupons: number
    orderAttrs: OrderAttributesValue
}

export type BasketRuleConditionHandler =
    boolean
    | ((cond: BasketRuleCondition, ctx: BasketRulesEvaluateContext) => boolean)


export type BasketRuleActionHandler = (
    props: {
        rule: BasketRule,
        activeRule: BasketRuleActive,
        action: BasketRuleActionInterface,
        summary: BasketRulesSummary,
        ctx: BasketRulesEvaluateContext
    }
) => void

export type BasketRuleActive = {
    rule: BasketRule,
    productIds: Record<string, number>,
    sectionIds: Record<string, number>,
}

export type BasketRuleConditionLogic = 'AND' | 'OR'

export type BasketItemCalculation = {
    discountPercent: number
    price: number
    priceSource: number
}

export type BasketItemCalculated = {
    model: BasketItemModel
    calc: BasketItemCalculation
}

export type ProductDiscountsCalculation = {
    price: number
    priceSource: number
    discountPercent: number
}

export type ProductDiscountsCalculationOptions = {
    round?: number | boolean
}

export type BasketRuleConditionIndexed = BasketRuleCondition & {
    IN_INDEXED: Map<string, number | string>
}

export type BasketRuleActionDiscountIndexed = Omit<BasketRuleActionDiscount, 'PRODUCT_IDS' | 'SECTION_IDS'> & {
    PRODUCT_IDS: Record<string, number | string>,
    SECTION_IDS: Record<string, number | string>,
}

export type BasketRuleActionIndexed =
    Omit<BasketRuleActionInterface, 'PRODUCT_IDS' | 'SECTION_IDS'>
    & BasketRuleActionDiscountIndexed

export type BasketRuleIndexed = Omit<BasketRule, 'CHILDREN' | 'ACTIONS' | 'CONDITIONS'> & {
    CONDITIONS: BasketRuleConditionIndexed[]
    ACTIONS: BasketRuleActionIndexed[]
    CHILDREN: BasketRuleIndexed[]
}

export type BasketBenefitsResult = {
    discounts: Record<DiscountTargetEnum | 'common', BasketRuleActionDiscountIndexed[]>,
    gifts: []
}

export const calcPriceDiscount = (discount: BasketRuleActionDiscountIndexed, price?: number, quantity?: number) => {
    if (!discount)
        return price || 0
    const amount = discount.AMOUNT_SURCHARGE ? discount.AMOUNT_SURCHARGE : -discount.AMOUNT
    if (amount) {
        if (!price)
            return 0
        if (discount.MODE === 'fixed') {
            return price > discount.AMOUNT ? price + amount : 0
        } else if (discount.MODE === 'percent') {
            return (price / 100) * amount
        }
    }
    return 0
}

export const getMaxDiscount = (discounts: BasketRuleActionDiscountIndexed[], price?: number, quantity?: number): BasketRuleActionDiscountIndexed | null | undefined => {
    return [...discounts.sort((a, b) => b.AMOUNT - a.AMOUNT)][0]
}

export const checkRuleConditions = (
    conditions: BasketRuleCondition[],
    logic: BasketRuleConditionLogic = 'AND',
    context: BasketRulesEvaluateContext
) => {

    let status = true

    for (const cond of conditions) {

        switch (cond.TYPE) {
            case "group_or":
                if (cond.CHILDREN && cond.CHILDREN.length) {
                    status = checkRuleConditions(cond.CHILDREN, 'OR', context) as boolean
                }
                break
            case "group_and":
                if (cond.CHILDREN && cond.CHILDREN.length) {
                    status = checkRuleConditions(cond.CHILDREN, 'AND', context) as boolean
                }
                break
            default:
                const handler = basketRuleConditions[cond.TYPE]
                if (handler && typeof handler === 'function') {
                    status = handler(cond, context)
                }
        }

        if (cond.NOT) {
            status = !status
        }

        if (!status && logic === 'AND' || status && logic === 'OR')
            break;
    }

    return status
}

function checkCondition(value: any, condition: BasketRuleCondition) {
    return !isEmpty(condition.VALUE) && (condition.VALUE === value) ||
        !isEmpty(condition.IN) && condition.IN!.includes(value) ||
        !isEmpty(condition.MIN || condition.MAX) && checkNumberRange(value, {min: condition.MIN, max: condition.MAX})
}

export const basketRuleConditions: Record<Exclude<BasketRuleConditionTypeEnum, 'group_and' | 'group_or'>, BasketRuleConditionHandler> = {
    'order_price': (cond, {basketPrice}) =>
        checkNumberRange(basketPrice, cond),
    'product_id': (cond, {basketProductIds}) =>
        !isEmpty(cond.IN) && isIntersects(basketProductIds, cond.IN!),
    'section_id': (cond, {basketSectionIds}) =>
        !isEmpty(cond.IN) && isIntersects(basketSectionIds, cond.IN!),
    'bonus_filled': (cond, {bonuses}) =>
        bonuses > 0,
    'coupons_filled': (cond, {coupons}) =>
        coupons > 0,
    'delivery_id': (cond, {orderAttrs}) =>
        orderAttrs.DELIVERY_ID === cond.VALUE,
    'transport_type': (cond, {orderAttrs}) =>
        checkCondition(orderAttrs.TRANSPORT_TYPE, cond),
    'payment_type': (cond, {orderAttrs}) =>
        checkCondition(orderAttrs.PAYMENT_TYPE, cond),
    'paysystem_id': (cond, {orderAttrs}) =>
        checkCondition(orderAttrs.PAY_SYSTEM_ID, cond),
    'attr_value': (cond, {orderAttrs}) =>
        checkCondition(orderAttrs[cond.CODE as keyof OrderAttributesValue], cond)
}

export const basketRuleActions: Partial<Record<BasketRuleActionTypeEnum, BasketRuleActionHandler>> = {
    'gift': (ctx) => {

    },
    'discount': (ctx) => {

    },
}
