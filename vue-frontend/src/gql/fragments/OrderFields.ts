import {OrderGenqlSelection} from "@/gql/gen";

export const OrderFields: OrderGenqlSelection = {
    ID: true,
    ACCOUNT_NUMBER: true,
    DATE_INSERT: true,
    DATE_FORMATTED: true,
    CSTATUS_NAME: true,
    CSTATUS_ID: true,
    CSTATUS_COLOR: true,
    DELIVERY_ID: true,

    PRICE: true,
    PRICE_PAY: true,
    PRICE_PAY_BASE: true,

    IS_PAID: true,
    IS_ACTIVE: true,
    IS_FINISHED: true,
    IS_CANCELED: true,
    IS_CAN_PAY: true,
    IS_CAN_PAY_ONLINE: true,
    IS_CAN_CANCEL: true,
    BASKET: {
        ID: true,
        PRODUCT_ID: true,
        NAME: true,
        QUANTITY: true,
        PRICE: true,
        FINAL_PRICE: true,
        PRICE_BASE: true,
        FINAL_PRICE_BASE: true,
        DESC: true
    },
    ATTR: {
        __fragment: 'OrderAttrsFields'
    }
}
