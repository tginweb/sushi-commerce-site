import {PageGenqlSelection} from "@/gql/gen";

export const PageFields: PageGenqlSelection = {
    "ID": true,
    "NAME": true,
    "CODE": true,
    "CREATED": true,
    "SORT": true,
    "URL": true,
    "DETAIL_IMAGE": {
        "__fragment": "Image"
    },
    "DETAIL_TEXT": true,
    "IBLOCK_CODE": true,
    "IBLOCK_ID": true,
    "LIST_IMAGE": {
        "__fragment": "Image"
    },
    "META": {
        "__fragment": "ElementMeta"
    },
    "PREVIEW_TEXT": true,
    "PROPERTIES": {
        "META_DESCRIPTION": true,
        "META_KEYWORDS": true,
        "META_TITLE": true,
        "TPL_PAGE_CONTENT": true,
        "TPL_PAGE_VIEW": true
    },
}
