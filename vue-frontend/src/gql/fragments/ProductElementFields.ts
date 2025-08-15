import {ProductElementGenqlSelection} from "@/gql/gen";

export const ProductElementFields: ProductElementGenqlSelection = {
    "ID": true,
    "NAME": true,
    "ACTIVE": true,
    "ACTIVE_FROM": true,
    "IBLOCK_CODE": true,
    "IBLOCK_ID": true,
    "IBLOCK_SECTION_ID": true,
    "IBLOCK_SECTION_IDS": true,
    "CODE": true,
    "CREATED": true,
    "DETAIL_IMAGE": {
        "__fragment": "Image"
    },
    "DETAIL_TEXT": true,
    "FLAGS": {
        "__fragment": "ProductFlag"
    },
    "IS_SALE_SPECIAL": true,
    "LIST_IMAGE": {
        "__fragment": "Image"
    },
    "MEASURE": {
        "__fragment": "ProductMeasure"
    },
    "PREVIEW_TEXT": true,
    "PRICE": {
        "__fragment": "ProductPrice"
    },
    "SORT": true,
    "TAGS": {
        ID: true,
        NAME: true,
        SORT: true,
        FEATURED: true
    },
    "URL": true,
    "WEIGHT": true,
    "BENEFITS": {
        PRODUCT_ID: true,
        QUANTITY: true,
        IS_GIFT: true
    },
    "SET_ITEMS": {
        PRODUCT_ID: true,
        QUANTITY: true,
    },
    "FLAG_ITEMS": {
        ID: true,
        CODE: true,
        NAME: true,
        NAME_SHORT: true,
        COLOR: true,
        CLASS: true,
        BG_COLOR_HEX: true,
        TEXT_COLOR_HEX: true,
        IMAGE: {
            SRC: true
        },
        ICON: true
    },
    "PROPERTIES": {
        __fragment: 'ProductElementPropsFields',
    }
}
