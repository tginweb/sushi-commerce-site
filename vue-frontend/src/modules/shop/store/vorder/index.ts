import {defineStore, storeToRefs} from "pinia";
import {computed, ref, toRaw, watch} from "vue";
import {
    Coupon,
    DeliveryCalculate,
    DeliveryComputed,
    Order,
    OrderAttribute,
    OrderAttributeCodeEnum,
    OrderAttributesValue,
    PaymentType,
    VorderCurrent
} from "@/gql/gen";

import {useScopeQuery} from "@/core/store/scopeQuery";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {isEqual} from "lodash-es";
import {getTypedEntries} from "@/core/util/getTypedEntries";
import {getRulesDelivery, getRulesPickup, getRulesTime} from "@/modules/shop/store/vorder/validate";
import {OrderProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";
import {useGraphql} from "@/core/graphql/service";
import saleVorderReserveMutation from "@/gql/gen/mutation/saleVorderReserveMutation";
import {useVorderBasketStore} from "@/modules/shop/store/vorder/basket";
import {BasketRulesEvaluateContext} from "@/modules/shop/store/vorder/util/benefit";
import {useVorderBenefitStore} from "@/modules/shop/store/vorder/benefit";
import {useShopStore} from "@/modules/shop/store/shop";

const STORE_NAME = 'vorder'

export type VorderTab = 'basket' | 'form'

export const useVorderStore = defineStore(STORE_NAME, () => {

    const {useMutation} = useGraphql()
    const {registerScopeQuery} = useScopeQuery()

    const {} = useShopStore()
    const catalogStore = useCatalogStore()
    const basketStore = useVorderBasketStore()
    const benefitStore = useVorderBenefitStore()

    const {evaluateBenefitsResultDebounced} = benefitStore

    const {discounts} = storeToRefs(benefitStore)

    const {} = storeToRefs(catalogStore)
    const {basketItemsModels, basketItemsWatchable, basketPrice} = storeToRefs(basketStore)

    const vorderId = ref(0)
    const tab = ref<VorderTab>('form')
    const attrsInfo = ref<OrderAttribute[]>([])
    const deliveries = ref<DeliveryComputed[]>([])
    const paymentTypes = ref<PaymentType[]>([])
    const bonusesPercent = ref(20)
    const deliveryCalc = ref<DeliveryCalculate>()
    const vorderSynced = ref(false)


    // SESSION
    const bonusesAvailable = ref(0)
    const coupons = ref<Coupon[]>([])
    const order = ref<Order>()
    const attrs = ref<OrderAttributesValue>({} as OrderAttributesValue)

    // COMPUTED

    const bonuses = computed(() => attrs.value.BONUSES || 0)
    const bonusesMax = computed(() => attrs.value.BONUSES || 0)

    const deliveryById = computed(() => deliveries.value.reduce<Record<string, DeliveryComputed>>((map, item) => (map[item.ID] = item, map), {}))
    const currentDeliveryId = computed(() => attrs.value.DELIVERY_ID)
    const currentDelivery = computed(() => deliveries.value.find(item => item.ID === currentDeliveryId.value))

    const attrByCode = computed(() => {
        return attrsInfo.value.reduce<{
            [key in OrderAttributeCodeEnum]?: OrderAttribute
        }>((map, item) => {
            map[item.CODE] = item
            return map
        }, {})
    })

    const deliveryPrice = computed(() => {
        return 0
    })

    const rulesPickup = computed(() => getRulesPickup(attrs.value))
    const rulesDelivery = computed(() => getRulesDelivery(attrs.value))
    const rulesTime = computed(() => getRulesTime(attrs.value))

    // ACTIONS

    const onAttrChanged = (attrName: keyof OrderAttributesValue, attrValue: any, prevValue: any) => {

    }

    const updateAttrs = (attrValues: OrderAttributesValue) => {
        let changed: {
            [key in keyof OrderAttributesValue]?: {
                value: OrderAttributesValue[key],
                prevValue: OrderAttributesValue[key]
            }
        } = {}

        const changedValues: {
            [key in keyof OrderAttributesValue]?: OrderAttributesValue[key]
        } = {}

        for (let [attrName, profileValue] of getTypedEntries(attrValues)) {
            const vorderValue = toRaw(attrs.value[attrName as keyof OrderAttributesValue])
            profileValue = toRaw(profileValue)

            if (typeof profileValue !== 'boolean' &&
                typeof vorderValue !== 'boolean' &&
                !profileValue &&
                !vorderValue)
                continue;

            if (!isEqual(profileValue, vorderValue)) {
                changed[attrName] = {
                    value: profileValue,
                    prevValue: vorderValue
                }
                changedValues[attrName] = profileValue
            }
        }

        if (Object.values(changed).length) {
            attrs.value = {
                ...attrs.value,
                ...changedValues
            }
        }

        console.log('updateAttrs', {
            changed,
            changedValues
        })

        for (const [attrName, attrChange] of getTypedEntries(changedValues)) {
            onAttrChanged(attrName, attrChange.value, attrChange.prevValue)
        }

        return {
            changed,
            changedValues
        }
    }

    const applyDeliveryProfile = (model: OrderProfileModel, changed = false) => {
        const profile = model.source
        attrs.value['PROFILE_ID'] = profile.ID
        updateAttrs(model.getRawAttrValues())
    }

    const setAttr = <T extends keyof OrderAttributesValue = keyof OrderAttributesValue>(code: T, value: OrderAttributesValue[T]) => {
        attrs.value[code] = value
    }

    const fillVorder = (data: VorderCurrent) => {

        vorderId.value = data.ID || 0

        if (data.ATTR)
            attrs.value = data.ATTR

        if (data.ATTRS)
            attrsInfo.value = data.ATTRS

        deliveries.value = data.DELIVERIES

        bonusesAvailable.value = data.BONUSES_AVAILABLE || 0
        bonusesPercent.value = data.BONUSES_PERCENT || 20

        if (data.ORDER) {
            order.value = data.ORDER
        }

        paymentTypes.value = data.PAYMENT_TYPES
        deliveries.value = data.DELIVERIES
        discounts.value = data.DISCOUNTS

        vorderSynced.value = true
    }


    // SCOPE QUERY

    registerScopeQuery(STORE_NAME, 'user', {
        sale_vorder_current: {
            __fragment: 'VorderCurrentFields'
        },
    }, (data) => {
        if (data.sale_vorder_current)
            fillVorder(data.sale_vorder_current)
    })

    // UTIL

    const inputPayload = computed(() => ({
        order: attrs.value,
        basket: [],
    }))

    const reserveRequest = useMutation(saleVorderReserveMutation({
        payload: {
            vorder: {
                __fragment: 'VorderCurrentFields'
            },
            calc: {
                __fragment: 'DeliveryCalculateFields'
            },
        }
    }))

    const reserve = () => {

        reserveRequest.mutate({
            order: inputPayload.value,
        })
        console.log('deliveryRequestReserve')
    }

    const getBenefitRulesEvaluateContext = (): BasketRulesEvaluateContext => {
        return {
            basketPrice: basketPrice.value,
            deliveryPrice: deliveryPrice.value,
            basketItems: basketItemsModels.value,
            basketProductIds: Object.values(basketItemsModels.value.reduce<Record<number, number>>((map, item) => (map[item.fields.PRODUCT_ID] = item.fields.PRODUCT_ID, map), {})),
            basketSectionIds: Object.values(basketItemsModels.value.reduce<Record<number, number>>((map, item) => {
                if (item.product) {
                    for (const sectionId of item.product.IBLOCK_SECTION_IDS) {
                        map[sectionId] = sectionId
                    }
                }
                return map
            }, {})),
            orderAttrs: attrs.value,
            bonuses: bonuses.value,
            coupons: coupons.value.length
        }
    }

    watch([basketItemsWatchable], () => {
        const context = getBenefitRulesEvaluateContext()
        evaluateBenefitsResultDebounced(context)
    })

    return {
        tab,

        setAttr,
        attrs,
        attrByCode,
        attrsInfo,

        deliveries,
        deliveryById,

        // delivery
        currentDeliveryId,
        currentDelivery,


        // order actions
        vorderSynced,
        order,

        // bonus
        bonusesAvailable,
        bonusesPercent,
        bonuses,

        // rules
        rulesPickup,
        rulesDelivery,
        rulesTime,

        applyDeliveryProfile,

        deliveryCalc,
        bonusesMax
    }
})

