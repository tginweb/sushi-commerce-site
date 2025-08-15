export interface ScheduleItem {
    day: number;
    hours: string;
}

export interface GroupedSchedule {
    start: number;
    end: number;
    hours: string;
}

const DAYS_MAP: Record<number, string> = {
    1: 'пн',
    2: 'вт',
    3: 'ср',
    4: 'чт',
    5: 'пт',
    6: 'сб',
    7: 'вс',
};

export default function formatSchedule(input: ScheduleItem[]): string[] {
    if (input.length === 0) return [];

    // Сортируем дни по порядку
    const sorted = [...input].sort((a: ScheduleItem, b: ScheduleItem) => a.day - b.day);

    const result: GroupedSchedule[] = [];

    if (!sorted[0])
        return []

    let current: GroupedSchedule = {
        start: sorted[0].day,
        end: sorted[0].day,
        hours: sorted[0].hours,
    };

    for (let i = 1; i < sorted.length; i++) {
        const next = sorted[i]
        if (next) {
            if (next.day === current.end + 1 && next.hours === current.hours) {
                current.end = next.day;
            } else {
                result.push(current);
                current = {
                    start: next.day,
                    end: next.day,
                    hours: next.hours,
                };
            }
        }
    }

    result.push(current);

    // Форматируем результат в строки
    return result.map(({start, end, hours}) => {
        const startDay = DAYS_MAP[start];
        const endDay = DAYS_MAP[end];
        const range = start === end ? startDay : `${startDay}-${endDay}`;
        return `${range} ${hours}`;
    })
}
