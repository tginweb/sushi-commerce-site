import {RouteComponent, RouteLocationNormalized, RouteRecordSingleView} from "vue-router";
import {StackRouterResolveResult, TRouteStackConfig, TStackItemRoute} from "@/packages/stack-router/types";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {RouteStackableComponent} from "@/packages/stack-router/hooks/useStackRoute";

let lastResolved: StackRouterResolveResult = null

function extractMatchedCurrent(to: RouteLocationNormalized) {
    return to.matched[to.matched.length - 1] as unknown as RouteRecordSingleView
}

function extractToComponent(to: RouteLocationNormalized) {
    const toCurrent = extractMatchedCurrent(to)
    const toCurrentComponent: RouteStackableComponent | RouteComponent = toCurrent.component || (toCurrent.components as any)?.default
    return toCurrentComponent
}

export async function useStackRouterBeforeEach(to: RouteLocationNormalized, from: RouteLocationNormalized, next: any): Promise<boolean | null> {

    const stackRouter = useStackRouter()

    const toCurrent = extractMatchedCurrent(to)
    const fromCurrent = extractMatchedCurrent(from)

    let ret: boolean | null = null
    let stackConfig: TRouteStackConfig | null = null

    const toCurrentComponent = extractToComponent(to)

    let currentResolved: StackRouterResolveResult = 'normal'

    if ('_isStackable' in toCurrentComponent) {

        if (
            lastResolved === 'stackable_route' &&
            (toCurrent === fromCurrent) &&
            toCurrentComponent.isInitialized()
        ) {
            toCurrentComponent.setProps(to.params)
            return null
        }

        stackConfig = await toCurrentComponent.initialize(to)


        if (stackConfig) {

            const stack = stackRouter.getStack()

            if (stack) {

                console.log('STACK ROUTE', {
                    stackConfig,
                    lastResolved
                })

                const stackItemRoute: TStackItemRoute = {
                    path: to.path,
                    name: to.name,
                }

                if (lastResolved) {
                    currentResolved = 'stackable_stack'
                    stack.push(stackConfig.stackComponent, stackConfig.props, stackItemRoute)
                    next(false)
                    ret = true
                } else {
                    if (!stackConfig.routeComponent) {

                        if (stackConfig.backRedirect) {
                            next({
                                path: stackConfig.backRedirect,
                                hash: '#modal=' + to.path
                            })
                            return true
                        } else {
                            currentResolved = 'stackable_stack'
                            stackItemRoute.initialRoute = true
                            stack.push(stackConfig.stackComponent, stackConfig.props, stackItemRoute)
                        }

                    } else {
                        currentResolved = 'stackable_route'
                    }
                }
            }
        }
    }

    //console.log('currentResolved', {currentResolved, ret})

    if (!lastResolved && (currentResolved !== 'stackable_stack')) {

        const initStackTo = stackRouter.getStackRouteByHash(to.hash)

        if (initStackTo && initStackTo.matched.length) {

            const initStackComponent = extractToComponent(initStackTo as any)

            if (initStackComponent) {
                if ('_isStackable' in initStackComponent) {
                    const initStackConfig = await initStackComponent.initialize(initStackTo)

                    console.log('initStackConfig', initStackConfig)

                    const stack = stackRouter.getStack()

                    if (stack && initStackConfig) {

                        const initStackRoute: TStackItemRoute = {
                            path: initStackTo.path,
                            name: initStackTo.name,
                            initialRoute: true
                        }

                        stack.push(initStackConfig.stackComponent, initStackConfig.props, initStackRoute)
                    }
                }
            }

        }
    }

    lastResolved = currentResolved

    return ret
}


