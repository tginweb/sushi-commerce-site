export function overwriteReactiveArray<T>(target: T[], value: T[] = []) {
    target.splice(0, target.length, ...value);
}
