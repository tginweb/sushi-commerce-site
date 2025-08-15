import { Directive } from 'vue'

declare global {
    interface HTMLElement {
        _intersectObserver?: IntersectionObserver
    }
}

interface IntersectBindingValue {
    handler: (entry: IntersectionObserverEntry) => void
    options?: IntersectionObserverInit
}

export const intersectDirective: Directive<HTMLElement, IntersectBindingValue> = {
    mounted(el, binding) {
        const { handler, options } = binding.value
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => handler(entry))
        }, options)

        el._intersectObserver = observer
        observer.observe(el)
    },

    unmounted(el) {
        if (el._intersectObserver) {
            el._intersectObserver.disconnect()
            delete el._intersectObserver
        }
    }
}
