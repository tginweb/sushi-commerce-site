export default function isEmptyScalar(value: any, zeroIsEmpty = false) {
    return value === '' ||
        value === null ||
        typeof value === 'undefined' ||
        zeroIsEmpty && (value === '0' || value === 0)
}
