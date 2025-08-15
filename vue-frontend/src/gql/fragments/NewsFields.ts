import { ElementGenqlSelection } from "@/gql/gen";

export const NewsFields: ElementGenqlSelection = {
    ID: true,
    NAME: true,
    CODE: true,
    ACTIVE: true,
    SORT: true,
    DATE_CREATE: true,
    DATE_MODIFY: true,
    ACTIVE_FROM: true,
    PREVIEW_TEXT: true,
    DETAIL_TEXT: true,
    PREVIEW_PICTURE: {
        src: true,
        alt: true,
        width: true,
        height: true
    },
    DETAIL_PICTURE: {
        src: true,
        alt: true,
        width: true,
        height: true
    },
    PROPERTIES: {
        UNPUBLISH_DATE: true,
        IS_FEATURED: true
    },
    IS_PUBLISHED: true,
    URL: true
}