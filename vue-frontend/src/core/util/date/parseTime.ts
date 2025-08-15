const dayjs = require('dayjs')

export default function parseTime(val: any, formatInput: 'datetime' | 'date' | null, formatOutput: 'ts_unix' | 'ts_js' | 'dayjs' | 'jsdate' = 'ts_js') {

    if (!val)
        return;

    let _formatInput = ''

    if (!formatInput) {
        formatInput = 'datetime'
        if (typeof val === 'string') {
            if (val.trim().match(/\d\d\.\d\d\.\d\d/)) {
                formatInput = 'date'
            }
        }
    }

    switch (formatInput) {
        case 'datetime':
            _formatInput = 'DD.MM.YYYY HH:mm'
            break
        case 'date':
            _formatInput = 'DD.MM.YYYY'
            break
        default:
            _formatInput = 'DD.MM.YYYY HH:mm'
            break
    }

    let t

    if (typeof val === 'string') {
        t = dayjs(val, _formatInput, true)
    } else if (typeof val === 'number') {
        if (val < 2147483647) val = val * 1000
        t = dayjs(val)
    } else if (val instanceof Date) {
        t = dayjs(val)
    } else if (typeof val === 'object' && val.format) {
        t = val
    } else {
        return;
    }

    let res

    switch (formatOutput) {
        case 'ts_unix':
            res = t.unix()
            res = !isNaN(res) ? res : null
            break
        case 'ts_js':
            res = t.valueOf()
            res = !isNaN(res) ? res : null
            break
        case 'dayjs':
            res = t
            break
        case 'jsdate':
            res = t.toDate()
            break
        default:
            res = t.format(formatOutput)
    }

    return res
}

