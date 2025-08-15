import {DeliveryZoneElementGenqlSelection} from "@/gql/gen";

export const DeliveryZoneFields: DeliveryZoneElementGenqlSelection = {
    ID: true,
    NAME: true,
    ACTIVE: true,
    CODE: true,
    PREVIEW_TEXT: true,
    PROPERTIES: {
        COLOR: true,
        FREE_FROM_PRICE: true,
        FREE_TEXT: true,
        GEOJSON: true,
        OUTSIDE: true,
        PRICE_BY_DELIVERY: true,
        PRICE_BY_KM: true,
        PRICE_TEXT: true,
    },
    SORT: true,
} 