import App, {AppModule, AppModuleOptions, AppRoute, AppRouteContext} from "@/core/classes/App";
import {AppRouteMeta} from "@/core/app/types";
import Result from "@/core/classes/Result";
import {Config} from "@/config/type";

export class AppModuleBase<TAppRoute extends AppRoute = AppRoute> {
    declare app: App<TAppRoute>
    declare config: Config
    declare options: any

    constructor() {

    }

    routes?(routes: TAppRoute[]): void

    boot?(): void

    request?(): void

    store?(): void

    scopeQuery?(): void

    guardAccess?(to: any, from: any, result: Result, context: AppRouteContext): void | boolean | null

    routesPrebuild?(): void

    scopes?(): void

    widgets?(): void

    routerInit?(): void

    routerAfterEach?(to: string, from: string): void

    children?(options: AppModuleOptions, app: App<TAppRoute>): AppModule<TAppRoute>[]

    register?(options: AppModuleOptions, app: App<TAppRoute>): any

    defaultRouteMeta?(options: AppRouteMeta): void
}
