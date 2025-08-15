import {app} from "@/modules/app";
import {useStackRouteComponent} from "@/packages/stack-router/hooks/useStackRoute";

app.addRoutes([

    {
        parent: "personal",
        path: "/personal/dashboard",
        component: () => import("../pages/personal/Dashboard.page.vue"),
        meta: {
            title: "Личный кабинет",
        },
    },

    {
        parent: "personal",
        path: "/personal/orders",
        component: () => import("../pages/personal/orders/Orders.layout.vue"),
        meta: {
            title: "Заказы",
            breadcrumb: {}
        },
        children: [
            {
                path: "",
                component: () => import("../pages/personal/orders/Orders.page.vue"),
                meta: {
                    title: "Заказы",
                },
            },
            {
                path: "/personal/orders/active",
                component: () => import("../pages/personal/orders/OrdersActive.page.vue"),
                meta: {
                    title: "Активные заказы",
                },
            },
            {
                path: "/personal/orders/history",
                component: () => import("../pages/personal/orders/OrdersHistory.page.vue"),
                meta: {
                    title: "История заказов",
                },
            },
            {
                path: "/personal/order/:id/cancel",
                component: useStackRouteComponent(import("@/pages/personal/order/OrderCancel.dialog.vue")),
                meta: {
                    title: "Отменить заказ",
                },
            },
        ],
    },
    {
        parent: "personal",
        path: "/personal/favorites",
        component: () => import("../pages/personal/favorites/Favorites.page.vue"),
        meta: {
            title: "Избранное",
            breadcrumb: {}
        },
    },
    {
        parent: "personal",
        path: "/personal/bonus",
        component: () => import("../pages/personal/bonus/Bonus.page.vue"),
        meta: {
            title: "Бонусы",
            breadcrumb: {}
        },
    },
    {
        parent: "public",
        path: "/personal/order-profiles",
        component: useStackRouteComponent(
            import("../pages/personal/order-profiles/OrderProfiles.dialog.vue"),
            {
                // routeComponent: import("../pages/order-profiles/OrderProfiles.page.vue")
            }
        ),
        meta: {},
    },

    {
        parent: "personal",
        path: "/personal/menu",
        component: useStackRouteComponent(
            import("../pages/personal/ProfileMenu.dialog.vue")
        ),
        meta: {},
    },
    {
        parent: "public",
        path: "/personal/logout",
        component: useStackRouteComponent(import("../pages/Logout.dialog.vue")),
        meta: {},
    },
]);
