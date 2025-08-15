import {OrderProfile, OrderProfileAttributesValue} from "@/gql/gen";
import {computed} from "vue";
import {getOrderAttrsAddress} from "@/modules/shop/composable/orderable/hooks";
import {useShopStateStore} from "@/modules/shop/store/state";
import {GeoCoordinates} from "@/modules/geo/class/GeoCoordinates";
import deepToRaw from "@/core/util/vue/deepToRaw";
import {useTimers} from "@/core/store/timers";
import {storeToRefs} from "pinia";
import {getOrderProfileDeliveryLocationHash} from "@/modules/shop/util/getOrderProfileDeliveryLocationHash";
import saleProfileCalcDeliveryMutation from "@/gql/gen/mutation/saleProfileCalcDeliveryMutation";
import {useGraphql} from "@/core/graphql/service";

export type OrderProfileModel = ReturnType<typeof useProfileModel>

export function useProfileModel(source: OrderProfile) {

    const {useMutation, responseSelection} = useGraphql()


    const timers = useTimers()

    const {timestampIntervalMinute, timestampUnixIntervalMinute} = storeToRefs(timers)

    const saleStateStore = useShopStateStore()

    const payload = saleStateStore.profilesPayload[source.ID || 0] || {}

    const setAttrs = (attrs: Partial<OrderProfileAttributesValue>) => {
        Object.assign(source.ATTR, attrs)
    }

    const address = computed(() => getOrderAttrsAddress(source.ATTR))
    const coords = computed(() => source.ATTR.HOUSE_COORDS ? new GeoCoordinates(source.ATTR.HOUSE_COORDS) : null)
    const navCoords = computed(() => {
        const v = [source.ATTR.STREET_COORDS, source.ATTR.HOUSE_COORDS].find(Boolean)
        return v ? GeoCoordinates.toObject(v) : null
    })

    const addressHash = computed<string | null>(() => {
        return getOrderProfileDeliveryLocationHash(source)
    })

    const deliveryCalculate = computed(() => {
        return addressHash.value ? saleStateStore.deliveryCalculatesMap[addressHash.value]?.calc : null
    })

    const deliveryTimeMinutes = computed(() => {
        return deliveryCalculate.value && deliveryCalculate.value.RES_TIME ?
            Math.round((deliveryCalculate.value.RES_TIME - timestampUnixIntervalMinute.value) / 60) : null
    })

    const name = computed(() => address.value)
    const caption = computed(() => {
        const parts = [
            source.ATTR.PRIVATE_HOUSE && 'Частный дом' || source.ATTR.FLAT && `Квартира: ${source.ATTR.FLAT}`,
            source.ATTR.FLOOR && `Этаж: ${source.ATTR.FLOOR}`,
            source.ATTR.LIFT && 'Лифт',
        ]
        return parts.filter(Boolean).join(', ')
    })

    const beforeSave = () => {
        if (source.ATTR.PRIVATE_HOUSE) {
            source.ATTR.FLAT = null
            source.ATTR.LIFT = null
            source.ATTR.FLOOR = null
        }
    }

    const getRawAttrValues = () => {
        return deepToRaw(source.ATTR)
    }

    const textInputFields = computed(() => {
        return {
            address: source.ATTR.ADDRESS,
            street: source.ATTR.STREET,
            city: source.ATTR.CITY,
            house: source.ATTR.HOUSE,
        }
    })

    const deliveryCalculateNearest = useMutation(saleProfileCalcDeliveryMutation({
        payload: {
            __fragment: 'DeliveryCalculateFields'
        },
        ...responseSelection()
    }), {
        // @ts-ignore
        variables: computed(() => {
            return {
                profileId: source.ID || 0,
                attrs: getRawAttrValues(),
                timeMode: 'nearest'
            }
        }),
        onResult: ({success, payload}) => {
            console.log('payload', payload)
        }
    })

    return {
        id: source.ID || 0,
        addressHash,
        name,
        caption,
        source,
        payload,
        deliveryCalculate,
        address,
        coords,
        setAttrs,
        beforeSave,
        getRawAttrValues,
        deliveryTimeMinutes,
        textInputFields,
        navCoords,
        deliveryCalculateNearest,

    }
}
