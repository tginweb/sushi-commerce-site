import {Stack} from './Stack'
import {RouteComponentMaybeStackable, TStackConfig} from "../types";
import {StackItem} from "./StackItem";
import {createRouterMatcher, Router} from "vue-router";
import {nextTick} from "vue";

export class StackManager {

    public stacks: Record<string, Stack> = {}
    public skipPopstate: boolean = false
    public router!: Router
    public pageScrollPosition: number = 0
    public pageUrl?: string

    public lastPushedRoutableStack?: Stack | null

    constructor() {
        if (!process.env.SERVER) {
            window.addEventListener('popstate', this.onHistoryBack.bind(this), false)
        }
    }

    getStackRouteByHash(hash: string) {
        if (hash && hash.match(/\#/)) {
            const [stackName, stackRoutePath] = hash.replace('#', '').split('=')
            if (stackName && stackRoutePath && this.getStack(stackName)) {

                console.log('this.getStackableRoutes()', this.getStackableRoutes())
                const matcher = createRouterMatcher(
                    this.getStackableRoutes() as any,
                    {}
                )
                const resolved = matcher.resolve(
                    {
                        path: stackRoutePath
                    }, {
                        path: '/',
                        name: undefined,
                        params: {},
                        matched: [],
                        meta: {},
                    })

                return resolved
            }
        }
    }

    getStackableRoutes() {
        const routes = this.router.getRoutes() as RouteComponentMaybeStackable[]
        return routes.filter(route => {
            return !!route.components?.default?._isStackable
        })
    }

    setVueRouter(router: Router) {
        this.router = router
    }

    onHistoryBack() {
        if (this.skipPopstate) {
            return;
        }
        const stack = this.lastPushedRoutableStack  || this.getRoutableStack()
        if (stack) {
            if (stack.haveItems()) {
                stack.removeLast(true)
                nextTick(() => {
                    window.scrollTo({top: this.pageScrollPosition, behavior: 'instant'})
                })
            }
        }
    }

    initStack<TConfig extends TStackConfig = TStackConfig>(name: string, config?: TConfig) {
        this.stacks[name] = new Stack<TConfig>(this, name, config)
    }

    getStack<TConfig extends TStackConfig = TStackConfig>(name: string = 'modal', config?: TConfig) {
        if (!this.stacks[name]) {
            this.initStack<TConfig>(name, config)
        }
        return this.stacks[name]
    }

    getStackByItemId<TConfig extends TStackConfig = TStackConfig>(id: string) {
        const [stackName] = id.split('-')
        return this.getStack<TConfig>(stackName) as Stack
    }

    getItemById(id: string, required: boolean = false) {
        const stack = this.getStackByItemId(id)
        return stack.getById(id, required)
    }

    onQueueChanged() {

    }

    getLocation() {
        const [path, hash] = window.location.href.split("#")
        return {
            path,
            hash
        }
    }

    onBeforePushItem(item: StackItem) {
        if (!item.stack.haveItems()) {
            this.pageScrollPosition = window.scrollY
            this.pageUrl = window.location.href
        }
    }

    onPushItem(item: StackItem) {
        if (item.isRoutable()) {
            this.lastPushedRoutableStack = item.stack
            this.historyPush(null, item.stack.name + '=' + item.route?.path)
        }
    }

    onRemoveItem(item: StackItem) {
        if (item.isRoutable()) {

            this.lastPushedRoutableStack = null

            console.log('historyBack', item)

            document.dispatchEvent(new CustomEvent("router.watchStatusChange", {
                detail: {
                    status: false
                }
            }))

            this.skipPopstate = true

            if (!item.route.initialRoute) {
                window.history.back()
            }

            setTimeout(() => {
                this.skipPopstate = false
                document.dispatchEvent(new CustomEvent("router.watchStatusChange", {
                    detail: {
                        status: true
                    }
                }))
            }, 50)
        }
    }

    historyPush(path: string | null | undefined, hash?: string) {
        const {path: currentPath} = this.getLocation()
        let url = path || currentPath
        if (hash) {
            url += '#' + hash
        }
        const new_state = {
            //  current: '/'
        }
        console.log('historyPush', url)
        window.history.pushState(new_state, '', url)
    }

    getRoutableStack() {
        return Object.values(this.stacks).find(stack => stack.config.routable)
    }
}

export const stackManager = new StackManager()


