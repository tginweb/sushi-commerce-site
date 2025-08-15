import { ElementGenqlSelection } from "@/gql/gen";

export const CertificateFields: ElementGenqlSelection = {
    ID: true,
    NAME: true,
    CODE: true,
    ACTIVE: true,
    SORT: true,
    DATE_CREATE: true,
    DATE_MODIFY: true,
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
        ISSUE_DATE: true,
        EXPIRY_DATE: true,
        ISSUING_AUTHORITY: true,
        CERTIFICATE_NUMBER: true,
        CERTIFICATE_FILE: {
            src: true,
            name: true,
            size: true
        }
    },
    IS_VALID: true,
    IS_EXPIRING_SOON: true,
    STATUS_TEXT: true,
    URL: true
}