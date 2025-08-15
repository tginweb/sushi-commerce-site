import {toNumber} from "lodash-es";
import {ValidatorNumber} from "@/core/types";

export default function checkNumber(val: string, params: Omit<ValidatorNumber, 'type'> = {}, returnMessage = false) {
    const _val = toNumber(val)
    if (params.min && _val < params.min) {
        return returnMessage ? 'минимальное значение ' + params.min : false
    }
    if (params.max && _val > params.max) {
        return returnMessage ? 'максимальное значение ' + params.max : false
    }
    return true
}
