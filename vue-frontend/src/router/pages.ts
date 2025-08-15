import { app } from "@/modules/app";
import { useStackRouteComponent } from "@/packages/stack-router/hooks/useStackRoute";

app.addRoutes([
  {
    parent: "page",
    path: "/:pageUrl(pay)",
    component: () => import("@/pages/page/Page.page.vue"),
    meta: {},
    props: true,
  },
  {
    parent: "public",
    path: "/contacts/",
    component: useStackRouteComponent(import("@/pages/Contacts.dialog.vue")),
    meta: {
      title: "Контакты",
    },
    props: true,
  },
  {
    parent: "page",
    path: "/delivery/",
    component: () =>
      import("@/pages/info/delivery-zones/DeliveryZones.page.vue"),
    meta: {
      title: "Доставка и самовывоз",
    },
    props: true,
  },
  {
    parent: "public",
    path: "/office/:id",
    component: useStackRouteComponent(
      import("@/pages/info/delivery/Office.dialog.vue")
    ),
  },
  {
    parent: "page",
    path: "/bonus/",
    component: () => import("@/pages/info/bonus/Bonus.page.vue"),
    meta: {
      title: "Бонусная система",
      layoutContentWide: true,
    },
    props: true,
  },
  {
    parent: "page",
    path: "/camera/",
    component: () => import("@/pages/info/camera/Camera.page.vue"),
    meta: {
      title: "Камеры",
    },
    props: true,
  },

  {
    parent: "page",
    path: "/delivery-zones/",
    component: () =>
      import("@/pages/info/delivery-zones/DeliveryZones.page.vue"),
    meta: {
      title: "Зоны доставки",
    },
    props: true,
  },
  {
    parent: "page",
    path: "/vacancies/",
    component: () => import("@/pages/info/vacancy/Vacancy.page.vue"),
    meta: {
      title: "Вакансии",
    },
    props: true,
  },
  {
    parent: "page",
    path: "/vacancies-ssr/",
    component: () => import("@/pages/info/vacancy/VacancySsr.page.vue"),
    meta: {
      title: "Вакансии (SSR)",
    },
    props: true,
  },
  {
    parent: "public",
    path: "/vacancy/:id",
    component: useStackRouteComponent(
      import("@/pages/info/vacancy/Vacancy.dialog.vue")
    ),
  },
  {
    parent: "page",
    path: "/faq/",
    component: () => import("@/pages/info/faq/Faq.page.vue"),
    meta: {
      title: "Вопросы и ответы",
    },
    props: true,
  },
]);
