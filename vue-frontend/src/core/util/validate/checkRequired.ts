import {ValidatorRequired} from "@/core/types";

export default function checkRequired(val: any, params: Omit<ValidatorRequired, 'type'> = {}, returnMessage = false) {
    const _val = !!val
    return _val ? true : (returnMessage ? 'обязательное поле' : false)
}
