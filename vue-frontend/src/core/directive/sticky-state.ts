// directives/sticky.ts
import type {Directive, Ref} from 'vue';
import {computed, toRef, toValue} from 'vue';
import debounce from "lodash/debounce";

interface StickyConfig {
    activeClass: string;
    initialTop?: string | number | Ref<string | number>;
    top?: string | number | Ref<string | number>;
    disable?: boolean;
    edgeOffset?: number;
    debugId?: string
}

interface StickyDirectiveBinding {
    value?: string | StickyConfig;
    arg?: 'top' | 'bottom';
}

const stickyObserverMap = new WeakMap<HTMLElement, {
    cleanup: () => void
    update: (binding: any) => void
    updateDebounced: (binding: any) => void
}>()

export const stickyStateDirective: Directive<HTMLElement, StickyDirectiveBinding> = {
    mounted(el, binding) {
        initStickyObserver(el, binding as any)
    },
    updated(el, binding) {
        const stored = stickyObserverMap.get(el)
        if (stored) {
            stored.updateDebounced(binding)
        }
    },
    beforeUnmount(el) {
        const stored = stickyObserverMap.get(el)
        if (stored) {
            stored.cleanup();
            stickyObserverMap.delete(el);
        }
    }
};

function initStickyObserver(
    el: HTMLElement,
    binding: StickyDirectiveBinding
) {

    if (stickyObserverMap.get(el))
        return;

    const readParams = (binding: StickyDirectiveBinding, initial = false) => {
        const {value} = binding
        const config = parseBinding(value)

        const top = config.top ?? getComputedStyle(el).top

        const topValue = toValue(top)
        const topPx = convertToPixels(topValue, el)
        const edgeOffset = config.edgeOffset ?? 1
        const position: 'top' | 'bottom' = 'top'
        const disable = config.disable
        const activeClass = config.activeClass
        const debugId = config.debugId

        return {
            config,
            position,
            top,
            topValue,
            topPx,
            edgeOffset,
            disable,
            activeClass,
            debugId
        }
    }

    const params = readParams(binding, true)

    let top = toRef(params.top)
    let topPx = computed(() => convertToPixels(top.value, el))

    let {
        edgeOffset,
        activeClass,
        position,
        disable,
        debugId
    } = params

    if (disable)
        return;

    let observer: IntersectionObserver | null = null

    const updateStickyState = (rect: DOMRect) => {
        const isSticky = position === 'top'
            ? rect.top <= topPx.value + edgeOffset
            : rect.bottom >= window.innerHeight - topPx.value - edgeOffset;
        el.classList.toggle(activeClass, isSticky);
        el.dispatchEvent(new CustomEvent('sticky-change', {
            detail: {isSticky, offset: topPx.value}
        }));
    }

    const handleIntersect = (entries: IntersectionObserverEntry[]) => {
        entries.forEach(entry => {
            updateStickyState(entry.boundingClientRect);
        })
    }

    const checkPosition = () => {
        const rect = el.getBoundingClientRect()
        updateStickyState(rect)
    }

    const checkPositionDebounced = debounce(checkPosition, 50)

    const initObserver = () => {
        const rootMargin = calculateRootMargin(position, topPx.value, edgeOffset)
        observer = new IntersectionObserver(
            handleIntersect,
            {root: null, rootMargin, threshold: [0, 0.1, 0.5, 0.9, 1]}
        )
        observer.observe(el)
    }

    const removeObserver = () => {
        if (observer)
            observer.disconnect()
    }

    const initListeners = () => {
        initObserver()
        document.addEventListener("scroll", checkPositionDebounced)
    }

    const removeListeners = () => {
        removeObserver()
        document.removeEventListener("scroll", checkPositionDebounced)
    }

    const cleanup = () => {
        removeListeners()
    }

    const update = (binding: StickyDirectiveBinding) => {

        const newParams = readParams(binding)

        const newTopPx = newParams.topPx
        const prevTopPx = topPx.value

        if (newTopPx !== prevTopPx) {

            if (debugId === 'vorder') {
                //console.log('on update changed', {prevTopPx: topPx.value, newTopPx: newTopPx,})
            }

            top.value = toValue(newParams.top)
            edgeOffset = newParams.edgeOffset
            disable = newParams.disable

            removeObserver()
            initObserver()
        }

    }

    const updateDebounced = debounce(update, 200)

    initListeners()

    stickyObserverMap.set(el, {cleanup, update, updateDebounced})
}

function parseBinding(bindingValue: any): StickyConfig {
    if (typeof bindingValue === 'string') {
        return {activeClass: bindingValue};
    }
    if (typeof bindingValue === 'object') {
        return {
            activeClass: bindingValue.activeClass || 'sticky-active',
            top: bindingValue.top,
            initialTop: bindingValue.initialTop,
            edgeOffset: bindingValue.edgeOffset,
            disable: bindingValue.disable,
            debugId: bindingValue.debugId,
        };
    }
    return {activeClass: 'sticky-active'};
}

function convertToPixels(value: string | number, el: HTMLElement): number {
    if (typeof value === 'number')
        return value
    const num = parseFloat(value);
    if (value.endsWith('px')) return num;
    if (value.endsWith('%')) {
        const parentHeight = el.parentElement?.offsetHeight || 0;
        return (num * parentHeight) / 100;
    }
    if (value.endsWith('vh')) {
        return (num * window.innerHeight) / 100;
    }
    return num;
}

function calculateRootMargin(
    position: 'top' | 'bottom',
    topPx: number,
    edgeOffset: number
): string {
    return position === 'top'
        ? `-${topPx + edgeOffset}px 0px 0px 0px`
        : `0px 0px -${topPx + edgeOffset}px 0px`;
}


