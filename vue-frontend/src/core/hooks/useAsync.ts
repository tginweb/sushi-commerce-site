import type {Ref, ShallowRef, UnwrapRef} from 'vue'
import {isRef, isShallow, ref as deepRef, shallowRef} from 'vue'
import {noop, until} from '@vueuse/shared'
import {debounce, DebounceSettings, throttle, ThrottleSettings} from "lodash-es";

export interface UseAsyncReturnBase<Data, Params extends any[], Shallow extends boolean> {
    state: Shallow extends true ? Ref<Data> : Ref<UnwrapRef<Data>>
    isReady: Ref<boolean>
    isLoading: Ref<boolean>
    error: Ref<unknown>
    execute: (...args: Params) => Promise<Data>
    executeImmediate: (...args: Params) => Promise<Data>
}

export type UseAsyncReturn<Data, Params extends any[], Shallow extends boolean>
    = UseAsyncReturnBase<Data, Params, Shallow>
    & PromiseLike<UseAsyncReturnBase<Data, Params, Shallow>>

export interface UseAsyncOptions<Shallow extends boolean, D = any> {
    /**
     * Delay for the first execution of the promise when "immediate" is true. In milliseconds.
     *
     * @default 0
     */
    delay?: number

    /**
     * Execute the promise right after the function is invoked.
     * Will apply the delay if any.
     *
     * When set to false, you will need to execute it manually.
     *
     * @default true
     */
    immediate?: boolean

    /**
     * Callback when error is caught.
     */
    onError?: (e: unknown) => void

    /**
     * Callback when success is caught.
     * @param {D} data
     */
    onSuccess?: (data: D) => void

    /**
     * Sets the state to initialState before executing the promise.
     *
     * This can be useful when calling the execute function more than once (for
     * example, to refresh data). When set to false, the current state remains
     * unchanged until the promise resolves.
     *
     * @default true
     */
    resetOnExecute?: boolean

    /**
     * Use shallowRef.
     *
     * @default true
     */
    shallow?: Shallow
    /**
     *
     * An error is thrown when executing the execute function
     *
     * @default false
     */
    throwError?: boolean

    stateful?: boolean
    stateless?: boolean
    throttled?: number
    throttleSettings?: ThrottleSettings
    debounced?: number
    debounceSettings?: DebounceSettings
}

/**
 * Reactive async state. Will not block your setup function and will trigger changes once
 * the promise is ready.
 *
 * @see https://vueuse.org/useAsyncState
 * @param promise         The promise / async function to be resolved
 * @param initialState    The initial state, used until the first evaluation finishes
 * @param options
 */
export function useAsync<Data, Params extends any[] = any[], Shallow extends boolean = true>(
    promise: Promise<Data> | ((...args: Params) => Promise<Data>),
    initialState: Data | Ref<Data> | Ref<UnwrapRef<Data>>,
    options?: UseAsyncOptions<Shallow, Data>,
): UseAsyncReturn<Data, Params, Shallow> {
    const {
        stateful = false,
        immediate = true,
        delay = 0,
        onError = noop,
        onSuccess = noop,
        resetOnExecute = true,
        shallow = true,
        throwError,
        throttled,
        throttleSettings,
        debounced,
        debounceSettings,
    } = options ?? {}

    let state: Ref<Data> | Ref<UnwrapRef<Data>> | undefined = undefined

    if (stateful) {
        if (isRef(initialState)) {
            state = initialState
        } else {
            state = shallow ? shallowRef(initialState) : deepRef(initialState)
        }
    }

    const isReady = shallowRef(false)
    const isLoading = shallowRef(false)
    const error = shallowRef<unknown | undefined>(undefined)

    async function executeImmediate(...args: any[]) {
        if (resetOnExecute && stateful && state && !isRef(initialState))
            state.value = initialState
        error.value = undefined
        isReady.value = false
        isLoading.value = true

        const _promise = typeof promise === 'function'
            ? promise(...args as Params)
            : promise

        let data

        try {
            data = await _promise
            if (stateful && state)
                state.value = data
            isReady.value = true
            onSuccess(data)
        } catch (e) {
            console.log('e5', e)
            error.value = e
            onError(e)
            if (throwError)
                throw e
        } finally {
            isLoading.value = false
        }

        return data as Data
    }

    let execute = executeImmediate

    if (throttled) {
        execute = throttle(executeImmediate, throttled, throttleSettings) as typeof execute
    } else if (debounced) {
        execute = debounce(executeImmediate, debounced, debounceSettings) as typeof execute
    }

    if (immediate) {
        if (delay) {
            setTimeout(() => {
                execute()
            }, delay)
        } else {
            execute()
        }
    }

    const shell: UseAsyncReturnBase<Data, Params, Shallow> = {
        state: state as Shallow extends true ? ShallowRef<Data> : Ref<UnwrapRef<Data>>,
        isReady,
        isLoading,
        error,
        execute,
        executeImmediate: (...args: any[]) => execute(0, ...args),
    }

    function waitUntilIsLoaded() {
        return new Promise<UseAsyncReturnBase<Data, Params, Shallow>>((resolve, reject) => {
            until(isLoading).toBe(false).then(() => resolve(shell)).catch(reject)
        })
    }

    return {
        ...shell,
        then(onFulfilled, onRejected) {
            return waitUntilIsLoaded()
                .then(onFulfilled, onRejected)
        },
    }
}
