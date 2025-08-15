export default function isEmpty(value: any, zeroIsEmpty = false) {
    if (value == null) return true;
    if (typeof value === 'string') return value === '';
    if (Array.isArray(value)) return value.length === 0;
    if (value.constructor === Object) return Object.keys(value).length === 0;
    if (zeroIsEmpty && (value === '0' || value === 0)) return true
    return false;
}
