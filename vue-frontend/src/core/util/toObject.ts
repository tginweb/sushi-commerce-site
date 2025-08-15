import isScalar from "./isScalar";

export function toObject(v: any, clone?: boolean) {
    if (isScalar(v) || !v) {
        return {}
    } else {
        return clone ? {...v} : v
    }
}

export default toObject
