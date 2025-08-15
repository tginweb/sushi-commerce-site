import {AppModuleBase} from "@/core/classes/AppModuleBase";
import Result from "@/core/classes/Result";
import {AppRouteMeta} from "@/core/app/types";
import {StoreConfig} from "@/core/types";

export default class App<TAppRoute extends AppRoute = AppRoute> {

    public booted: boolean = false

    public scopes: AppScope[] = []
    public scaned: boolean = false
    public data: any = {}
    public modules: AppModule<TAppRoute>[] = []

    public storeDefinitions: StoreConfig[] = []

    public modulesCallbacks: Record<AppModuleHookName, any> = {
        boot: [],
        request: [],
        store: [],
        scopeQuery: [],
        guardAccess: [],
        routes: [],
        routesPrebuild: [],
        scopes: [],
        widgets: [],
        routerInit: [],
        routerAfterEach: [],
        routerBeforeEach: [],
        register: [],
        children: [],
        defaultRouteMeta: []
    }

    public defaultRouteMeta: AppRouteMeta | null = null

    public routes: TAppRoute[] = []
    public routesBuildedMap: Record<string, TAppRoute> = {}
    public storeModulesNames: string[] = []
    public hooks: Record<string, any> = []
    public appScope?: string

    constructor(appScope: string) {
        this.appScope = appScope
    }

    setAppScope(scope: string) {
        this.appScope = scope
    }

    inAppScope(scope: string) {
        return this.appScope === scope
    }

    registerClassModule(module: any) {
        this.modules.push(module)
    }

    registerModule(module: AppModule<TAppRoute>) {
        this.modules.push(module)
    }

    registerModules(modules: AppModule<TAppRoute>[]) {
        for (const module of modules) {
            this.modules.push(module)
        }
    }

    getObjectKeys(obj: any) {
        const methods: any = {}
        Object.getOwnPropertyNames(Object.getPrototypeOf(obj)).forEach(methodName => {
            methods[methodName] = obj[methodName]
        })
        Object.keys(obj).forEach(key => methods[key] = key)
        return Object.keys(methods)
    }

    scanModules() {

        const hookNames: string[] = Object.keys(this.modulesCallbacks)

        const scan = (tree: AppModule<TAppRoute>[]) => {

            tree.forEach((module) => {

                const moduleKeys = this.getObjectKeys(module) as AppModuleHookName[]

                moduleKeys.forEach((key) => {
                    if (hookNames.indexOf(key) > -1) {
                        this.modulesCallbacks[key].push(module)
                    }
                })

                if (module.children) {
                    const children = module.children()
                    scan(children)
                }
            })
        }

        scan(this.modules)

        this.scaned = true
    }

    getHookModules(name: AppModuleHookName) {

        if (!this.scaned)
            this.scanModules()

        return this.modulesCallbacks[name]
    }

    applyFilters(name: AppModuleHookName, val: any, ...args: any[]) {

        const modules = this.getHookModules(name)

        for (const module of modules) {
            val = module(...[val, ...args])
        }

        if (this.hooks[name]) {
            for (const hook of this.hooks[name]) {
                val = hook(...[val, ...args])
            }
        }

        return val
    }

    runCallbacks(name: AppModuleHookName, ...args: any[]) {
        const callbacks = this.getHookModules(name)
        for (const module of callbacks) {
            module[name](...args)
        }
        return args[0]
    }

    async runGuardsAsync(name: AppModuleHookName, ...args: any[]) {
        const callbacks = this.getHookModules(name)
        for (const module of callbacks) {
            const res = await module[name](...args)
            if (res === false)
                return false
        }
        return args[0]
    }

    async runCallbacksAsync(name: AppModuleHookName, ...args: any[]) {
        const callbacks = this.getHookModules(name)
        for (const module of callbacks) {
            await module[name](...args)
        }
        return args[0]
    }

    onBoot() {
        if (!this.booted) {
            this.runCallbacks('boot')
            this.booted = true
        }
    }

    async onRequest(ctx: any) {
        await this.runCallbacksAsync('request', ctx)
    }

    getStoreModules() {
        const ctx = {}
        this.runCallbacks('store', ctx)
        this.storeModulesNames = Object.keys(ctx)
        return ctx
    }

    getStoreModuleNames() {
        return this.storeModulesNames
    }

    async testRouteAccess(to: any, from: any, result: Result, context: AppRouteContext): Promise<boolean | void> {
        return await this.runGuardsAsync('guardAccess', to, from, result, context)
    }

    scopeQuery(query: any, scope: string) {
        return this.applyFilters('scopeQuery', query, scope)
    }

    getDefaultRouteMeta() {
        if (!this.defaultRouteMeta) {
            this.defaultRouteMeta = {} as AppRouteMeta
            this.runCallbacks('defaultRouteMeta', this.defaultRouteMeta)
        }
        return this.defaultRouteMeta
    }

    buildRoutes() {

        this.runCallbacks('routes', this.routes)

        let mapByName: Record<string, any> = {}

        for (let route of this.routes) {
            if (route.name) {
                mapByName[route.name] = route
            }
        }

        this.runCallbacks('routesPrebuild', this.routes, mapByName)

        for (let route of this.routes) {
            if (route.name && !mapByName[route.name]) {
                mapByName[route.name] = route
            }
        }

        const res = []
        const ctx: AppRouteBuildContext<TAppRoute> = {
            parent: 'public'
        }

        for (let route of this.routes) {

            if (route.disable) {
                continue;
            }

            if (route.parent) {

                const parentName = route.parent
                const parentRoute = mapByName[parentName]

                if (parentRoute) {

                    if (route.path === '') {
                        // parentRoute.name = ''
                    }

                    //ctx.parent = parentRoute.path.replace(/\/+$/, '');

                    this.buildRoute(route, ctx)

                    if (!parentRoute.children)
                        parentRoute.children = []

                    parentRoute.children.push(route)
                }
            } else {
                this.buildRoute(route, ctx)
                res.push(route)
            }
        }
        return res
    }

    buildRoute(route: TAppRoute, ctx: AppRouteBuildContext<TAppRoute>) {
        if (route.path) {
            route.path = route.path.replace(
                /{(\w*)}/g,
                //@ts-ignore
                (m, key: keyof AppRouteBuildContext<TAppRoute>) => {
                    return ctx[key]
                }
            );
        }
        if (route.name)
            this.routesBuildedMap[route.name] = route
    }

    getRouteByName(routeName: string, field: keyof TAppRoute) {
        const route = this.routesBuildedMap[routeName]
        if (route) {
            return field ? route[field] : route
        }
    }

    getRoutePath(routeName: string, replaces: any = {}) {
        const route = this.routesBuildedMap[routeName]
        if (route) {
            const regex = new RegExp(Object.keys(replaces).join('|'), "g");
            return route.path ? route.path.replace(regex, (key) => {
                return replaces[key]
            }) : null
        }
    }

    addFilter(name: string, cb: (...args: any[]) => any) {
        this.hooks[name] = this.hooks[name] || []
        this.hooks[name].push(cb)
    }

    isDev() {
        return process.env.DEV
    }

    addRoutes(routes: TAppRoute[]) {
        this.routes.push(...routes)
    }

}

export type IconsMap = Record<any, any>

export interface AppModule<TAppRoute extends AppRoute = AppRoute> {
    routes?: (routes: TAppRoute[]) => void
    boot?: () => void
    request?: () => void
    store?: () => void
    scopeQuery?: () => void
    guardAccess?: () => void
    routesPrebuild?: () => void
    scopes?: () => void
    widgets?: () => void
    routerInit?: () => void
    routerAfterEach?: (to: string, from: string) => void
    routerBeforeEach?: (to: string, from: string) => void
    children?: () => AppModule<TAppRoute>[]
    register?: (options: AppModuleOptions, app: App<TAppRoute>) => any

    reserved?: () => void
    defaultRouteMeta: (options: AppRouteMeta) => void
}

export type AppModuleOptions = Record<string, any>

export type AppModuleRegisterInfo<TAppRoute extends AppRoute = AppRoute> =
    AppModule<TAppRoute>
    | AppModuleBase<TAppRoute>
    | [AppModule<TAppRoute>, AppModuleOptions]

export type AppModuleHookName = 'boot' |
    'request' |
    'store' |
    'scopeQuery' |
    'guardAccess' |
    'routes' |
    'routesPrebuild' |
    'scopes' |
    'widgets' |
    'routerInit' |
    'routerAfterEach' |
    'routerBeforeEach' |
    'register' |
    'children' |
    'defaultRouteMeta'

export type AppRoute = {
    name?: string
    disable?: boolean
    parent?: AppRouterScope
    path?: string;
    redirect?: any;
    component?: any;
    components?: any;
    children?: AppRoute[];
    meta?: AppRouteMeta;
    props?: boolean | Record<string, any> | ((to: any) => Record<string, any>);
}

export type AppRouteBuildContext<TAppRoute extends AppRoute = AppRoute> = {
    parent: TAppRoute['parent']
}

export type AppScope = {
    name: string
    code: string
}

export type  AppRouterScope = 'public' | 'personal' | 'page'

export type AppRouteContext<TAppRoute extends AppRoute = AppRoute> = {
    meta: AppRouteMeta
    result?: Result
}




