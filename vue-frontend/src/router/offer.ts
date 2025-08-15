import {app} from "@/modules/app";
import {useStackRouteComponent} from "@/packages/stack-router/hooks/useStackRoute";

app.addRoutes([
    {
        parent: 'page',
        path: '/promotions',
        component: import("@/pages/info/offer/Offers.page.vue"),
        meta: {
            title: 'Акции'
        }
    },
    {
        parent: 'public',
        path: '/offer/:id',
        component: useStackRouteComponent(import("@/pages/info/offer/Offer.dialog.vue")),
        meta: {}
    },

])

