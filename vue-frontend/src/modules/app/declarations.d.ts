import {RouteLocationRaw} from "vue-router";

declare module "@/core/app/types" {
    interface AppRouteMeta {
        auth?: boolean
        keepAlive?: boolean
        title?: string
        description?: string
        mobileCatalogHide?: boolean
        layoutContentWide?: boolean
        breadcrumb?: {
            to?: string
            title?: string
        }
    }
}

declare module "@/core/app/types" {
    interface BusEvents {
        'router:push': (to: RouteLocationRaw) => void,
        'router:replace': (to: RouteLocationRaw) => void,
    }
}


