import {defineStore} from "pinia";
import {computed, reactive, ref} from "vue";
import {useApp} from "@/modules/app";
import {AppRouteMeta} from "@/core/app/types";
import {overwriteReactiveObject} from "@/core/util/overwriteReactiveObject";
import {Router} from "vue-router";
//@ts-ignore
import {MetaOptions} from "quasar/dist/types/meta";


export const useRouterStore = defineStore("router", () => {

    const cachedComponents = ref<string[]>([])

    const app = useApp()
    const routeMeta = reactive<AppRouteMeta>({})
    const defaultRouteMeta = app.getDefaultRouteMeta()

    const setRouteMeta = <T extends keyof AppRouteMeta = keyof AppRouteMeta>(name: T, value: AppRouteMeta[T]) => {
        routeMeta[name] = value
    }

    const setRouteMetas = (options: Partial<AppRouteMeta>) => {
        overwriteReactiveObject(routeMeta, {...defaultRouteMeta, ...options})
    }

    const assignRouteMetas = (options: Partial<AppRouteMeta>) => {
        Object.assign(routeMeta, options)
    }

    // Добавление компонента в кэш
    const addComponent = (name: string) => {
        if (!cachedComponents.value.includes(name)) {
            cachedComponents.value.push(name);
        }
    };

    // Инициализация обработчика маршрутов
    const initRouter = (router: Router) => {
        router.afterEach((to) => {
            to.matched.forEach((routeRecord) => {
                if (routeRecord.meta.keepAlive) {
                    const component = routeRecord.components?.default;
                    if (component?.name) addComponent(component.name);
                }
            })
        })
    }

    const htmlMeta = computed<MetaOptions>(() => {
        return {
            title: routeMeta.title,
            meta: {
                description: {name: 'description', content: routeMeta.description},
            },
        }
    })

    return {
        routeMeta,
        setRouteMetas,
        setRouteMeta,
        assignRouteMetas,
        initRouter,
        cachedComponents,
        htmlMeta
    }
})
