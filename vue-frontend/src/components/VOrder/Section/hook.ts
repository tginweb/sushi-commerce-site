import {ValidateRules} from "@/core/types";

export type VOrderSectionProps = {
    code: string
    label?: string
    hideLabel?: boolean
    hint?: string
    rules?: ValidateRules
    loading?: boolean
    loadingText?: string
    icon?: any
    onClick?: any
    colClass?: any
    colStyle?: any
}

export const vorderSectionPropsDefault = {
    loading: false,
}

export function useVorderSection(props: VOrderSectionProps) {
    return {}
}
