import {app} from "@/modules/app";

app.addRoutes([
    {
        name: 'front',
        parent: 'public',
        path: '',
        components: {
            default: () => import("@/pages/front/Front.page.vue"),
            top: () => import("@/pages/front/Offers.vue"),
        },
        meta: {}
    },
])

