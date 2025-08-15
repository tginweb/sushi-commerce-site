import {app} from "@/modules/app";
import {useStackRouteComponent} from "@/packages/stack-router/hooks/useStackRoute";

app.addRoutes([
    {
        parent: 'page',
        path: '/reviews',
        component: () => import("@/pages/info/review/Reviews.page.vue"),
        meta: {
            title: 'Отзывы'
        }
    },
    {
        parent: 'page',
        path: '/review/make',
        component: useStackRouteComponent(import("@/pages/info/review/ReviewMake.dialog.vue")),
    }
])

