import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {Offer} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";

const STORE_NAME = 'offer'

export const useOfferStore = defineStore(STORE_NAME, () => {

    const offersCommon = ref<Offer[]>([])

    const offersCommonComputed = computed<OfferModel[]>(() => offersCommon.value.map(offer => ({
        ...offer
    })))

    const {registerScopeQuery} = useScopeQuery()

    registerScopeQuery(STORE_NAME, 'app', {
        offer_common_list: {
            __fragment: 'OfferFields'
        },
    }, (data) => {
        offersCommon.value = data.offer_common_list as Offer[]
    })

    return {
        offersCommon,
        offersCommonComputed
    }
})

export interface OfferModel extends Offer {

}

