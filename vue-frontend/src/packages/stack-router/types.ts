import {TStackItemProps} from "./hooks/useStackItem";
import {RouteRecordNameGeneric} from "vue-router";
import {BottomSheetProps} from "@/components/BottomSheet/BottomSheet.vue";
import {DialogProps} from "@/components/Dialog/Dialog.vue";

export type TSheetProps = Partial<BottomSheetProps> & {}

export type TStackItemModalModes = 'dialog' | 'sheet'

export type TStackItemModalProps = TStackItemProps & {
    dialog?: DialogProps | undefined
    sheet?: TSheetProps | undefined
    breakpoint?: string | undefined
}

export type StackItemState = {
    aa?: string
}

export type StackItemModalState = StackItemState & {
    mode: TStackItemModalModes
}

export type TStackConfig = {
    routable?: boolean
    backComponent?: any
    backRedirect?: any
}

export type TStackProps = {
    namespace: string
}

export type TStackModalConfig = TStackConfig & {
    breakpoint?: string
}

export type TStackTabsConfig = TStackConfig & {
    tabsParams?: string
}

export type TStackModalProps = TStackProps & TStackModalConfig

export type TStackTabsProps = TStackProps & TStackTabsConfig

export type TPageProps = {
    title?: string
}

export type TStackItemRoute = {
    name?: RouteRecordNameGeneric | null
    path?: string
    initialRoute?: boolean
}

export type TRouteStackConfig = {
    enable?: boolean
    props?: any
    stackComponent?: any
    routeComponent?: any
    backComponent?: any
    backRedirect?: any
    component?: any
}

export type StackItemEmits = {
    show: [string],
    hide: [string]
}

export type RouteComponentMaybeStackable = any

export type StackRouterResolveResult = 'normal' | 'stackable_route' | 'stackable_stack' | null
