import {computed, reactive, Ref, ref, watch} from "vue";
import gql from "graphql-tag";
import {useQuery} from "@vue/apollo-composable";
import {OperationVariables} from "@apollo/client";
import {
    TEntity,
    TEntityRecordsetResult,
    TUseEntityQueryFilter,
    TUseEntityQueryNav,
    TUseEntityRecordsetQueryParams,
    TUseEntityRecordsetQueryReturn
} from "../types";
import {generateQueryOp} from "@/gql/gen";

export function useEntityRecordsetQuery<
    TEntityType extends TEntity,
    TResult extends TEntityRecordsetResult<TEntityType>,
    TVariables extends OperationVariables
>(params: TUseEntityRecordsetQueryParams<TEntityType, TResult, TVariables>): TUseEntityRecordsetQueryReturn<TEntityType, TResult, TVariables> {

    const {
        queryBuilder,
        query,
        queryAlias,
        variables: initVariables = {},
        options = {},
        filter: initFilter = {},
        nav: initNav = {}
    } = params

    let isFirstRun = true

    const filter = ref<TUseEntityQueryFilter>(initFilter)

    const nav = ref<TUseEntityQueryNav>({
        page: 10,
        limit: 20,
        //sortBy: [['id', 'DESC']],
        //rowsNumber: 200,
        ...initNav
    })

    const variables = computed<any>(() => {
        let data: any = {
            filter: filter.value,
            ...nav.value,
            ...initVariables
        }
        if (isFirstRun) {
            data.withTable = 'admin'
        }
        return data
    })

    const queryOp = computed<any>(() => {
        return generateQueryOp(queryBuilder(null, variables.value))
    })

    const queryOpGql = computed<any>(() => {
        return gql(queryOp.value.query)
    })

    const queryOpVars = computed<any>(() => {
        return queryOp.value.variables
    })

    const useResult = useQuery<TResult, TVariables>(
        queryOpGql,
        queryOpVars,
        {
            ...options,
            fetchPolicy: 'no-cache'
        }
    )

    const {result, onResult} = useResult

    const content = computed<TResult[keyof TResult] | null>(() => {
        return result.value ? result.value[queryAlias as keyof TResult] : null;
    })

    watch(content, () => {

    })

    const rows = computed<TEntityType[]>(() => {
        return content.value ? content.value.nodes : []
    })

    const navToPagination = () => {
        return {}
    }


    const pagination = reactive(navToPagination())

    watch(nav, () => {
        //pagination.value = navToPagination()
        console.log('watch nav', nav)
    })

    watch(pagination, () => {
        console.log('watch pagination', pagination)
    })

    const resetFilters = () => {

    }

    const updateFilter = (newData: any) => {
        filter.value = newData
        console.log('updateFilter', newData)
    }

    const selected = ref<TEntityType[]>([])

    const selectedIds = computed(() => {
        return selected.value.map(item => item.ID)
    })

    return {
        ...useResult,
        content,
        rows,
        filter,
        nav,
        pagination,
        updateFilter,
        resetFilters,
        selected: selected as Ref<TEntityType[]>,
        selectedIds,
    }
}

export function useEntityRecordsetQueryGenerator<
    TEntityType extends TEntity,
    TResult extends TEntityRecordsetResult<TEntityType>,
    TVariables extends OperationVariables
>(params: TUseEntityRecordsetQueryParams<TEntityType, TResult, TVariables>): (useParams: Partial<TUseEntityRecordsetQueryParams<TEntityType, TResult, TVariables>>) => TUseEntityRecordsetQueryReturn<TEntityType, TResult, TVariables> {
    return (useParams) => useEntityRecordsetQuery({
        ...useParams,
        ...params,
    })
}
