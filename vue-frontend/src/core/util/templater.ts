import deepGet from "lodash/get";

const symbolsSchema: Record<string, [string, string]> = {
    '{': ['\{', '\}'],
    '%': ['\%', '\%'],
    '[': ['\[', '\]'],
    '{{': ['\{\{', '\}\}'],
    '##': ['\#\#', '\#\#'],
}

export function templater(text?: string, vars?: any, valueWrapper?: any, symbol = '{') {

    const symbols = symbolsSchema[symbol]

    if (symbols) {
        let reg = new RegExp(symbols[0] + '(.+?)' + symbols[1], 'ig')
        return (text || '').replace(reg, function (str, find, offset) {
            if (find) {
                let val = deepGet(vars, find)
                if (typeof val === "function") {
                    val = val(val)
                }
                if (valueWrapper) {
                    val = valueWrapper(val as any)
                }
                return typeof val !== 'undefined' ? val as string : ''
            }
            return ''
        })
    }
}

export default templater

