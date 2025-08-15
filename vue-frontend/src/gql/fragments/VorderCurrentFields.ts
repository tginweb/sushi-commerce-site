import {VorderCurrentGenqlSelection} from "@/gql/gen";

export const VorderCurrentFields: VorderCurrentGenqlSelection = {
    ID: true,
    BONUSES_AVAILABLE: true,
    BONUSES_PERCENT: true,
    DISCOUNTS: {
        __fragment: 'DiscountFields',
    },
    DELIVERIES: {
        ID: true,
        NAME: true,
        PRICE: true,
        SERVICE: {
            ID: true,
            NAME: true,
            TRANSPORT_TYPE: true
        }
    },
    PAYMENT_TYPES: {
        CODE: true,
        NAME: true
    },
    ATTR: {
        __fragment: 'OrderAttrsFields',
    },
    ORDER: {
        PRICE: true,
        BONUSES: true,
        COUPONS: {
            NAME: true,
            COUPON: true
        }
    },
    BASKET: {
        ITEMS: {
            PRODUCT_ID: true,
            QUANTITY: true,
            NAME: true,
            PROPS: {
                CODE: true,
                VALUE: true
            },
            PRICE: true,
            PRICE_BASE: true,
            FINAL_PRICE: true,
            FINAL_PRICE_BASE: true
        },
        PRICE: true,
        BASE_PRICE: true,
    },
}
