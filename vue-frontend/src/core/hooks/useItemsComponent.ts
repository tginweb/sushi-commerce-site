import {computed} from "vue";
import getKeyable from "@/core/util/getKeyable";
import getItemField from "@/core/util/getItemField";

export const itemsComponentPropsDefault = {
    items: () => [],
    itemLabel: 'label',
    itemValue: 'value',
}

export type ItemsComponentPropsBase = {
    itemLabel?: string
    itemValue?: string
}

export type ItemsComponentProps = ItemsComponentPropsBase & {
    items: any[]
}

export type ItemsComponentPropsWithDefaults = Omit<ItemsComponentProps, 'itemLabel' | 'itemValue'> & {
    itemLabel: string | ((item: any) => string)
    itemValue: string | ((item: any) => string)
}

export type ItemsComponentItem = {
    label: string
    value: any
    key: string
}

export type ItemsComponentAdditionalField = {
    name: string
    propName: string
    default: any
}

export const useItemsComponent = <
    TItem extends ItemsComponentItem = ItemsComponentItem,
>(
    props: ItemsComponentPropsWithDefaults,
    additionalFields: ItemsComponentAdditionalField[] = []
) => {
    const items = computed<TItem[]>(() => props.items.map((item, index) => {
        const label = getItemField(item, props.itemLabel)
        const value = getItemField(item, props.itemValue)
        return {
            key: getKeyable(value, label, index),
            value: value,
            label: label,
            ...additionalFields.reduce<{ [key in keyof TItem]?: any }>((map, field) => (map[field.name as keyof TItem] = getItemField(item, props[field.propName as keyof ItemsComponentPropsWithDefaults] as string), map), {}),
            data: item
        } as unknown as TItem
    }))

    return {
        items,
    }
}
