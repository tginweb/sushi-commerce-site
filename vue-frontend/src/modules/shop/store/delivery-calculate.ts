import {defineStore} from "pinia";
import {DeliveryCalculate, OrderAttributesValue} from "@/gql/gen";
import {toRefs} from "vue";
import {useShopStateStore} from "@/modules/shop/store/state";
import {toArray} from "@/core/util";
import {getOrderProfileDeliveryLocationHash} from "@/modules/shop/util/getOrderProfileDeliveryLocationHash";
import {OrderProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";
import saleProfileCalcDeliveryMutation from "@/gql/gen/mutation/saleProfileCalcDeliveryMutation";
import {useGraphql} from "@/core/graphql/service";

const STORE_NAME = 'shop-delivery-calculate'

export const useDeliveryCalculateStore = defineStore(STORE_NAME, () => {

    const {useMutation, responseSelection} = useGraphql();

    const state = useShopStateStore()

    const {
        deliveryCalculatesMap,
    } = toRefs(state)

    const profileCalculateRequest = useMutation(saleProfileCalcDeliveryMutation({
        payload: {
            __fragment: 'DeliveryCalculateFields'
        },
        ...responseSelection()
    }), {
        onResult: (result) => {

        }
    })

    const profileCalculate = async (profile: OrderProfileModel) => {
        const {success, payload} = await profileCalculateRequest.mutate({
            profileId: profile.id,
            attrs: profile.getRawAttrValues(),
            timeMode: 'nearest'
        })
        if (success !== false) {
            if (payload) {
                if (payload.RES_STATUS === 'success') {
                    const tag = 'profile.' + (profile.id ? profile.id : 'new')
                    const locationHash = getOrderProfileDeliveryLocationHash(profile.source)
                    if (locationHash) {
                        setDeliveryCalcResult(
                            locationHash,
                            tag,
                            profile.source.ATTR as any,
                            payload
                        )
                    }
                }
            }
        }
    };

    const setDeliveryCalcResult = (
        locationHash: string,
        tag: string | null = null,
        attrs: OrderAttributesValue,
        calc: DeliveryCalculate
    ) => {
        deliveryCalculatesMap.value[locationHash] = {
            tag,
            attrs,
            locationHash,
            fetchedAt: (Date.now() / 1000),
            calc,
        }
    }

    const clearResults = (criteria: {
        maxFetchedSeconds?: number,
        tag?: (string | number)[] | string | number,
        hash?: string,
    } = {}) => {

        for (const [hash, entry] of Object.entries(deliveryCalculatesMap.value)) {
            if (
                criteria.maxFetchedSeconds && (((Date.now() / 1000) - entry.fetchedAt) > criteria.maxFetchedSeconds) ||
                criteria.hash && entry.locationHash === criteria.hash ||
                criteria.tag && entry.tag && toArray(criteria.tag).includes(entry.tag)
            ) {
                delete deliveryCalculatesMap.value[hash]
            }
        }
    }

    setInterval(() => clearResults({maxFetchedSeconds: 30}), 1000 * 60 * 10)

    return {
        profileDeliveryCalculate: profileCalculate,
        profileDeliveryCalculateRequest: profileCalculateRequest,
        clearResults
    }
})


