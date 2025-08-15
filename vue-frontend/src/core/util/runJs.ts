export function runJs(code: string | any) {
    if (code) {
        if (typeof code === 'string') {
            eval(code)
        } else {
            code()
        }
    }
}
