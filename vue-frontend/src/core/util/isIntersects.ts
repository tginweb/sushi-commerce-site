export function isIntersects(values1: any[], values2: any[]) {
    return !!values1.find(value => values2.includes(value))
}
