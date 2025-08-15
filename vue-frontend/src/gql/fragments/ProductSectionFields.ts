import {ProductSectionGenqlSelection} from "@/gql/gen";

export const ProductSectionFields: ProductSectionGenqlSelection = {
    ID: true,
    NAME: true,
    CODE: true,
    URL: true,
    ELEMENT_CNT: true,
    REPLACE_LINK: true,
    IBLOCK_SECTION_ID: true,
    PICTURE: {
        SRC: true
    },
    PROPERTIES: {
        __fragment: 'ProductSectionPropsFields',
    }
}
