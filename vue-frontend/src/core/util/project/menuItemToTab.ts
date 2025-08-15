import {MenuItem} from "@/gql/gen";
import {QRouteTabProps} from "quasar";
import {getTypedEntries} from "@/core/util/getTypedEntries";
import {parseActionPath} from "@/core/util/project/parseActionPath";
import {resolveIcon} from "@/assets/icons";

const map: {
    [key in keyof MenuItem]?: keyof QRouteTabProps
} = {
    label: 'label'
}

export function menuItemToTab(item?: Partial<MenuItem> | null, defaultProps: QRouteTabProps | null = {}, defaultTo?: string | any) {

    if (!item)
        return {}
    const props: QRouteTabProps = defaultProps || {}

    for (const [itemProp, btnProp] of getTypedEntries(map)) {
        if (btnProp && (typeof item[itemProp] !== 'undefined') && (item[itemProp] !== null)) {
            props[btnProp] = item[itemProp]
        }
    }

    if (item.icon) {
        props.icon = resolveIcon(item.icon)
    }

    if (item.url) {
        const action = parseActionPath(item.url, item)


        //console.log('action', action)
        if (action) {
            if (action.type === 'location') {
                props.href = action.path
                props.target = action.blank ? '_blank' : undefined
            } else if (action.type === 'router') {
                const to = defaultTo || {}
                props.to = {
                    ...to,
                    path: action.path
                }
            } else if (action.type === 'store') {
                props.onClick = () => {
                    //storeResolve(action)
                }
            }
        }
    }
    // console.log('props', props)
    return props
}
