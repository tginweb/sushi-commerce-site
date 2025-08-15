import {MenuItemGenqlSelection} from "@/gql/gen";

export const MenuItemFields: MenuItemGenqlSelection = {
    badge: true,
    bgColor: true,
    blank: true,
    color: true,
    icon: true,
    id: true,
    label: true,
    outline: true,
    url: true,
    children: {
        badge: true,
        bgColor: true,
        blank: true,
        color: true,
        icon: true,
        id: true,
        label: true,
        outline: true,
        url: true
    }
}
