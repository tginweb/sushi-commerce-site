export function overwriteReactiveObject(target: Record<string, any>, value: Record<string, any> = {}) {
    const cleared = Object.fromEntries(Object.keys(target).map(key => [key, undefined]))
    return Object.assign(target, cleared, value);
}
