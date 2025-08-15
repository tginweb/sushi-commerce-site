import {defineStore} from "pinia";

const STORE_NAME = 'shop-currency'

export const useCurrency = defineStore(STORE_NAME, () => {

    const applyPricePercent = (price: number, percent: number) => {
        return percent ? (price - (price / 100) * percent) : price
    }

    const roundPrice = (value: number | null, round?: number | boolean | null | undefined) => {
        if (!value)
            return 0
        if (round) {
            return Math.round(value)
        }
        return value
    }

    const format = (value: number | null, round?: number | boolean | null | undefined, space: boolean | string | number = 1, symbol: boolean | string = true) => {
        if (value === null)
            return null
        let result = roundPrice(value, round).toString()
        if (space) {
            let _space = ''
            if (typeof space === 'boolean') {
                _space = ' '
            } else if (typeof space === 'number') {
                _space = space > 1 ? '&nbsp;'.repeat(space) : ' '
            } else if (typeof space === 'string') {
                _space = _space
            }
            result += _space
        }
        if (symbol)
            result += typeof space !== 'string' ? '₽' : space
        return result
    }

    return {
        roundPrice,
        format,
        formatPrice: format,
        applyPricePercent
    }
})

