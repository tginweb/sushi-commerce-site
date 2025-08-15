import {MenuItem} from "@/gql/gen";
import {isRef, Ref} from "vue";

export type ActionsItem = Partial<MenuItem>

export type ActionsItems = ActionsItem[]

export type ActionsItemsUnfiltered = (ActionsItem | null | undefined | boolean)[]

export type ActionsSchema = {
    actions: ActionsItems
    closeAction?: boolean | string | null
    class?: any
}

export type ActionsSchemaUnfiltered = Omit<ActionsSchema, 'actions'> & {
    actions: ActionsItemsUnfiltered
}

export type ActionsGeneratorValue = ActionsSchemaUnfiltered | ActionsItems | ActionsItemsUnfiltered
export type ActionsGeneratorBase = ((items: ActionsSchemaUnfiltered) => ActionsGeneratorValue | void)
export type ActionsGenerator = ActionsGeneratorValue | ActionsGeneratorBase | Ref<ActionsGeneratorValue>


export default function extractActionsSchema(info: ActionsGenerator | null | undefined, initial?: ActionsSchemaUnfiltered): ActionsSchema | null {
    if (!info)
        return null
    let result: ActionsSchemaUnfiltered = {
        actions: [],
        ...(initial || {})
    }
    if (isRef(info)) {
        const val = info.value
        if (val) {
            if (Array.isArray(val)) {
                result.actions = val
            } else {
                result = {
                    ...result,
                    ...val
                }
            }
        }
    } else if (Array.isArray(info)) {
        result.actions = [...result.actions, ...info]
    } else if (typeof info === "function") {
        const res = info(result)
        if (res) {
            result = {
                ...result,
                ...res
            }
        }
    }
    result.actions = result.actions.filter((action) => !!action)
    return result as ActionsSchema
}

