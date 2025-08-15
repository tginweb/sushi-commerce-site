import {defineStore} from "pinia";
import {computed, ref} from "vue";

const STORE_NAME = 'layout'

export const useLayoutStore = defineStore(STORE_NAME, () => {

    const initialHeaderHeight = 62

    const drawerMainModel = ref<boolean>(false)
    const drawerBasket = ref<boolean>(true)
    const drawerBasketMobile = ref<boolean>(false)

    const headerHeight = ref<number>(initialHeaderHeight)
    const headerHeightCss = computed(() => headerHeight.value + 'px')

    const sideColumnTopOffset = ref<number>(initialHeaderHeight)
    const sideColumnTopOffsetCss = computed(() => sideColumnTopOffset.value + 'px')

    return {
        headerHeight,
        headerHeightCss,
        drawerMainModel,
        drawerBasket,
        drawerBasketMobile,
        sideColumnTopOffset,
        sideColumnTopOffsetCss
    }
})

