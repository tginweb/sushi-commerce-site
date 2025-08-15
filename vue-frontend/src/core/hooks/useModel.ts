import {toRaw, toValue} from 'vue'
import {cloneDeep} from "lodash-es";

export type WithModel = {
    getJson: () => any
    getClonedJson: () => any
}

export function useModel(model: any, observerable = false) {

    const getJson = () => {
        return Object.keys(model).reduce<Record<string, any>>((map, key: any) => {
            const val = model[key as keyof typeof model]
            map[key] = toValue(val)
            return map
        }, {})
    }

    const getClonedJson: any = () => {
        return cloneDeep(toRaw(getJson()))
    }

    return {
        getJson,
        getClonedJson
    }
}
