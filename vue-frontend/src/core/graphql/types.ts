import {MutationOptions, OperationVariables, QueryOptions,} from "@apollo/client";
//@ts-ignore
import {Mutation, MutationGenqlSelection, Query, QueryGenqlSelection, QueryInfo, Response,} from "@/gql/gen/schema";
import {UseQueryReturn} from "@vue/apollo-composable";
import {DocumentParameter, OptionsParameter, VariablesParameter,} from "@vue/apollo-composable/dist/useQuery";
import {Ref} from "@vue/reactivity";
import {ComputedRef} from "vue";
import {UseErrorManagerParams} from "../error/useErrorManager";

export type TGraphqlRequestMutationOptions<TData, TArgs> = Pick<
    MutationOptions<TData, TArgs>,
    Exclude<keyof MutationOptions, "mutation">
>;

export type TGraphqlRequestQueryOptions<TData, TArgs> = Pick<
    QueryOptions<TArgs, TData>,
    Exclude<keyof QueryOptions, "query">
>;

enum TGraphqlRequestThrowOn {
    FATAL = "fatal",
    UNSUCCESS = "unsuccess",
}

export type TGraphqlRequestParams = {
    throwInternalError?: boolean;
    throwUserError?: boolean;
    throwError?: boolean | TGraphqlRequestThrowOn[];
    state?: TGraphqlRequestMutationParamsState & TGraphqlRequestQueryParamsState;
    name?: string;
};

export type TGraphqlRequestQueryParamsState = {
    process: boolean;
    querying?: boolean;
    queried?: boolean;
};

export type TGraphqlRequestMutationParamsState = {
    process: boolean;
    mutating?: boolean;
    mutated?: boolean;
};

export type TGraphqlRequestQuery<TResult, TVariables> = (
    queryOptions: TGraphqlRequestQueryOptions<TResult, TVariables>,
    params?: TGraphqlRequestParams
) => Promise<TResult>;

export type TGraphqlRequestMutation<TResult, TVariables> = (
    mutationOptions: TGraphqlRequestMutationOptions<TResult, TVariables>,
    params?: TGraphqlRequestParams
) => Promise<TResult>;

export type TUseEntityQueryFilter = any;

export type TUseEntityQueryNav = {
    page?: number;
    limit?: number;
    sortBy?: [string, string][];
    rowsNumber?: number;
};

export type TUseEntityRecordsetQueryParams<
    TEntityType,
    TResult extends TEntityRecordsetResult<TEntityType>,
    TVariables extends OperationVariables
> = {
    queryBuilder: any;
    query?: DocumentParameter<TResult, TVariables>;
    queryAlias: string;
    variables?: VariablesParameter<TVariables>;
    options?: OptionsParameter<TResult, TVariables>;
    filter?: TUseEntityQueryFilter;
    nav?: TUseEntityQueryNav;
};

export type TUseEntityListQueryParams<
    TEntityType,
    TResult extends TEntityListResult<TEntityType>,
    TVariables extends OperationVariables
> = {
    queryBuilder: any;
    query: DocumentParameter<TResult, TVariables>;
    queryAlias: string;
    variables?: VariablesParameter<TVariables>;
    options?: OptionsParameter<TResult, TVariables>;
    filter?: TUseEntityQueryFilter;
    nav?: TUseEntityQueryNav;
};

export type TUseEntityRecordsetQueryReturn<
    TEntityType,
    TResult,
    TVariables extends OperationVariables
> = UseQueryReturn<TResult, TVariables> & {
    filter: Ref<TUseEntityQueryFilter>;
    nav: Ref<TUseEntityQueryNav>;
    resetFilters: () => void;
    updateFilter: (newData: any) => void;
    content: ComputedRef<TResult[keyof TResult] | null>;
    rows: ComputedRef<TEntityType[]>;
    pagination: TQuasarPagination;
    selected?: Ref<TEntityType[]>;
    selectedIds: ComputedRef<number[]>;
};

export type TUseEntityListQueryReturn<
    TEntityType,
    TResult,
    TVariables extends OperationVariables
> = UseQueryReturn<TResult, TVariables> & {
    filter: Ref<TUseEntityQueryFilter>;
    nav: Ref<TUseEntityQueryNav>;
    resetFilters: () => void;
    updateFilter: (newData: any) => void;
    content: ComputedRef<TResult[keyof TResult] | null>;
    rows: ComputedRef<TEntityType[]>;
};

export type TQuasarPagination = {
    sortBy?: string | null;
    descending?: boolean;
    page?: number;
    rowsPerPage?: number;
    rowsNumber?: number;
};

export type TAction<T> = {
    code: string;
    label: number;
    payload: T;
    type: "store" | "router" | "redirect" | "mutation";
    path: string;
    allowMultiple: boolean;
};

export type TEntityAction = TAction<{
    entityType: string;
    entityId: number;
}>;

export type TEntity = {
    ID: number;
    //actions: TEntityAction[]
};

export type TEntityRecordset<T> = {
    nodes: T[];
    info: QueryInfo;
};

export type TEntityList<T> = T[];

export type TEntityRecordsetResult<T> = Record<string, TEntityRecordset<T>>;
export type TEntityListResult<T> = Record<string, TEntityList<T>>;

export type ResponseCommon = Omit<Response, "__typename">;

export type GqlMutateFunction<TResult, TVariables> = (
    variables: TVariables
) => Promise<TResult>;

export type GqlQueryFunction<TResult, TVariables> = (
    variables: TVariables
) => Promise<TResult>;

export type GqlUseMutationReturn<TResult, TVariables> = {
    mutate: GqlMutateFunction<TResult, TVariables>;
    loading: Ref<boolean>;
    loaded: Ref<boolean>;
    startTimestamp: Ref<number>;
    finishTimestamp: Ref<number>;
    result: TResult;
};

export type GqlUseQueryReturn<TResult, TVariables> = {
    query: GqlQueryFunction<TResult, TVariables>;
    loading: Ref<boolean>;
    loaded: Ref<boolean>;
    startTimestamp: Ref<number>;
    finishTimestamp: Ref<number>;
    result: TResult;
};

export type GqlUserRequestReturn =
    | GqlUseMutationReturn<any, any>
    | GqlUseQueryReturn<any, any>;

export type GqlMutationGenerator<TResult, TVariables> = {
    build: (variables: TVariables) => MutationGenqlSelection;
    mutate: (variables?: TVariables, fetchParams?: GqlRequestFetchParams) => Promise<TResult>;
    _variables: TVariables;
    _result: TResult;
};

export type GqlQueryGenerator<TResult, TVariables> = {
    build: (variables: TVariables) => QueryGenqlSelection;
    run: (variables?: TVariables, fetchParams?: GqlRequestFetchParams) => Promise<TResult>;
    _variables: TVariables;
    _result: TResult;
};

export type GqlRequestParamsErrorGroups = Record<string, any>;

export type GqlRequestParams<TVariables = any> = {
    name?: string;
    isMultiple?: boolean;
    throwOnError?: boolean;
    errorManager?: UseErrorManagerParams;
    debounced?: number | null;
    throttled?: number | null;
    variables?: TVariables | Ref<TVariables> | ComputedRef<TVariables>,
    errorProcessorDisable?: boolean,
};

export type GqlRequestQueryParams<TVariables, TResult> = GqlRequestParams<TVariables> & {
    enabled?: Ref<boolean>
    lazy?: boolean
    cache?: number | boolean
    initialResult?: () => TResult | null | undefined
    onResult?: (result: TResult) => void
    checkEmptyResult?: (result: TResult) => boolean
    pollingInterval?: number
}

export type GqlRequestMutationParams<TVariables, TResult> = GqlRequestParams<TVariables> & {
    onResult?: (result: TResult) => void
}

export type GqlRequestFetchParams = {
    immediate?: boolean
    refetch?: boolean
}
