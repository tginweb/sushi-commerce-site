import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {h, reactive} from "vue";
import {StackRouterResolveResult, TRouteStackConfig} from "@/packages/stack-router/types";
import {RouteLocationNormalized} from "vue-router";
import {overwriteReactiveObject} from "@/core/util/overwriteReactiveObject";

async function resolveComponent(component: any) {
    if (!component)
        return component
    if (component.then) {
        component = await component
    }
    return component.default ? component.default : component
}


export type RouteStackableComponent = {
    _isStackable: boolean
    getStackConfig: () => any
    initialize: (to: RouteLocationNormalized, lastResolved: StackRouterResolveResult) => any
    isInitialized: () => boolean
    setup: any
    setProps: (props: any) => void
}

const resolveCallbacks = (data: any, ctx: any, exclude: any) => {
    const cdata: any = {}

    for (const [key, value] of Object.entries(data)) {
        if (!exclude || exclude.indexOf(key) === -1) {
            cdata[key] = typeof value === 'function' ? value(ctx) : value
        } else {
            cdata[key] = value
        }
    }

    return cdata
}

export const useStackRouteComponent = (stackComponent: any, config: TRouteStackConfig = {}): RouteStackableComponent => {

    let stackConfig: TRouteStackConfig = {}

    let initialized = false

    let props = reactive<any>({})

    async function initialize(to: RouteLocationNormalized, lastResolved: StackRouterResolveResult): Promise<TRouteStackConfig> {

        initialized = true

        const stackRouter = useStackRouter()

        let stackGlobalConfig = stackRouter.getStack()?.config || {}

        stackConfig = {
            ...stackGlobalConfig
        }

        let stackProps: any = {}

        const nestedConfigs: TRouteStackConfig[] = [
            ...to.matched.filter(record => !!record.meta.stack).map(record => record.meta.stack as TRouteStackConfig),
            config,
            {
                stackComponent,
                props: to.params
            }
        ]

        nestedConfigs.forEach((nestedConfig) => {
            stackConfig = {
                ...stackConfig,
                ...nestedConfig
            }
            stackProps = {
                ...stackProps,
                ...(nestedConfig.props || {})
            }
        })

        stackConfig.props = stackProps

        stackConfig = resolveCallbacks(stackConfig, {}, ['stackComponent', 'routeComponent', 'backComponent']) as TRouteStackConfig

        stackConfig.stackComponent = await resolveComponent(stackConfig.stackComponent)
        stackConfig.backComponent = await resolveComponent(stackConfig.backComponent)
        stackConfig.routeComponent = await resolveComponent(stackConfig.routeComponent)

        if (!lastResolved && stackConfig.routeComponent) {
            stackConfig.component = stackConfig.routeComponent
        } else {
            stackConfig.component = stackConfig.backComponent
        }

        overwriteReactiveObject(props, stackProps)

        return stackConfig
    }

    return {
        _isStackable: true,
        getStackConfig: () => {
            return stackConfig
        },
        setProps: (newProps: any) => {
            Object.assign(props, newProps)
        },
        isInitialized: () => {
            return initialized
        },
        initialize,
        setup: () => {
            return () => {
                if (!stackConfig.component) return null
                return h(stackConfig.component, props);
            }
        }
    }
}


