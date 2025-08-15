// stores/taskStore.ts
import {computed, Ref, ref, toValue, watch} from 'vue'
import {useIntervalFn} from "@vueuse/core";
import {TaskStatus} from "@/core/types";
import {type DebounceSettings, type ThrottleSettings} from "lodash-es";
import throttle from "lodash/throttle";
import debounce from "lodash/debounce";

const POLLING_DEFAULT_INTERVAL = 1000 * 120

export type UseTaskConfig = {
    polling?: number | boolean | Ref<number | boolean>,
    throttled?: number
    throttleSettings?: ThrottleSettings
    debounced?: number
    debounceSettings?: DebounceSettings
}

export const useTask = <T extends (...args: any[]) => Promise<any>>(
    asyncFn: T,
    config: UseTaskConfig = {}
) => {

    const {
        polling,
        throttled,
        throttleSettings,
        debounced,
        debounceSettings,
    } = config

    // State
    const status = ref<TaskStatus>('idle')
    const error = ref<Error | null>(null)
    const result = ref<Awaited<ReturnType<T>> | null>(null)

    // Computed
    const isIdle = computed(() => status.value === 'idle')
    const isProcessing = computed(() => status.value === 'processing')
    const isResolved = computed(() => status.value === 'resolved')
    const isRejected = computed(() => status.value === 'rejected')

    // Actions
    const execute = async (...args: any): Promise<Awaited<ReturnType<T>>> => {
        try {
            status.value = 'processing'
            error.value = null
            const response = await asyncFn(...args)
            result.value = response
            status.value = 'resolved'
            return response
        } catch (err) {
            error.value = err as Error
            status.value = 'rejected'
            throw err
        }
    }

    let run = execute

    if (throttled) {
        run = throttle(execute, throttled, throttleSettings) as typeof execute
    } else if (debounced) {
        run = debounce(execute, debounced, debounceSettings) as typeof execute
    }

    const reset = () => {
        status.value = 'idle'
        error.value = null
        result.value = null
    }

    const pollingInitialValue = toValue(polling)

    if (typeof pollingInitialValue === 'boolean' || typeof pollingInitialValue === 'number') {

        const pollingInternal = computed(() => {
            const val = toValue(polling)

            if (val) {
                return val === true ? POLLING_DEFAULT_INTERVAL : val
            } else {
                return 0
            }
        })

        const {pause, resume, isActive} = useIntervalFn(() => {
            run()
        }, pollingInternal, {immediate: false})

        watch(pollingInternal, () => {
            if (pollingInternal.value) {
                resume()
            } else {
                pause()
            }
        }, {immediate: true})
    }

    return {
        // State
        status,
        error,
        result,

        // Computed
        isIdle,
        isProcessing,
        isResolved,
        isRejected,

        // Actions
        run,
        reset
    }
}
