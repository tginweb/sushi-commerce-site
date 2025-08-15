import {reactive, Ref, ref} from "vue";
import {Stack} from "./Stack";
import {TStackItemRoute} from "../types";
import {useQuasar} from "quasar";

export type StackItem = {
    stack: Stack
    id: string
    component: () => any
    props: Ref<any>
    route: TStackItemRoute
    visible: Ref<boolean>
    isRoutable: () => boolean
    close: () => void
    state: any
}

export function createStackItem(stack: Stack, id: string, _component: any, _props: any, route: TStackItemRoute = {}) {

    const visible = ref(true)
    const props = ref(_props)
    const component = () => _component

    const isRoutable = () => {
        return !!(stack.config.routable && route && (route.path || route.name))
    }

    const close = () => {
        visible.value = false
    }

    const state = reactive<any>({})

    return {
        stack,
        id,
        component,
        props,
        route,
        isRoutable,
        visible,
        close,
        state
    }
}

