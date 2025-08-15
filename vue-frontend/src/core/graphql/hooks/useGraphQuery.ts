import {useGraphql} from "@/core/graphql/service";
import {Query, QueryGenqlSelection} from "@/gql/gen";
import {isRef, onBeforeMount, ref, watch} from "vue";
import {Ref} from "@vue/reactivity";

export type UseGraphQueryParams = {
    skip?: boolean
    returnField?: boolean | keyof Query
    builded?: boolean
}

export type UseGraphQueryReturn<TResult, TVariables> = {
    result: Ref<TResult | null | undefined>
    error: Ref<Error | null | undefined>
    fetch: () => void
}

export function useGraphQuery<TResult, TVariables extends any = any>(
    queryBuilder: (variables: TVariables) => any | QueryGenqlSelection,
    variables: Ref<TVariables> | TVariables | null = null,
    params: UseGraphQueryParams = {}
): UseGraphQueryReturn<TResult, TVariables> {

    const {queryWrapped} = useGraphql()

    const {
        skip = false,
        returnField = true
    } = params

    const error = ref<Error | null | undefined>()

    const result = ref<TResult | null | undefined>()

    const queryGenerate = () => {
        let variablesValue: any = {}
        if (variables) {
            variablesValue = isRef(variables) ? variables.value : variables
        }
        return typeof queryBuilder === 'function' ? queryBuilder(variablesValue) : variablesValue
    }

    const queryObject = ref<any>(queryGenerate())

    watch(() => variables, () => {
        queryObject.value = queryGenerate()
        fetch()
    }, {deep: true})

    const fetch = async () => {
        try {
            const data = await queryWrapped<TResult>(queryObject.value, returnField, true)
            error.value = null
            result.value = data
        } catch (e) {
            error.value = e as Error
            console.log(e)
        }
    }

    onBeforeMount(() => {
        if (!skip) {
            fetch()
        }
    })

    return {
        result,
        error,
        fetch
    }
}

export function useGraphQueryMultiple<TResult, TVariables extends any = any>(
    queryBuilder: (variables: TVariables) => any,
    variables: Ref<TVariables> | TVariables | null = null,
    params: UseGraphQueryParams = {}
): UseGraphQueryReturn<TResult, TVariables> {
    return useGraphQuery(queryBuilder, variables, {
        ...params,
        returnField: false
    })
}
