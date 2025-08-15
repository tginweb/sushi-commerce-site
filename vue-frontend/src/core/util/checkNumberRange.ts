export function checkNumberRange(value: number, range: {
    min?: number | null,
    max?: number | null,
    MIN?: number | null,
    MAX?: number | null
}) {
    const min = range.MIN || range.min
    const max = range.MAX || range.max
    return (!min || value >= min) || (!max || value <= max)
}
