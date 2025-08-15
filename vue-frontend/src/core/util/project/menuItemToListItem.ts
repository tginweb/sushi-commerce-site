import {MenuItem} from "@/gql/gen";
import {QBtnProps} from "quasar";
import {getTypedEntries} from "@/core/util/getTypedEntries";
import {parseActionPath} from "@/core/util/project/parseActionPath";

const map: {
    [key in keyof MenuItem]?: keyof QBtnProps
} = {
    color: 'color',
}

export function menuItemToListItem(item?: Partial<MenuItem> | null, defaultProps: QBtnProps | null = {}, defaultTo?: string | any) {
    if (!item)
        return {}
    const props: QBtnProps = defaultProps || {}

    for (const [itemProp, btnProp] of getTypedEntries(map)) {
        if (btnProp && (typeof item[itemProp] !== 'undefined') && (item[itemProp] !== null)) {
            props[btnProp] = item[itemProp]
        }
    }
    if (item.url) {
        const action = parseActionPath(item.url)
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
