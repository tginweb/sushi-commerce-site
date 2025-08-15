import {UserGenqlSelection} from "@/gql/gen";

export const UserFields: UserGenqlSelection = {
    "EMAIL": true,
    "FAMILY": {
        "__fragment": "UserFamily"
    },
    "GREETING_NAME": true,
    "GROUPS_INFO": {
        "__fragment": "UserGroup"
    },
    "ID": true,
    "LAST_NAME": true,
    "LOGIN": true,
    "LOGIN_FORMAT": true,
    "NAME": true,
    "NAME_FULL": true,
    "NAME_TEASER": true,
    "PERSONAL_BIRTHDAY": true,
    "PERSONAL_PHOTO": {
        "__fragment": "Image"
    },
    "PERSON_TYPE_ID": true,
    "PHONE": true,
    "PHONE_FORMATTED": true,
    "PROFILE_FILLED": true,
    "PROFILE_GIFT_USED": true,
    "PROMOCODE": true,
    "PROPS": {
        "__fragment": "EntityProp"
    },
    "ROLES": true,
    "SECOND_NAME": true,
    "SESSION_ID": true
}
