import {app} from "@/modules/app";

app.addRoutes([
    {
        name: 'public',
        path: '/',
        component: () => import('@/layouts/MainLayout.vue'),
        meta: {
        },
    },
    {
        name: 'page',
        parent: 'public',
        path: '/page',
        component: () => import('@/layouts/PageLayout.vue'),
        meta: {},
        children: []
    },
    {
        name: 'personal',
        parent: 'public',
        path: '/personal',
        component: () => import('@/layouts/PersonalLayout.vue'),
        meta: {
            auth: true,
            title: 'Личный кабинет',
            breadcrumb: {
                to: '/personal/dashboard'
            }
        },
        children: []
    },
    {
        path: '/:catchAll(.*)*',
        component: () => import('@/pages/ErrorNotFound.vue'),
    },
])

