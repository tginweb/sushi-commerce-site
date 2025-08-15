import { app } from "@/modules/app";
import { useStackRouteComponent } from "@/packages/stack-router/hooks/useStackRoute";

app.addRoutes([
  {
    parent: 'public',
    path: '/auth',
    component: useStackRouteComponent(import('@/pages/auth/Auth.dialog.vue'))
  }
]); 