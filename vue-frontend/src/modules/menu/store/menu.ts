import {defineStore} from "pinia";
import {computed, ref} from "vue";
import {Menu, MenuItem} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";

const STORE_NAME = 'menu'

export const useMenuStore = defineStore(STORE_NAME, () => {

    const menus = ref<Menu[]>([])

    const menuItems = computed(() =>
        menus.value.reduce<Record<string, MenuItem[]>>((map, item) =>
                (map[item.code || ''] = item.children as MenuItem[], map),
            {}
        )
    )

    const {registerScopeQuery} = useScopeQuery()

    registerScopeQuery(STORE_NAME, 'app', {
        menu_menus: {
            __fragment: 'MenuFields'
        },
    }, (data) => {
        menus.value = data.menu_menus as Menu[]
    })

    return {
        rootGetters: {
            menus
        },
        menus,
        menuItems
    }
})


