export function debounceNamed(func: any, timeout = 300) {
    let timers: any = {}

    return async (name: string, ...args: any) => {
        clearTimeout(timers[name])
        timers[name] = setTimeout(() => {
            // @ts-ignore
            func.apply(this, args);
        }, timeout)
        setTimeout(() => {
            // delete timers[name]
        }, timeout * 2)
    }
}
