export default function isEmptyObject(obj: any) {
    if (!obj)
        return false
    for (const prop in obj) {
        if (Object.hasOwn(obj, prop)) {
            return false;
        }
    }
    return true;
}
