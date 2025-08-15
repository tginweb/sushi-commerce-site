import {CompanyOfficeGenqlSelection} from "@/gql/gen";

export const OfficeFields: CompanyOfficeGenqlSelection = {
    ID: true,
    NAME: true,
    ACTIVE: true,
    PROPERTIES: {
        COORDINATES: {
            __fragment: 'Coordinates'
        },
        ADDRESS: true,
        PHONES: true,
        GIS_URL: true,
        ROLES: {
            __fragment: 'ListValue'
        },
        WORKTIME: true,
    },
    SORT: true,
}
