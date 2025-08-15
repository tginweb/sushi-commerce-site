import {stackManager} from "../class/StackManager";
import {ref, watch} from "vue";
import {MenuItem} from "@/gql/gen";
import {EmitFn} from "@vue/runtime-core";
import {StackItemEmits, StackItemState} from "@/packages/stack-router/types";

export type TStackItemProps = {
    stackItemId?: string
    title?: string | undefined
    actions?: Partial<MenuItem>[] | undefined
    actionClose?: boolean | undefined
    onHeaderBack?: () => void
}

export const StackItemPropsDefault = {
    title: 'Popup'
}

export function useStackItem<TState extends StackItemState = StackItemState>(props: TStackItemProps, emit: EmitFn<StackItemEmits>) {

    const stackItemId = props.stackItemId!

    const stack = stackManager.getStackByItemId(stackItemId)
    const stackItem = stack.getById(stackItemId)
    const stackItemState: TState = stackItem ? stackItem.state : {}

    const visible = ref(stackItem ? stackItem.visible : false)

    if (stackItem) {
        watch(() => stackItem.visible, () => {
            visible.value = stackItem.visible.value
        })
        watch(visible, (value, oldValue, onCleanup) => {
            if (!value) {
                if (stackItem) {
                    stack.removeById(stackItem.id)
                }
                emit('hide', stackItem?.id || '')
                console.log('emit hide')
            } else {
                emit('show', stackItem?.id || '')
            }
        }, {immediate: true})
    }

    return {
        stack,
        stackItem,
        stackItemState,
        visible
    }
}
