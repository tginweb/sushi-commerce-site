import {SaleClientCardGenqlSelection} from "@/gql/gen";

export const ClientCardFields: SaleClientCardGenqlSelection = {
    BONUSES: true,
    BONUSES_EXPIRE: true,
    BONUSES_PERCENT: true,
    CLIENT_PHONE: true,
    EXPIRED: true,
    FETCHED: true,
    FETCHED_ACTUAL: true,
    ID: true,
    LEVEL: {
        ID: true,
        NAME: true,
        CODE: true,
    },
    MONTH_SPENT: true,
    DISCOUNTS: {
        __fragment: "DiscountFields",
    },
};
