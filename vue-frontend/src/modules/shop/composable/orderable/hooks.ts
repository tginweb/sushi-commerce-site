import {OrderAttributesValue, OrderProfileAttributesValue} from "@/gql/gen";

type Attrs = OrderAttributesValue | OrderProfileAttributesValue

export function getOrderAttrsCoords(attrs: Attrs) {
    if (attrs.HOUSE_COORDS && attrs.HOUSE_COORDS.LAT && attrs.HOUSE_COORDS.LON) {
        return [attrs.HOUSE_COORDS.LAT, attrs.HOUSE_COORDS.LON]
    }
}

export function getOrderAttrsAddress(attrs: Attrs) {
    return attrs.ADDRESS
}
