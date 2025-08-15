import {UserAuthConfirmGenqlSelection} from "@/gql/gen";

export const UserAuthConfirmFields: UserAuthConfirmGenqlSelection = {
    CODE: true,
    COLOR: true,
    CONFIRM_CONTENT_MOBILE: true,
    CONFIRM_CONTENT_WEB: true,
    CONFIRM_STEPS: {
        __fragment: 'UserAuthConfirmStep'
    },
    ICON: true,
    LIST_BUTTON_MOBILE: {
        __fragment: 'MenuItemMobile'
    },
    LIST_BUTTON_WEB: {
        __fragment: 'MenuItem'
    },
    LIST_CAPTION: true,
    LIST_NAME: true,
    NAME: true,
    RESEND_TITLE: true
} 