import {
    toValue,
    type MaybeRefOrGetter,
    type ComponentPublicInstance
} from 'vue';

type RawComposable<T> = {
    [K in keyof T]: T[K] extends (...args: any[]) => any ? never : T[K];
};

/**
 * Извлекает исходный объект из composable-объекта Vue
 * @param composable - Объект, созданный фабричной функцией
 * @returns Исходный объект без реактивных обёрток и методов
 */
export function extractRaw<T extends Record<string, any>>(
    composable: T
): RawComposable<T> {
    const raw: Record<string, any> = {};

    for (const key in composable) {
        // Пропускаем внутренние свойства Vue
        if (key.startsWith('__v_')) continue;

        const value = composable[key];

        // Пропускаем методы
        if (typeof value === 'function') continue;

        // Извлекаем значение из реактивных обёрток
        raw[key] = toValue(value);

        // Для объектов рекурсивно извлекаем исходные значения
        if (raw[key] !== null && typeof raw[key] === 'object') {
            raw[key] = extractObjectRaw(raw[key]);
        }
    }

    return raw as RawComposable<T>;
}

/**
 * Рекурсивно извлекает исходные значения из объектов
 */
function extractObjectRaw<T extends object>(obj: T): T {
    // Обрабатываем специальные объекты (Date, Set, Map и т.д.)
    if (obj instanceof Date
        || obj instanceof Set
        || obj instanceof Map
        || obj instanceof RegExp
        || obj instanceof WeakMap
        || obj instanceof WeakSet) {
        return obj;
    }

    // Для массивов
    if (Array.isArray(obj)) {
        return obj.map(item =>
            item && typeof item === 'object' ? extractObjectRaw(item) : item
        ) as unknown as T;
    }

    // Для обычных объектов
    const result: Record<string, any> = {};
    for (const key in obj) {
        let value = obj[key];

        // Извлекаем значение из реактивных обёрток
        value = toValue(value);

        // Рекурсивная обработка вложенных объектов
        result[key] = value && typeof value === 'object'
            ? extractObjectRaw(value)
            : value;
    }

    return result as T;
}
