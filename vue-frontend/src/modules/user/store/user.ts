import {defineStore, storeToRefs} from "pinia";
import {computed, ref} from "vue";
import {User} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {useMenuStore} from "@/modules/menu/store/menu";
import {useRootStore} from "@/stores/root";

const STORE_NAME = 'user'

export const useUserStore = defineStore("user", () => {

    const {registerScopeQuery} = useScopeQuery()
    const menuStore = useMenuStore()
    const rootStore = useRootStore()

    const {storeGetter} = rootStore

    const {menuItems} = storeToRefs(menuStore)

    const user = ref<User | null>()

    const isAuthorized = computed(() => {
        return !!user.value
    })

    const menuPersonal = computed(() => {
        const items = menuItems.value.personal || []
        return items.map((item) => ({
            ...item,
            badgeLabel: item.badge ? (item.badge.getter ? storeGetter[item.badge.getter] : item.badge.label) : ''
        }))
    })

    registerScopeQuery(STORE_NAME, 'user', {
        user_fetch: {
            __fragment: 'UserFields'
        },
    }, (data) => {
        user.value = data.user_fetch
    })

    return {
        user,
        isAuthorized,
        menuPersonal
    }
})

