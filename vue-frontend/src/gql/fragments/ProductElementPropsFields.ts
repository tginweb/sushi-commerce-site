import {ProductElementPropsGenqlSelection} from "@/gql/gen";

export const ProductElementPropsFields: ProductElementPropsGenqlSelection = {
    SALE_SPECIAL: true,
    KKAL: true,
    BELKI: true,
    ZHIRY: true,
    UGLEVODY: true,
    WEIGHT: true,
    AVAILABLE_SCHEDULE: {
        DAY: {
            VALUE: true,
            CODE: true
        },
        FROM: true,
        TO: true
    }
}
