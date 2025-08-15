import {Validator} from "@/core/types";

import checkEmail from "./checkEmail";
import checkPhone from "./checkPhone";
import checkNumber from "./checkNumber";
import checkString from "./checkString";
import checkRequired from "./checkRequired";

export default function validate(val: any, params: Validator, returnMessage = false) {
    const _val = typeof val === "string" ? val.trim() : val
    if (params.type !== 'required' && !_val) {
        return true
    }
    switch (params.type) {
        case 'email':
            return checkEmail(_val, params, returnMessage)
        case 'phone':
            return checkPhone(_val, params, returnMessage)
        case 'number':
            return checkNumber(_val, params, returnMessage)
        case 'string':
            return checkString(_val, params, returnMessage)
        case 'required':
            return checkRequired(_val, params, returnMessage)
    }
    //@ts-ignore
    return true
}


