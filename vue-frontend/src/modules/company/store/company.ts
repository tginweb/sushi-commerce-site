import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {CompanyOffice, DeliveryZoneElement} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";

const STORE_NAME = 'company'

export const useCompanyStore = defineStore(STORE_NAME, () => {

    const city = ref<string>()

    const offices = ref<CompanyOffice[]>([])
    const officesById = computed(() =>
        offices.value.reduce<Record<string, CompanyOffice>>((map, item) => (map[item.ID] = item, map), {})
    )

    const {registerScopeQuery} = useScopeQuery()

    registerScopeQuery(STORE_NAME, 'app', {
        company_office_list: {
            __fragment: 'OfficeFields'
        },
    }, (data) => {
        offices.value = data.company_office_list

    })

    return {
        offices,
        officesById,
        city
    }
})
