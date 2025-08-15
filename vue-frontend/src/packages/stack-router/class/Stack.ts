import {computed, reactive, ref, watch} from "vue";
import {StackManager} from "./StackManager";
import {createStackItem, StackItem} from "./StackItem";
import {TStackConfig, TStackItemRoute, TStackModalConfig} from "../types";
import {ComputedRef, Ref} from "@vue/reactivity";

export class Stack<TConfig extends TStackConfig = TStackConfig> {

    public manager: StackManager
    public name: string
    public activeIndex: Ref<number>
    public activeId: ComputedRef<string | null>
    public queue: StackItem[] = []
    public config: TStackModalConfig
    public lastId: number = 0

    constructor(manager: StackManager, name: string, config?: TConfig) {
        this.manager = manager
        this.name = name
        this.config = config || {}
        this.activeIndex = ref(-1)
        this.activeId = computed(() => {
            const item = this.queue[this.activeIndex.value]
            return item ? item.id : null
        })
        this.queue = reactive([])
        watch(this.queue, () => {
            manager.onQueueChanged()
        })
    }

    getManager() {
        return this.manager
    }

    onBeforePushItem(item: StackItem) {
        this.getManager().onBeforePushItem(item)
    }

    onPushItem(item: StackItem) {
        this.getManager().onPushItem(item)
    }

    onRemoveItem(item: StackItem) {
        this.getManager().onRemoveItem(item)
    }

    getNextItemId() {
        this.lastId++
        return this.name + '-' + this.lastId
    }

    replace(component: any, props: any) {
        const id = this.getNextItemId()
        const item = createStackItem(this, id, component, props)
        this.queue.push(item)
    }

    push<TProps = any>(component: any, props: TProps, route?: TStackItemRoute) {
        const id = this.getNextItemId()
        const item = createStackItem(this, id, component, props, route)
        this.onBeforePushItem(item)
        this.queue.push(item)
        this.activeIndex.value = this.queue.length - 1
        this.onPushItem(item)
    }

    haveItems() {
        return !!this.queue.length
    }

    removeById(id: string, skipEvents: boolean = false) {
        const index = this.queue.findIndex(item => item.id === id)
        if (index > -1) {
            const item = this.queue[index]
            if (item) {
                this.queue.splice(index, 1)
                this.activeIndex.value = index - 1
                if (!skipEvents)
                    this.onRemoveItem(item)
            }
        }
        //console.log('removeById', id)
    }

    removeLast(skipEvents: boolean = false) {
        const index = this.queue.length - 1
        if (index > -1) {
            const item = this.queue[index]
            if (item) {
                this.queue.splice(index, 1)
                this.activeIndex.value = index - 1
                if (!skipEvents)
                    this.onRemoveItem(item)
            }
        }
    }

    getById(id: string, required = false) {
        let item = this.queue.find(item => item.id === id)
        if (!item && required) {
            item = createStackItem(this, this.getNextItemId(), () => {
            }, {})
        }
        return item
    }

    getActive() {
        return this.activeIndex.value > -1 ? this.queue[this.activeIndex.value] : this.queue[this.queue.length - 1]
    }

    setActiveByIndex(index: number) {
        this.activeIndex.value = index
    }

    setActiveById(id: string) {
        this.activeIndex.value = this.queue.findIndex(item => item.id === id)
    }
}

