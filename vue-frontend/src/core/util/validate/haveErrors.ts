import {Maybe, ValidateErrors, ValidateResult} from "@/core/types";

export default function haveErrors(data: Maybe<ValidateErrors | ValidateResult | boolean>): boolean {
    if (data) {
        if (data === true) {
            return false
        } else if (Array.isArray(data)) {
            return data.length > 0
        } else {
            return true
        }
    } else {
        return false
    }
}
