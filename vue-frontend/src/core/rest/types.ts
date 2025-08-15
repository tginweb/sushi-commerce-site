//@ts-ignore
import {Mutation, MutationGenqlSelection, Query, QueryGenqlSelection, QueryInfo, Response,} from "@/gql/gen/schema";
import {Ref} from "@vue/reactivity";
import {UseErrorManagerParams} from "../error/useErrorManager";

export type RestRequestParams<TVariables = any> = {
    name?: string;
    isMultiple?: boolean;
    throwOnError?: boolean;
    errorManager?: UseErrorManagerParams;
    debounced?: number | null;
    throttled?: number | null;
    variables?: TVariables | Ref<TVariables>,
    errorProcessorDisable?: boolean,
};

export type RestRequestQueryParams<TVariables, TResult> = RestRequestParams<TVariables> & {
    enabled?: Ref<boolean>
    lazy?: boolean
    cache?: number | boolean
    initialResult?: () => TResult | null | undefined
    onResult?: (result: TResult) => void
    checkEmptyResult?: (result: TResult) => boolean
    pollingInterval?: number
}

export type RestRequestMutationParams<TVariables, TResult> = RestRequestParams<TVariables> & {}

export type RestRequestFetchParams = {
    immediate?: boolean
    refetch?: boolean
}
