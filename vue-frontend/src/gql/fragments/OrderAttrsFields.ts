import {OrderAttributesValueGenqlSelection} from "@/gql/gen";

export const OrderAttrsFields: OrderAttributesValueGenqlSelection = {
    UUID: true,
    VORDER_ID: true,
    SOURCE: true,

    DETAILS: true,

    FIO: true,
    EMAIL: true,
    PHONE: true,

    BENEFIT_TYPE: true,
    BENEFIT_TYPE_STRING: true,

    PAYMENT_TYPE: true,
    PAYMENT_TYPE_STRING: true,
    CASH_SUM: true,
    BONUSES: true,

    PROFILE_ID: true,
    PROFILE_COMMENT: true,
    PROFILE_DEFAULT: true,

    TIME_MODE: true,
    DATE: true,
    TIME: true,
    DATETIME: true,

    DELIVERY_ID: true,
    PICKUP_DEPARTMENT: true,
    DELIVERY_DEPARTMENT: true,
    DELIVERY_PRICE: true,
    DEPARTMENT_SERVICE_ID: true,
    ADDRESS: true,
    ADDRESS_IS_CUSTOM: true,
    ADDRESS_SOURCE: true,

    CITY_FIAS_ID: true,
    CITY: true,

    STREET: true,
    STREET_PATH: true,
    STREET_FIAS_ID: true,
    STREET_COORDS: {
        __fragment: 'Coordinates'
    },

    PRIVATE_HOUSE: true,
    HOUSE: true,
    HOUSE_FIAS_ID: true,
    HOUSE_COORDS: {
        __fragment: 'Coordinates'
    },

    FLAT: true,
    FLOOR: true,
    INTERCOM: true,
    LIFT: true,

    DISCOUNT_PERCENT: true,
    DISCOUNT_REASON: true,

    NEED_CONFIRM: true,
    WITH_OPERATOR: true,

    PERSONS_NUMBER: true,

    PROMOCODE: true,

    SERVICE_SEND: true,
    SERVICE_SEND_START: true,

    SETTLEMENT: true,
    SETTLEMENT_FIAS_ID: true,

    USER_DESCRIPTION: true,
    COMMENT: true,
}
