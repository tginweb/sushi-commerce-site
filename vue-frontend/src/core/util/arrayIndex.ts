export function arrayIndex<T, K extends keyof T>(
    array: T[],
    keyField: K | ((item: T) => string | number)
): Record<string, T> {
    return array.reduce((acc, item) => {
        const keyValue = typeof keyField === 'function' ? keyField(item) : item[keyField];
        if (keyValue === null || keyValue === undefined) {
            return acc;
        }
        acc[keyValue as string] = item
        return acc;
    }, {} as Record<string, T>);
}
