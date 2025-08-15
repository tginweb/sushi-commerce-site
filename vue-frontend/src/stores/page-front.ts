import {defineStore, storeToRefs} from "pinia";
import {nextTick, ref, watch} from "vue";
import {useHashScroll} from "@/core/hooks/useHashScroll";
import {useLayoutStore} from "@/stores/layout";

const STORE_NAME = 'page-front'

export const usePageFrontStore = defineStore(STORE_NAME, () => {

    const layoutStore = useLayoutStore()

    const {headerHeight} = storeToRefs(layoutStore)

    const scrollNav = useHashScroll({
        find: true,
        scrollOffset: headerHeight,
    })

    const activeSection = ref<string | null>(null)

    const activeSectionStopUpdate = ref<boolean>(false)

    const setActiveSection = (code: string) => {
        if (activeSectionStopUpdate.value)
            return;
        activeSection.value = code
        scrollNav.scrollTo(activeSection.value)
    }

    watch(scrollNav.activeHash, async (v) => {
        activeSectionStopUpdate.value = true
        activeSection.value = scrollNav.activeHash.value
        await nextTick()
        activeSectionStopUpdate.value = false
    }, {immediate: true})

    return {
        setActiveSection,
        activeSection,
        scrollNav
    }
})

