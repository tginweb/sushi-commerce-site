import errorPrepare from "./errorPrepare";
import {ValidateErrors} from "@/core/types";

export default function errorsPrepare(data: ValidateErrors): ValidateErrors {
    const res = []
    for (const item of data) {
        const error = errorPrepare(item)
        if (error) {
            res.push(error)
        }
    }
    return res
}
