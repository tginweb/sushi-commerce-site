import {get} from "lodash-es";

export function getItemField<T extends any = any, K extends keyof T = keyof T>(item: T, field: K | string | ((item: T) => any) | undefined | null, def = undefined) {
    if (!item || !field)
        return def
    if (typeof field === "string") {
        if (typeof item[field as keyof T] !== "undefined") {
            return item[field as keyof T]
        } else if (field.indexOf('.') > -1) {
            const res = get(item, field)
            return typeof res !== 'undefined' ? res : def
        }
    } else if (typeof field === 'function') {
        return field(item)
    }
    return def
}

export default getItemField
