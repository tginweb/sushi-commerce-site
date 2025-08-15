import {Directive} from 'vue'
import debounce from "lodash/debounce";
import {Screen} from 'quasar'

interface TabsScrollableBindingValue {
    param: string
}

const tabArrowClick = debounce((side: 'left' | 'right', target: HTMLInputElement) => {
    target.dispatchEvent(new Event('mousedown'));
}, 300)

const onTabsMouseover = (e: { target: HTMLInputElement }) => {
    if (Screen.gt.lg) {
        if (e.target.classList.contains('q-tabs__arrow--left')) {
            tabArrowClick('left', e.target)
        } else if (e.target.classList.contains('q-tabs__arrow--right')) {
            tabArrowClick('right', e.target)
        }
    }
}

export const tabsScrollableDirective: Directive<HTMLElement, TabsScrollableBindingValue> = {
    mounted(el, binding) {
        //const {param} = binding.value
        el.addEventListener('mouseover', onTabsMouseover as any)
    },

    unmounted(el) {
        el.removeEventListener('mouseover', onTabsMouseover as any)
    }
}
