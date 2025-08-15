import errorPrepare from "./errorPrepare";
import validate from "./validate";
import {ValidateErrors, ValidateMode, ValidateResult, ValidateRules} from "@/core/types";

export default function testRules(mode: ValidateMode, rules: ValidateRules, value?: any, errorsCollector?: ValidateErrors | null, ctx?: any): ValidateResult {

    const localErrors: ValidateErrors = []

    for (const rule of rules) {

        let res

        if (typeof rule === 'function') {
            res = rule(value, ctx)
        } else if (typeof rule === 'object') {
            const _rule = {...rule}
            res = validate(value, _rule)
            if (!res && _rule.message) {
                res = _rule.message
            }
        }

        if (res !== true) {
            let error = errorPrepare(res)
            if (error) {
                localErrors.push(error)
                if (mode === 'first') {
                    break
                }
            }
        }
    }
    if (localErrors.length) {
        if (errorsCollector) {
            Array.prototype.push.apply(errorsCollector, localErrors)
        }
        return localErrors
    }
    return true
}
