import {OrderAttributesValue} from "@/gql/gen";

export function joinAttrsAddress(attrs: OrderAttributesValue, short = false) {

    const res = []

    if (attrs.ADDRESS) {
        let address = attrs.ADDRESS
        res.push(address)
    } else {
        if (attrs.STREET_PATH)
            res.push(attrs.STREET_PATH)
        if (attrs.HOUSE)
            res.push(attrs.HOUSE)
    }

    if (attrs.PRIVATE_HOUSE) {
        if (attrs.FLAT)
            res.push('кв. ' + attrs.FLAT)
    }

    return res.filter(item => {
        if (short) {
            if (item.match(/Иркутск$|Иркутская/i)) {
                return false
            }
        }
        return true
    }).join(', ')
}
