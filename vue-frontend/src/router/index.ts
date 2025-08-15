//@ts-ignore
import {defineRouter} from '#q-app/wrappers';
import {
    createMemoryHistory,
    createRouter,
    createWebHashHistory,
    createWebHistory,
    RouteLocationNormalized,
    RouteRecordSingleView,
} from 'vue-router';
import routes from './routes';
import {QueryScope, useScopeQuery} from "@/core/store/scopeQuery";
import {app} from "@/modules/app";
import {useRootStore} from "@/stores/root";
import {useAlerts} from "@/core/store/alerts";
import {useRedirect} from "@/core/store/redirect";
import {collectMeta} from "@/core/router/collectMeta";
import {AppRouteContext} from "@/core/classes/App";
import Result from "@/core/classes/Result";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {useStackRouterBeforeEach} from "@/packages/stack-router/hooks/useStackRouterGuards";
import {useRouterStore} from "@/core/router/store";
import {useConfig} from "@/core/store/config";

function extractMatchedCurrent(to: RouteLocationNormalized) {
    return to.matched[to.matched.length - 1] as unknown as RouteRecordSingleView
}


if (!process.env.SERVER) {

}

export default defineRouter(function (/* { store, ssrContext } */) {

    let firstBeforeEach = true
    let firstAfterEach = true

    app.onBoot()

    // initAdvancedPushState()

    const stackRouter = useStackRouter()

    if (!process.env.SERVER) {
        window.history.scrollRestoration = 'manual'
    }

    stackRouter.initStack('modal', {
        //backComponent: StackRouteSafe,
        backRedirect: '/',
        routable: true,
        breakpoint: 'md'
    })

    const createHistory = process.env.SERVER
        ? createMemoryHistory
        : (process.env.VUE_ROUTER_MODE === 'history' ? createWebHistory : createWebHashHistory);

    const router = createRouter({
        scrollBehavior: (to, from) => {
            if (to.hash || to.fullPath === from.fullPath)
                return false
            return {left: 0, top: 0}
        },
        routes: routes as any,
        history: createHistory(process.env.VUE_ROUTER_BASE),
    })

    router.beforeEach(async (to, from, next) => {

        const toCurrent = extractMatchedCurrent(to)
        const fromCurrent = extractMatchedCurrent(from)

        if (toCurrent === fromCurrent) {
            const [toUrl] = to.fullPath.split('#')
            const [fromUrl] = from.fullPath.split('#')
            if (toUrl === fromUrl) {
                next()
                return
            }
        }

        const result = new Result()
        const meta = collectMeta(to)
        const context: AppRouteContext = {meta}

        //console.log('context', context)

        const {loadServerConfig} = useConfig()
        const {fetchScopesByRoute} = useScopeQuery()
        const {redirect} = useRedirect()
        const {showAlerts} = useAlerts()
        const {setRouteMetas} = useRouterStore()

        if (firstBeforeEach) {
            const {storesBoot} = useRootStore()
            await storesBoot()
            await loadServerConfig()
            firstBeforeEach = false
        }

        if ((await app.runGuardsAsync('routerBeforeEach', to, from, next, context) === false)) {
            return
        }

        const scopes: QueryScope[] = ['app']

        //if (meta.auth) scopes.push('user')

        await fetchScopesByRoute(to, scopes)

        if (!process.env.SERVER) {
            if (typeof window !== undefined && window.hidePreloader) window.hidePreloader()
        }

        const access = await app.testRouteAccess(to, from, result, context)

        if (access === false) {

            console.log('NO access', result)

            showAlerts(result.getMessages())

            const redir = result.getRedirect()

            if (redir) {
                if (redir.type === 'router') {
                    next(redir.path)
                    return
                } else {
                    next(false)
                    redirect(redir)
                    return
                }
            }
        }

        if ((await useStackRouterBeforeEach(to, from, next)) == true) {
            return
        }

        setRouteMetas(meta || {})

        next()
    })

    router.afterEach(async (to, from, failure) => {
        if (firstAfterEach && !process.env.SERVER) {
            const scopeQuery = useScopeQuery()
            await scopeQuery.fetchScopes('user')
        }
        await app.runCallbacksAsync('routerAfterEach', to, from, failure)
        firstAfterEach = false
    })

    stackRouter.setVueRouter(router)

    return router
})
