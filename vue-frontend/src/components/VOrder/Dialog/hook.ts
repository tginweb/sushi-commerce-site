import {ValidateRules} from "@/core/types";
import {StackComponentProps} from "@/packages/stack-router/hooks/useStackComponent";
import {useBus} from "@/core/store/bus";

export type VOrderDialogProps = StackComponentProps & {
    mode?: string
    code?: string
    label?: string | undefined
    rules?: ValidateRules | undefined
    loading?: boolean | undefined
    validateOnClose?: boolean | undefined
    param?: any
}

export const vorderDialogPropsDefault = {
    rules: () => [],
    loading: false,
    validateOnClose: true
}

export function useVorderDialog(props: VOrderDialogProps) {
    const {bus} = useBus()
    const {validateOnClose, code} = props

    const listeners = {
        show: () => {

        },
        hide: () => {
            if (validateOnClose) {
                bus.emit('vorder:validate', 'dialog', code)
            }
        },
    }
    return {
        listeners
    }
}
