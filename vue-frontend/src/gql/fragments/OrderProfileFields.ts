import {OrderProfileGenqlSelection} from "@/gql/gen";

export const OrderProfileFields: OrderProfileGenqlSelection = {
    ID: true,
    NAME: true,
    PERSON_TYPE_ID: true,
    IS_DEFAULT: true,
    CAPTION: true,
    ATTR: {
        __fragment: 'OrderProfileAttributesValue'
    }
}
