import {ValidateError, ValidateRuleResult} from "@/core/types";

export default function errorPrepare(data: ValidateError | ValidateRuleResult): ValidateError | null {

    let error: ValidateError

    if (data) {

        error = {
            type: 'error',
        } as ValidateError

        if (typeof data === 'string') {
            error.message = data
        } else if (typeof data === 'object') {
            error = {
                ...error,
                ...data
            }
        } else {
            error.message = 'Ошибка'
        }

        return error
    }

    return null
}
