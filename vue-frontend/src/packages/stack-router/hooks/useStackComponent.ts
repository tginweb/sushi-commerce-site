import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {StackItem} from "@/packages/stack-router/class/StackItem";
import {StackItemState} from "@/packages/stack-router/types";

export type StackComponentProps = {
    stackItemId?: string | undefined
}

export function useStackComponent<TState extends StackItemState = StackItemState>(props: StackComponentProps) {

    const {stackItemId} = props
    let stackItem: StackItem | undefined
    let stackItemState: TState = {} as TState

    let bind: any = {}

    if (stackItemId) {
        const stackRouter = useStackRouter()
        stackItem = stackRouter.getItemById(stackItemId)
        bind = {
            ...bind,
            stackItemId
        }
        stackItemState = stackItem?.state
    }

    return {
        bind,
        stackItem,
        stackItemState
    }
}

export function useStackComponentWithStandalone<TStackProps extends StackComponentProps = StackComponentProps, TNoStackProps = any>(
    props: StackComponentProps,
    stackProps: Omit<TStackProps, 'stackItemId'> & {
        is: any
    },
    nostackProps?: TNoStackProps & {
        is: any
    }
) {

    const stackItemId = props.stackItemId

    let bind: {
        stackItemId: string
        is?: any
    } = {
        stackItemId: stackItemId || ''
    }

    if (stackItemId) {
        if (stackProps) {
            bind = {
                ...bind,
                ...stackProps
            }
        }
    } else {
        if (nostackProps) {
            bind = {
                ...bind,
                ...nostackProps
            }
        }
    }

    return {
        bind
    }
}
