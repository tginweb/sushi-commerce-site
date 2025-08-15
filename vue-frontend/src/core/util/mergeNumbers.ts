export function mergeNumbers(map: Record<number, number> | number[], numbers: number[] | Record<number, number>) {
    if (!Array.isArray(map) && Array.isArray(numbers)) {
        Object.assign(map, numbers.reduce<Record<number, number>>((acc, num) => (acc[num] = num, acc), {}))
    } else if (!Array.isArray(map) && !Array.isArray(numbers)) {
        Object.assign(map, numbers)
    } else if (Array.isArray(map) && Array.isArray(numbers)) {
        map.push(...numbers.filter(item => !map.includes(item)))
    } else if (Array.isArray(map) && !Array.isArray(numbers)) {
        map.push(...Object.values(numbers).filter(item => !map.includes(item)))
    }
}
