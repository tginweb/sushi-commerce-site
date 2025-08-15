import {stackManager} from "../class/StackManager";
import {TStackConfig, TStackProps} from "../types";

export const StackPropsDefault = {
    namespace: 'modal',
    routable: false
}

export function useStack<TConfig extends TStackConfig = TStackConfig>(props: TStackProps) {
    const stack = stackManager.getStack<TConfig>(props.namespace, props as unknown as TConfig)
    return {
        stack
    }
}
