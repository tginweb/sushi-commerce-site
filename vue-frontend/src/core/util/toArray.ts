export function toArray<T = any>(v: T | T[], clone?: boolean): T[] {
    return v ? (!Array.isArray(v) ? [v] : (clone ? [...v] : v)) : []
}
export default toArray
