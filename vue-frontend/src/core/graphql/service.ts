import {
    Client,
    createClient,
    ErrorInterface,
    GenqlError,
    Message,
    Mutation,
    MutationGenqlSelection,
    Query,
    QueryGenqlSelection,
    Response,
    ResponseState,
} from "@/gql/gen";
import {RequestContext} from "@/core/composable/createRequestContext";
import {useCommonStore} from "@/core/store/common";
import {useConfig} from "@/core/store/config";
import throttle from "lodash/throttle";
import debounce from "lodash/debounce";
import {defineStore} from "pinia";
import {computed, ref, shallowRef, toValue, watch} from "vue";
import {getTypedEntries} from "../util/getTypedEntries";
import {
    GqlMutationGenerator,
    GqlQueryGenerator,
    GqlRequestFetchParams,
    GqlRequestMutationParams,
    GqlRequestQueryParams,
} from "./types";
import useErrorManager from "../error/useErrorManager";
import {TaskStatus} from "@/core/types";
import {paramToRef} from "@/core/vue/paramToRef";

export type UseQuery = {
    setEmptyResult: () => {},
    clearResult: () => {},
    invalidateCache: () => {},
    query: (variables?: any, params?: GqlRequestFetchParams) => void,
    loading: boolean,
    loaded: boolean,
    result: any,
    onResult: (result: any) => void,
    status: TaskStatus,
    isEmptyResult: boolean
}

export const useGraphql = defineStore("graphql", () => {
    const {get} = useConfig();

    const {getGlobalAlertsChannel, getGlobalRequestContext} = useCommonStore();

    const client: Client = createClient({
        url: get('app', 'API_GRAPHQL_URL'),
    });

    const errorProcessor = (e: GenqlError, requestContext?: RequestContext) => {
        const alertsChannel =
            requestContext?.getAlertsChannel() || getGlobalAlertsChannel();

        const messages: Partial<Message>[] = [];

        let status: string = "";

        if (e.errors) {
            e.errors.forEach((error) => {
                if (error.extensions?.status) {
                    status = error.extensions?.status;
                }
                messages.push({
                    type: "error",
                    message: error.message,
                });
            });
        } else {
            console.error(e);
        }

        alertsChannel?.showMessages(messages);
        return {
            status,
        };
    };

    const processResponseState = (
        state: ResponseState,
        requestContext?: RequestContext
    ) => {
        const alertsChannel =
            requestContext?.getAlertsChannel() || getGlobalAlertsChannel();

        const messages = state.messages.filter((message) => message);

        console.log("processResponseState", {
            responseContext: requestContext,
            alertsChannel,
            messages,
        });

        alertsChannel?.showMessages(messages);
    };

    const processing = ref(false);

    /*
    {
      success: boolean;
      error?: ErrorInterfaceGenqlSelection;
      errors?: ErrorInterfaceGenqlSelection;
      state: ResponseStateGenqlSelection;
    }
    */

    const responseSelection = (errorMultiple = true): any => {
        const selection: any = {
            success: true,
            error: {
                __fragment: "ErrorFields",
            },
            state: {
                __fragment: "ResponseState",
            },
        };

        if (errorMultiple) {
            selection.errors = {
                __fragment: "ErrorFields",
            };
        }
        return selection;
    };

    const query = async <T>(
        query: QueryGenqlSelection,
        requestParams: GqlRequestQueryParams<any, any> = {},
    ): Promise<T | null> => {
        let {isMultiple} = requestParams;
        let res = null;
        let data = await client.query(query);
        if (!data || typeof data !== "object") {
            throw new Error('Response error')
        }
        if (!isMultiple) {
            const returnField = Object.keys(data)[0] as keyof Query;
            res = data[returnField]
        } else {
            res = data;
        }
        return res as T
    }

    const queryWrapped = async <T>(
        _query: QueryGenqlSelection,
        params: GqlRequestQueryParams<any, any> = {},
        context: RequestContext = getGlobalRequestContext()
    ): Promise<T | null> => {
        const {throwOnError = false, errorProcessorDisable = false} = params;
        processing.value = true;
        let res = null;
        try {
            res = await query<T>(_query, params);
        } catch (e: unknown) {
            console.log('e3', e)

            if (!errorProcessorDisable) {
                errorProcessor(e as GenqlError, context);
            }
            if (throwOnError) {
                throw e;
            }
        } finally {
            processing.value = false;
        }
        return res as T
    };

    const mutation = async <T>(
        mutation: MutationGenqlSelection,
        requestParams: GqlRequestMutationParams<any, any> = {}
    ): Promise<T | null> => {
        let {isMultiple} = requestParams;
        let res = null;
        const data = await client.mutation(mutation);
        if (!data) {
            throw new Error('Undefined error')
        }
        if (!isMultiple) {
            const field = Object.keys(data)[0] as keyof Mutation;
            res = data[field];
        } else {
            res = data;
        }
        return res as T
    };

    const mutationWrapped = async <T>(
        _mutation: MutationGenqlSelection,
        params: GqlRequestMutationParams<any, any> = {},
        context: RequestContext = getGlobalRequestContext()
    ): Promise<T> => {
        context.getAlertsChannel()?.onRequestStart();

        const {name, isMultiple, throwOnError = false} = params;

        let res = null;

        processing.value = true;

        try {
            if (!isMultiple) {
                res = (await mutation<T>(_mutation, params)) as Response | null;
                if (res) {
                    if (res.state) {
                        processResponseState(res.state, context);
                    }
                }
            } else {
                res = (await mutation<T>(_mutation)) as Record<string, Response> | null;
                if (res) {
                    for (const [name, result] of getTypedEntries(res)) {
                        if (result.state) {
                            processResponseState(result.state, context);
                        }
                    }
                }
            }
        } catch (e: unknown) {
            console.log('e4', e)
            const {status} = errorProcessor(e as GenqlError, context);
            if (throwOnError) {
                throw e;
            }
            res = {
                status,
                success: false,
            };
        } finally {
            processing.value = false;
        }
        return res as T;
    };

    const useMutation = <
        T extends GqlMutationGenerator<Omit<Response, "__typename">, any>
    >(
        generator: T,
        params: GqlRequestMutationParams<T['_variables'], T['_result']> = {},
        context: RequestContext = getGlobalRequestContext()
    ) => {
        const {name, throwOnError = false, isMultiple, errorManager: errorManagerParams} = params;
        const loading = ref<boolean>(false);
        const loaded = ref<boolean>(false);
        const startTimestamp = ref<number>(0);
        const finishTimestamp = ref<number>(0);

        const result = ref<T['_result']>();

        const error = computed(() => {
            if (result?.value?.error) {
                return result?.value?.error;
            } else {
                return result?.value?.errors[0];
            }
        });

        const errors = computed(() => {
            if (result?.value?.errors) {
                return result?.value?.errors;
            } else if (result?.value?.error) {
                return [result?.value?.error];
            } else {
                return [];
            }
        });

        const errorType = computed(() => error.value?.type);

        const errorName = computed(() => error.value?.name);

        const success = computed(() => result?.value?.success);

        const _params = {
            ...params,
            isMultiple: false,
        };

        const errorManager = useErrorManager([], errorManagerParams)

        const processResult = (res: Response) => {
            let _errors: ErrorInterface[] = []
            if (res.errors && res.errors.length) {
                _errors = res.errors
            } else if (res.error) {
                _errors = [res.error]
            }
            errorManager.setErrors(_errors)
        }

        type OnResultCallback = (result: T["_result"]) => void;

        const callbacks: {
            onResult: OnResultCallback[];
        } = {
            onResult: [],
        };

        const onResult = (callback: (result: T["_result"]) => void) => {
            callbacks.onResult.push(callback);
        };

        const runCallbacks = (name: keyof typeof callbacks, ...args: any[]) => {
            switch (name) {
                case "onResult":
                    if (_params.onResult)
                        _params.onResult(args[0])
                    break;
            }
            callbacks[name].forEach((callback) => {
                callback.apply(null, args as any);
            });
        };

        const variablesRef = paramToRef<T["_variables"]>(params.variables);

        const mutate: T["mutate"] = async (variables = null, fetchParams: GqlRequestFetchParams = {}) => {

            const _variables = variables || toValue(variablesRef) || {}

            let res: Response;

            startTimestamp.value = Date.now();

            const query = generator.build(_variables);

            loading.value = true;

            try {
                res = await mutationWrapped(query, _params, context);
                loaded.value = true;
                result.value = res as T['_result'];

                runCallbacks('onResult', res)
            } catch (e) {
                throw e;
            } finally {
                loading.value = false;
                finishTimestamp.value = Date.now();
            }
            processResult(res)
            return res;
        };

        return {
            name,
            startTimestamp,
            finishTimestamp,
            mutate,
            onResult,
            loading,
            loaded,
            result,

            success,
            errorManager,
        };
    };

    const useQuery = <T extends GqlQueryGenerator<any, any>>(
        generator: T,
        params: GqlRequestQueryParams<T['_variables'], T['_result']> = {},
        context: RequestContext = getGlobalRequestContext()
    ) => {

        const _params = {
            ...params,
            throwOnError: true,
            isMultiple: false,
        };

        const {
            initialResult,
            debounced,
            throttled,
            checkEmptyResult
        } = _params

        const getInitialResultValue = () => {
            if (initialResult) {
                const _initialResult = initialResult()
                const value = toValue(_initialResult)
                if (typeof value !== 'undefined' && value !== null) {
                    return value
                }
            }
            return null
        }

        const initial = getInitialResultValue()

        const status = ref<TaskStatus>(initial === null ? 'idle' : 'resolved');
        const loading = ref(false);
        const loaded = ref(false);
        const startedAt = ref<number>(0);
        const finishedAt = ref<number>(0);
        const resolvedAt = ref<number>(0);
        const runCount = ref<number>(0);

        const variablesRef = paramToRef<T["_variables"]>(params.variables);

        const result = shallowRef<T["_result"]>();

        const isEmptyResult = computed(() => {
            if (result.value) {
                if (checkEmptyResult) {
                    return checkEmptyResult(result.value)
                } else {
                    if (Array.isArray(result.value)) {
                        return !result.value.length
                    } else {
                        return !result.value
                    }
                }
            } else {
                return true
            }
        })

        const forceDisabled = ref(params.lazy)
        const enabledOption = computed(() => params.enabled?.value === null || params.enabled?.value)
        const isEnabled = computed(() => enabledOption.value && !forceDisabled.value)

        type OnResultCallback = (result: T["_result"]) => void;

        const callbacks: {
            onResult: OnResultCallback[];
        } = {
            onResult: [],
        };

        const onResult = (callback: (result: T["_result"]) => void) => {
            callbacks.onResult.push(callback);
        };

        const runCallbacks = (name: keyof typeof callbacks, ...args: any[]) => {
            switch (name) {
                case "onResult":
                    if (_params.onResult)
                        _params.onResult(args[0])
                    break;
            }
            callbacks[name].forEach((callback) => {
                callback.apply(null, args as any);
            });
        };

        const setEmptyResult = () => {
            if (result.value) {
                if (Array.isArray(result.value)) {
                    result.value = []
                } else {
                    result.value = null
                }
            }
        }

        const clearResult = () => {
            result.value = undefined
        }

        const invalidateCache = () => {
            resolvedAt.value = 0
        }

        const queryInternal: T["run"] = async (variables, fetchParams: GqlRequestFetchParams = {}) => {


            runCount.value++

            if (!fetchParams.refetch) {
                if (runCount.value === 1) {
                    const initial = getInitialResultValue()
                    if (initial !== null) {
                        status.value = 'resolved'
                        result.value = initial
                        resolvedAt.value = Date.now()
                    }
                }

                if (_params.cache && resolvedAt.value && result.value) {
                    if (typeof _params.cache === "number") {
                        const age = Date.now() - resolvedAt.value
                        console.log('age', age)
                        if (age < _params.cache * 1000) {
                            runCallbacks("onResult", result.value);
                            return result.value
                        }
                    }
                }
            }

            let res: any;

            const query = generator.build(variables);

            startedAt.value = Date.now();
            loading.value = true;
            status.value = 'processing'

            try {
                res = await queryWrapped(query, _params, context);
                loaded.value = true;
                result.value = res as T['_result'];
                runCallbacks("onResult", res);
                resolvedAt.value = Date.now()
                status.value = 'resolved'
            } catch (e) {
                status.value = 'rejected'
                console.log('reject', e)
            } finally {
                loading.value = false;
                finishedAt.value = Date.now();
            }
            return res;
        };

        let queryProxy: any;

        if (debounced) {
            queryProxy = debounce(queryInternal, debounced);
        }

        if (throttled) {
            queryProxy = throttle(queryInternal, throttled);
        }

        const query: T["run"] = async (variables = null, fetchParams: GqlRequestFetchParams = {}) => {
            const _variables = variables || toValue(variablesRef) || {}
            return !fetchParams.immediate && queryProxy
                ? queryProxy(_variables, fetchParams)
                : queryInternal(_variables, fetchParams);
        };

        if (isEnabled.value) {
            query()
        }

        if (params.pollingInterval) {
            setInterval(() => {
                query(null, {refetch: true})
            }, 2000)
        }

        watch(variablesRef, () => {
            console.log('watch variables')
            if (isEnabled.value) {
                query()
            }
        })

        return {
            startTimestamp: startedAt,
            finishTimestamp: finishedAt,
            query,
            loading,
            loaded,
            result,
            onResult,
            status,
            isEmptyResult,
            clearResult,
            invalidateCache,
            setEmptyResult
        };
    };

    const useLazyQuery = <T extends GqlQueryGenerator<any, any>>(
        generator: T,
        params: GqlRequestQueryParams<T['_variables'], T['_result']> = {},
        context: RequestContext = getGlobalRequestContext()
    ) => {
        return useQuery(generator, {
            ...params,
            lazy: true,
        });
    };

    return {
        client,
        query,
        queryWrapped,
        mutation,
        mutationWrapped,
        useMutation,
        useQuery,
        useLazyQuery,
        processing,
        responseSelection,
    };
});
