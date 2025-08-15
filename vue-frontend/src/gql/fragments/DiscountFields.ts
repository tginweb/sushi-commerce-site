import {DiscountGenqlSelection} from "@/gql/gen";

export const DiscountFields: DiscountGenqlSelection = {
    ID: true,
    NAME: true,
    ACTIVE: true,
    CODE: true,
    PREVIEW_TEXT: true,
    PROPERTIES: {
       ACTION_DISCOUNT_PERCENT: true,
    },
    SORT: true,
}
