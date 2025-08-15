import {computed, reactive, ref} from "vue";

type UseLayoutResizeData = {
    height: number
    width: number
}

type LayoutDirection = 'vertical' | 'horizontal'

export function useLayout(dir: LayoutDirection = 'vertical', initialContainerSize = 0) {

    const elements = reactive<Record<string, number>>({
        header: 0,
        footer: 0
    })

    const containerSize = ref(initialContainerSize)

    const onElementResize = (name: string, size: UseLayoutResizeData) => {
        let value = dir === 'vertical' ? size.height : size.width
        if (value && value > 0) {
            elements[name] = value
        }
    }

    const usedSize = computed(() => Object.keys(elements).reduce((acc, name) => {
        acc += elements[name] || 0
        return acc
    }, 0))

    const freeSize = computed(() => containerSize.value - usedSize.value)
    const freeSizeCss = computed(() => freeSize.value + 'px')

    return {
        elements,
        containerSize,
        freeSize,
        freeSizeCss,
        usedSize,
        onElementResize,
        onResizeContainer: (size: UseLayoutResizeData) => containerSize.value = (dir === 'vertical' ? size.height : size.width),
        onResizeHeader: (size: UseLayoutResizeData) => onElementResize('header', size),
        onResizeFooter: (size: UseLayoutResizeData) => onElementResize('footer', size),
        footerHeightCss: elements.footer + 'px',
        headerHeightCss: elements.footer + 'px'
    }
}
