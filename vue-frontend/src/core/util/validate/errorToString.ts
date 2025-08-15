import {ValidateError} from "@/core/types";
import toArray from "@/core/util/toArray";

export default function errorToString(errors: ValidateError | ValidateError[] | string | string[] | boolean | null, limit: number = 1): string | null {
    if (errors) {
        return toArray(errors)
            .filter(error => !!error)
            .map(error => {
                if (typeof error === 'string') {
                    return error
                } else if (typeof error === 'object') {
                    return error.message
                }
            })
            .slice(0, limit)
            .join(', ')
    } else {
        return null
    }
}
