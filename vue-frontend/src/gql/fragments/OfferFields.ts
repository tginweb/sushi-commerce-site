import {OfferGenqlSelection} from "@/gql/gen";

export const OfferFields: OfferGenqlSelection = {
    ID: true,
    VID: true,
    ACTIVE_FROM: true,
    NAME: true,
    OFFER_NAME: true,
    DISCOUNT_ID: true,
    CODE: true,
    DETAIL_TEXT: true,
    PREVIEW_TEXT: true,
    BANNER_INTERNAL_TEXT: true,
    BANNER_SQUARE: {
        SRC: true
    },
    BANNER_HOR_MOBILE: {
        SRC: true
    },
    BANNER_HOR_DESKTOP: {
        SRC: true
    },
    VARS: true,
    ACTIONS_WEB: {
        __fragment: 'MenuItem'
    },
    IS_HOT: true,
    STARTUP_SHOW: true
}
