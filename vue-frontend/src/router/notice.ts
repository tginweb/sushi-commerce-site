import {app} from "@/modules/app";
import {useStackRouteComponent} from "@/packages/stack-router/hooks/useStackRoute";

app.addRoutes([
    {
        parent: 'public',
        path: '/notice/show/:id',
        component: useStackRouteComponent(import("@/pages/notice/Notice.dialog.vue")),
        meta: {}
    },
    {
        parent: 'personal',
        path: '/personal/notices',
        component: useStackRouteComponent(import("@/pages/notice/Notices.dialog.vue")),
        meta: {}
    },
])

