// utils.ts
import {inject, InjectionKey} from 'vue';

export function injection<T>(key: InjectionKey<T>, fallback?: T) {
    const resolved = inject(key, fallback);
    if (!resolved) {
        throw new Error(`Не удалось получить значение для ключа ${key.description}`);
    }
    return resolved;
}
