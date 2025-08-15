import {MenuGenqlSelection} from "@/gql/gen";

export const MenuFields: MenuGenqlSelection = {
    code: true,
    id: true,
    children: {
        __fragment: 'MenuItemFields'
    }
}
