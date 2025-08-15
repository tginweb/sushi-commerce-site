import {useAsync, UseAsyncOptions, UseAsyncReturnBase} from "@/core/hooks/useAsync";
import type {Ref, UnwrapRef} from "vue";

export type UseAsyncStatefulReturn<Data, Params extends any[], Shallow extends boolean>
    = UseAsyncReturnBase<Data, Params, Shallow>

export function useAsyncStateful<Data, Params extends any[] = any[], Shallow extends boolean = true>(
    promise: Promise<Data> | ((...args: Params) => Promise<Data>),
    initialState: Data | Ref<Data> | Ref<UnwrapRef<Data>>,
    options?: UseAsyncOptions<Shallow, Data>,
): UseAsyncStatefulReturn<Data, Params, Shallow> {
    const _options: UseAsyncOptions<Shallow, Data> = {
        ...options,
        stateful: true
    }
    return useAsync<Data, Params, Shallow>(promise, initialState, _options)
}
