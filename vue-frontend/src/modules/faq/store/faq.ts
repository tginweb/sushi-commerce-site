import {FaqQuestion} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {defineStore} from "pinia";
import {ref} from "vue";

const STORE_NAME = 'faq'

export const useFaqStore = defineStore(STORE_NAME, () => {

    const faqElements = ref<FaqQuestion[]>([])

    const {registerScopeQuery} = useScopeQuery()

    registerScopeQuery(STORE_NAME, 'app', {
        faq_element_list: {
            __fragment: 'FaqElementFields'
        },
    }, (data) => {
        faqElements.value = data.faq_element_list
    })

    return {
        faqElements
    }
})

