import {toRaw} from "vue";

export default function deepToRaw(obj: any): any {
    if (typeof obj !== 'object' || obj === null) return obj;
    const raw = toRaw(obj);
    return Object.fromEntries(
        Object.entries(raw).map(([key, val]) => [key, deepToRaw(val)])
    );
};
