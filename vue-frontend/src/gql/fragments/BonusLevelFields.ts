import { BonusLevelElementGenqlSelection } from "@/gql/gen";

export const BonusLevelFields: BonusLevelElementGenqlSelection = {
    ID: true,
    NAME: true,
    ACTIVE: true,
    PROPERTIES: {
        ACCUMULATION_PERCENT: true,
        MAX_USE_PERCENT: true,
        ORDERS_SUMM: true,
        COLOR: true
    },
    SORT: true,
}
