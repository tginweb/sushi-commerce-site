export default function parseMinutes(time: string): number {
    const [hours, minutes] = time.split(':').map(Number)
    if (typeof hours === 'number' && typeof minutes === 'number')
        return hours * 60 + minutes;
    return 0
}
