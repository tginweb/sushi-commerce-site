import {computed, Ref} from "vue";
import getKeyable from "@/core/util/getKeyable";
import getItemField from "@/core/util/getItemField";

export const optionsComponentPropsDefault = {
    options: () => [],
    optionLabel: 'label',
    optionValue: 'value',
    optionIcon: 'icon'
}

export type OptionsComponentPropsBase = {
    optionLabel?: string
    optionValue?: string
    optionIcon?: string
    optionImage?: string
}

export type OptionsComponentProps = OptionsComponentPropsBase & {
    options: any[]
}

export type OptionsComponentPropsWithDefaults =
    Omit<OptionsComponentProps, 'optionLabel' | 'optionValue' | 'optionIcon'>
    & {
    optionLabel: string | ((option: any) => string)
    optionValue: string | ((option: any) => string)
    optionIcon: string | ((option: any) => string)
    optionImage: string | ((option: any) => string)
}

export type OptionsComponentOption<TData = any> = {
    label: string
    value: any
    key: string
    icon?: any
    data: TData
}

export type OptionsComponentAdditionalField = {
    name: string
    propName: string
    default: any
}

export const useOptionsComponent = <
    TOption extends OptionsComponentOption = OptionsComponentOption,
    TOptionValue = any,
>(
    props: OptionsComponentPropsWithDefaults,
    model?: Ref<TOptionValue | null | undefined>,
    additionalFields: OptionsComponentAdditionalField[] = [],
    mapper: (option: any) => Partial<TOption> = (option) => option
) => {

    const options = computed<TOption[]>(() => props.options.map((option, index) => {
        const label = getItemField(option, props.optionLabel)
        const value = getItemField(option, props.optionValue)
        const icon = getItemField(option, props.optionIcon)
        return mapper({
            key: getKeyable(value, label, index),
            value,
            label,
            icon,
            ...additionalFields.reduce<{ [key in keyof TOption]?: any }>((map, field) => (map[field.name as keyof TOption] = getItemField(option, props[field.propName as keyof OptionsComponentPropsWithDefaults] as string), map), {}),
            data: option
        }) as unknown as TOption
    }))

    //console.log('options', options.value)

    const selectedOption = computed(() => {
        return model ? options.value.find(option => option.value === model.value) : null
    })

    return {
        options,
        selectedOption
    }
}
