import {BasketRule, Discount} from "@/gql/gen";
import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {
    BasketBenefitsResult,
    BasketRuleActionDiscountIndexed,
    BasketRuleIndexed,
    BasketRulesEvaluateContext,
    BenefitTypeCode,
    checkRuleConditions
} from "@/modules/shop/store/vorder/util/benefit";
import {useDebounceFn} from "@vueuse/core";
import {treeFilter} from "@/core/util/treeFilter";
import {useScopeQuery} from "@/core/store/scopeQuery";

const STORE_NAME = 'vorder-benefit'

export const useVorderBenefitStore = defineStore(STORE_NAME, () => {

    const {registerScopeQuery} = useScopeQuery();

    const discounts = ref<Discount[]>([])

    const benefitType = ref<BenefitTypeCode>('bonus')

    const basketRules = ref<BasketRuleIndexed[]>([])

    const basketBenefitsHash = ref<string>('')

    const activeBenefits = ref<BasketBenefitsResult>({
        gifts: [],
        discounts: {
            common: [],
            product: [],
            basket: [],
            total: [],
            delivery: [],
        }
    })

    const activeCommonDiscounts = computed(() => activeBenefits.value.discounts.common)
    const activeCommonDiscount = computed(() => activeCommonDiscounts.value[0])

    const activeDiscountsBySectionId = computed(() => {
        const map: Record<string, Record<string, BasketRuleActionDiscountIndexed>> = {};
        for (const discount of activeBenefits.value.discounts.product) {
            for (const sectionId of Object.keys(discount.SECTION_IDS)) {
                if (!map[sectionId]) map[sectionId] = {};
                map[sectionId]![discount.ID!] = discount;
            }
        }
        return Object.values(map).map(section => Object.values(section));
    })

    const activeDiscountsByProductId = computed(() => {
        const map: Record<string, Record<string, BasketRuleActionDiscountIndexed>> = {};
        for (const discount of activeBenefits.value.discounts.product) {
            for (const sectionId of Object.keys(discount.PRODUCT_IDS)) {
                if (!map[sectionId]) map[sectionId] = {};
                map[sectionId]![discount.ID!] = discount;
            }
        }
        return Object.values(map).map(section => Object.values(section));
    })

    const getBasketRulesActive = (rules: BasketRuleIndexed[], vorderStat: BasketRulesEvaluateContext) => {

        const result: BasketRuleIndexed[] = []

        let stoped = false

        const scan = (scanRules: BasketRuleIndexed[]) => {
            for (const rule of scanRules) {
                const status = checkRuleConditions(rule.CONDITIONS, 'AND', vorderStat)
                if (status) {
                    result.push(rule)
                    if (rule.CHILDREN && rule.CHILDREN.length) {
                        scan(rule.CHILDREN)
                        if (stoped) {
                            return
                        }
                    }
                    if (rule.LEVEL_STOP) {
                        break;
                    }
                    if (rule.ALL_STOP) {
                        stoped = true
                        break;
                    }
                }
            }
        }

        scan(rules)

        return result
    }

    const evaluateBenefitsResult = (context: BasketRulesEvaluateContext) => {

        const result: BasketBenefitsResult = {
            gifts: [],
            discounts: {
                common: [],
                product: [],
                basket: [],
                delivery: [],
                total: [],
            }
        }

        const rules = getBasketRulesActive(basketRules.value, {} as any)

        const otherBenefitsUsed = context.bonuses > 0 || context.coupons > 0

        for (const rule of rules) {
            for (const action of rule.ACTIONS) {
                if (action.TYPE === 'discount') {

                    if (action.TARGET === 'basket' || action.TARGET === 'total')
                        result.discounts.common.push(action)

                    if (action.AMOUNT_SURCHARGE || !otherBenefitsUsed) {
                        if (!result.discounts[action.TARGET]) {
                            result.discounts[action.TARGET] = []
                        }
                        result.discounts[action.TARGET]!.push(action)
                    }
                }
            }
        }

        activeBenefits.value = result
        basketBenefitsHash.value = rules.map(item => item.ID || item.CODE).join('-')

        return result
    }

    const evaluateBenefitsResultDebounced = useDebounceFn(evaluateBenefitsResult, 100)

    const benefitTypes = computed(() => {
        const res = []
        res.push({
            nameShort: 'Скидка',
            name: 'Скидка 10%',
            value: 'discount',
            icon: 'fasPercent'
        })
        res.push({
            nameShort: 'Бонусы',
            name: 'Бонусы',
            value: 'bonus',
            icon: 'bonus'
        })
        res.push({
            nameShort: 'Промокод',
            name: 'Промокод',
            value: 'promocode',
            icon: 'promocode'
        })
        return res
    })

    const indexRules = (rules: BasketRule[]): BasketRuleIndexed[] => {

        treeFilter(rules, (rule) => {
            rule.ACTIONS.forEach((action) => {
                if (action.TYPE === 'discount') {
                    action.PRODUCT_IDS = action.PRODUCT_IDS.reduce<Record<string, number>>((map, id) => (map[id] = id, map), {}) as any
                    action.SECTION_IDS = action.SECTION_IDS.reduce<Record<string, number>>((map, id) => (map[id] = id, map), {}) as any
                }
            })
            return true
        }, 'children')

        return rules as any
    }

    const fillRules = (rules: BasketRule[]) => {
        basketRules.value = indexRules(rules)
    }


    return {
        discounts,
        fillRules,
        evaluateBenefitsResult,
        evaluateBenefitsResultDebounced,
        activeBenefits,
        activeCommonDiscount,
        basketBenefitsHash,
        activeDiscountsBySectionId,
        activeDiscountsByProductId,
        benefitTypes,
        benefitType
    }
})


