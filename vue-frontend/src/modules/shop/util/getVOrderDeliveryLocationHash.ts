import {OrderAttributesValue} from "@/gql/gen";

export function getVOrderDeliveryLocationHash(
    attrs: OrderAttributesValue,
    basketPriceCategory?: number
) {

    const res: (string | number)[] = [
        attrs.TRANSPORT_TYPE || 'courier',
        attrs.DELIVERY_ID || 0
    ]

    if (attrs.TRANSPORT_TYPE === 'courier') {
        if (!attrs.ADDRESS)
            return null
        res.push(attrs.ADDRESS)
    } else {
        if (!attrs.PICKUP_DEPARTMENT)
            return null
        res.push(attrs.PICKUP_DEPARTMENT)
    }

    if (basketPriceCategory)
        res.push(basketPriceCategory)

    return res.join('-')
}
