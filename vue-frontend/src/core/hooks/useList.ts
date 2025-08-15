import {onMounted, Ref, ref, watch} from 'vue'
import {scrollTo as utilScrollTo} from "@/core/util/scrollTo";
import {useDebounceFn} from "@vueuse/core";

export type UseListParams = {
    elementId?: Ref<number | string>
    scrollOnMount?: boolean
}

export function useList<TComponent extends InstanceType<any>>(params: UseListParams) {

    const {elementId, scrollOnMount} = params

    const elementsRef = ref<Record<string, TComponent>>({})

    function setElementRef(el: any, id: number | string) {
        elementsRef.value[id] = el?.$el
    }

    const scrollTo = useDebounceFn((id: number | string) => {
        if (id) {
            const el = elementsRef.value[id]
            if (el) {
                utilScrollTo(el as any, 0, {}, true)
            }
        }
    }, 100)

    if (elementId) {
        watch(elementId, () => scrollTo(elementId.value))

        if (scrollOnMount) {
            onMounted(() => {
                scrollTo(elementId.value)
            })
        }
    }

    return {
        elementsRef,
        setElementRef,
        scrollTo
    }
}
