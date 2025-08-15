import {reactive} from 'vue';

export function reactiveConditional<T extends object = object>(data: T, condition: boolean) {
    return condition ? reactive(data) : data
}
