import { app } from "@/modules/app";
import { useStackRouteComponent } from "@/packages/stack-router/hooks/useStackRoute";

app.addRoutes([
    {
        parent: 'public',
        path: '/news',
        component: () => import("@/pages/news/NewsList.page.vue"),
        meta: {
            title: 'Новости'
        }
    },
    {
        parent: 'public',
        path: '/news/:id',
        component: useStackRouteComponent(import("@/pages/news/NewsDetail.dialog.vue")),
        meta: {
            title: 'Новость'
        }
    }
])