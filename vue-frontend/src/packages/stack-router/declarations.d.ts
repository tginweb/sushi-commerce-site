import {TRouteStackConfig} from "@/packages/stack-router/types";

declare module "vue-router" {
    interface RouteMeta {
        stack?: TRouteStackConfig
    }
}
