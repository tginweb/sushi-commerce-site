// hooks/useHashScroll.js
import {onMounted, onUnmounted, Ref, ref, shallowRef, toValue, watch} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import {IntersectionValue} from "quasar";
import {scrollTo} from "@/core/util/scrollTo";

type OptionsType = {
    name?: string
    scrollTimeout: number
    checkInterval: number
    scrollOffset: number | Ref<number>
    scrollOptions: ScrollToOptions
    intersectScrollMargin: string
    intersectThreshold: any
    find?: boolean
}

const defaultOptions: OptionsType = {
    scrollTimeout: 5000,
    checkInterval: 100,
    scrollOffset: 0,
    scrollOptions: {behavior: 'smooth'},
    intersectThreshold: 0,
    intersectScrollMargin: '-50% 0% -50% 0%',
    find: false
}

interface RouterWatchStatusEvent extends CustomEvent {
    watchStatus: boolean
}

declare global {
    interface HTMLElement {
        _intersectObserverHashScroll?: IntersectionObserver
    }
}

const hashClean = (hash: string | null) => {
    if (hash) {
        if (!hash.match('=')) {
            return hash.replace('#', '')
        }
    }
    return null
}

export function useHashScroll(userOptions: Partial<OptionsType> = {}) {

    const options: OptionsType = {...defaultOptions, ...userOptions}

    const router = useRouter()

    const route = useRoute()

    const activeHash = ref<string | null>(hashClean(route.hash))

    const timeoutId = ref<NodeJS.Timeout | null>(null)

    const startTime = ref<number | null>(null)

    const isScrolling = ref(false)
    const scrollingTo = ref<string>('')

    const routerWatchSkip = ref(false)

    const onScrollEnd = () => {
        scrollingTo.value = ''
        isScrolling.value = false
    }

    const container = ref<HTMLElement>()

    const scrollInternal = (customHash?: string) => {

        return new Promise((resolve, reject) => {

            const hash = customHash || activeHash.value

            //console.log('scrollToInternal', hash)

            if (!hash) {
                window.scrollTo({top: 0})
                resolve(true)
                return
            }

            startTime.value = Date.now()

            const cleanup = () => {
                if (timeoutId.value) {
                    clearTimeout(timeoutId.value)
                    timeoutId.value = null
                }
            }

            const checkElement = () => {
                const element = document.querySelector('#' + hash)
                const currentTime = Date.now()
                //  console.log('element', element)
                if (element) {
                    scrollingTo.value = hash
                    isScrolling.value = true
                    setTimeout(() => {
                        scrollingTo.value = ''
                        isScrolling.value = false
                    }, 1500)
                    scrollTo(element as HTMLElement, toValue(options.scrollOffset), options.scrollOptions)
                    //element.scrollIntoView(options.scrollOptions)
                    cleanup()
                    resolve(true)
                } else if (startTime.value && (currentTime - startTime.value < options.scrollTimeout)) {
                    timeoutId.value = setTimeout(checkElement, options.checkInterval)
                } else {
                    cleanup()
                    reject(`Element ${hash} not found in ${options.scrollTimeout}ms`)
                }
            }

            cleanup()
            checkElement()
        })
    }

    const scrollToOnly = async (hash?: string) => {
        try {
            await scrollInternal(hash)
        } catch (e) {
            console.log(e)
        }
    }

    const updateHash = (hash: string | null, source?: 'call' | 'router' | 'scroll') => {

        activeHash.value = hash
        if (source !== 'router') {
            routerWatchSkip.value = true
            hash ? router.push({hash: '#' + hash}) : router.push({hash: ''})
        }

        console.log('activeHash.value', activeHash.value)
    }

    const scroll = async (hash: string | null) => {
        updateHash(hash, 'call')
        await scrollToOnly()
    }

    const handleClick = (event: any) => {
        const link = event.target.closest('a');
        if (!link) return;
        event.preventDefault();

        const url = new URL(link.href);
        const hash = url.hash.slice(1); // Убираем #

        if (hash) {
            event.preventDefault()
            scroll(hash)
        }
    }

    const intersectOptions = {
        rootMargin: options.intersectScrollMargin,
        threshold: options.intersectThreshold
    }

    const targets = shallowRef<Record<string, HTMLElement>>({});
    const links = shallowRef<Record<string, HTMLElement>>({});

    const findElements = () => {

        const foundLinks = document.querySelectorAll('[data-scroll]')
        const foundTargets = document.querySelectorAll('[data-scroll-target]')

        links.value = Array.from(foundLinks).reduce<Record<string, HTMLElement>>((acc, link) => {
            if (link instanceof HTMLElement) {
                link.addEventListener('click', handleClick)
                if (link.id)
                    acc[link.id] = link as HTMLElement
            }
            return acc;
        }, {})

        targets.value = Array.from(foundTargets).reduce<Record<string, HTMLElement>>((acc, target) => {
            if (target.id && target instanceof HTMLElement) {
                const id = target.id
                if (targets.value[id]) {
                    if (targets.value[id] !== target) {
                        target._intersectObserverHashScroll?.disconnect()
                    } else {
                        return acc
                    }
                }
                if (id) {
                    const observer = new IntersectionObserver((entries,) => {
                        entries.forEach(entry => intersectHandler(id, entry))
                    }, intersectOptions)
                    target._intersectObserverHashScroll = observer
                    observer.observe(target)
                    acc[id] = target as HTMLElement
                }
            }
            return acc;
        }, {})
    }

    const onWatchStatusChange = (event: any) => {
        routerWatchSkip.value = !event.detail.status
    }

    onMounted(async () => {
        addEventListener("scrollend", onScrollEnd)

        //console.log('onMounted, active hash: ', activeHash.value)

        await router.isReady()

        if (options.find)
            findElements()

        if (activeHash.value)
            await scrollToOnly()

        document.addEventListener("router.watchStatusChange", onWatchStatusChange)

    })

    onUnmounted(() => {
        if (timeoutId.value) clearTimeout(timeoutId.value)

        removeEventListener("scrollend", onScrollEnd)

        if (options.find) {
            Object.values(links.value).forEach((link) => {
                link.removeEventListener('click', handleClick)
            })
            Object.values(targets.value).forEach((target) => {
                if (target._intersectObserverHashScroll)
                    target._intersectObserverHashScroll.disconnect()
            })
        }

        document.removeEventListener("router.watchStatusChange", onWatchStatusChange)
    })


    watch(
        () => ({...route}),
        async (newRoute, oldRoute) => {

            /*
            console.log('WATCH ROUTE', {
                routerWatchSkip: routerWatchSkip.value,
                scrollingTo: '-' + scrollingTo.value,
                isScrolling: isScrolling.value ? 'true' : 'false',
                newRoute
            })

             */

            if (routerWatchSkip.value) {
                routerWatchSkip.value = false
                return
            }

            if (newRoute.path !== oldRoute?.path || newRoute.hash !== oldRoute?.hash) {
                updateHash(hashClean(newRoute.hash), 'router')
                await scrollToOnly()
            }
        },
        {
            // immediate: true,
            deep: true
        }
    )


    const intersectHandler = (hash: string, entry?: IntersectionObserverEntry) => {
        if (entry && entry.isIntersecting) {
            if (isScrolling.value && activeHash.value === hash) {
                setTimeout(() => {
                    isScrolling.value = false
                }, 300)
            }
            if (!isScrolling.value) {
                updateHash(hash, 'scroll')
            }
        }
        return true
    }

    const intersectHandlerCreate = (hash: string) => {
        return (entry?: IntersectionObserverEntry) => intersectHandler(hash, entry)
    }

    const intersectDirectiveConnect = (hash: string): IntersectionValue => {
        return {
            cfg: intersectOptions,
            handler: intersectHandlerCreate(hash)
        }
    }

    return {
        container,
        activeHash,
        scrollTo: scroll,
        updateHash,
        intersectHandler,
        intersectHandlerCreate,
        intersectOptions,
        intersectDirectiveConnect
    }
}
