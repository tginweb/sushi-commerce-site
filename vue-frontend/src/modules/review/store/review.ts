import {Review} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {defineStore} from "pinia";
import {ref} from "vue";

const STORE_NAME = 'review'

export const useReviewStore = defineStore(STORE_NAME, () => {

    const reviews = ref<Review[]>([])

    const {registerScopeQuery} = useScopeQuery()

    registerScopeQuery(STORE_NAME, 'app', {
        review_list: {
            __fragment: 'ReviewFields'
        },
    }, (data) => {
        reviews.value = data.review_list
    })

    return {
        reviews
    }
})


