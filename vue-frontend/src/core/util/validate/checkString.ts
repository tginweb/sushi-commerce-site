import {ValidatorString} from "@/core/types";

export default function checkString(val: string, params: Omit<ValidatorString, 'type'> = {}, returnMessage = false) {

    const _val = val || ''

    if (params.min && _val.length < params.min) {
        return returnMessage ? 'минимальная длина ' + params.min : false
    }

    if (params.max && _val.length > params.max) {
        return returnMessage ? 'максимальная длина ' + params.max : false
    }

    if (params.regexp && _val.match(new RegExp(params.regexp))) {
        return returnMessage ? 'неверный формат' : false
    }

    return true
}
