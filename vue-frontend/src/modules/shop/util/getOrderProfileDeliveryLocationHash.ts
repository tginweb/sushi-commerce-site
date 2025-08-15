import {OrderProfile} from "@/gql/gen";

export function getOrderProfileDeliveryLocationHash(
    profile: OrderProfile,
    basketPriceCategory?: number
) {

    const res: (string | number)[] = [
        'courier'
    ]

    const attrs = profile.ATTR

    if (!attrs.ADDRESS)
        return null;

    res.push(attrs.ADDRESS)

    if (basketPriceCategory)
        res.push(basketPriceCategory)

    return res.join('-')
}
