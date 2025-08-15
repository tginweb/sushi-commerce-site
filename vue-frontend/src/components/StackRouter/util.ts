import {MenuItem} from "@/gql/gen";

export function createStackCloseAction(data: Partial<MenuItem> | string | boolean, accent = false, callback?: any) {
    let result = {
        outline: !accent
    } as MenuItem
    if (data) {
        if (typeof data === 'string') {
            result.label = data
        } else if (typeof data === 'object') {
            result = {...result, ...data}
        }
    }
    result.onClick = result.onClick ? result.onClick : callback
    return result
}
