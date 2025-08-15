import {defineStore} from "pinia";
import {useTask} from "@/core/hooks/useTask";
import {Arrayable} from "@/core/types";
import toArray from "@/core/util/toArray";
import {ref} from "vue";
import {Query, QueryGenqlSelection} from "@/gql/gen";
import {useGraphql} from "@/core/graphql/service";
import {getTypedEntries} from "@/core/util/getTypedEntries";
import {genqlBuild} from "@/core/graphql/genql/builder";

export type QueryScope = 'app' | 'session' | 'user'

export type QueryScopeOnResult = (data: Query) => void

export type QueryScopeQueries = Record<keyof QueryGenqlSelection, QueryGenqlSelection[keyof QueryGenqlSelection]>

export type QueryScopeResult = {
    [key in keyof QueryGenqlSelection]?: Query[keyof Omit<QueryGenqlSelection, '__fragment' | '__scalar'>]
}

export type QueryGroupName = string

export const useScopeQuery = defineStore("scopeQuery", () => {

    const {queryWrapped} = useGraphql()

    const queriesByScope: {
        [key in QueryScope]?: Record<QueryGroupName, QueryGenqlSelection>
    } = {}

    const groups: Record<string, QueryScopeOnResult[]> = {}

    const fetchedScopes = ref<{
        [key in QueryScope]?: boolean
    }>({})

    const registerScopeQuery = (group: string, scopes: Arrayable<QueryScope>, queries: QueryGenqlSelection, onResult?: QueryScopeOnResult) => {
        toArray(scopes).forEach((scope) => {
            if (!queriesByScope[scope])
                queriesByScope[scope] = {}
            if (queriesByScope[scope]) {
                //@ts-ignore
                queriesByScope[scope][group] = queries
                if (!groups[group]) {
                    groups[group] = []
                }
                //@ts-ignore
                groups[group].push(onResult)
            }
        })
    }

    const fetchScopes = async (scopes: Arrayable<QueryScope>, refetch = false) => {

        const queries: QueryGenqlSelection = {}
        const queriesGroups: {
            [key in keyof QueryGenqlSelection]?: string
        } = {}

        for (const scope of toArray(scopes)) {
            if (!refetch && fetchedScopes.value[scope])
                continue;

            const queryGroups = queriesByScope[scope]

            if (queryGroups) {
                for (const [groupName, groupQueries] of getTypedEntries(queryGroups)) {
                    Object.assign(queries, groupQueries)
                    for (const [queryName, querySelection] of getTypedEntries(groupQueries as QueryScopeQueries)) {
                        queriesGroups[queryName] = groupName
                    }
                }
            }
        }

        if (!Object.keys(queries).length)
            return;

        const result = await queryWrapped(genqlBuild(queries), {
            isMultiple: true
        })

        console.log('fetchScopes', {
            scopes
        })

        for (const scope of toArray(scopes)) {
            fetchedScopes.value[scope] = true
        }

        if (!result)
            return;

        const resultByGroup: Record<QueryGroupName, QueryScopeResult> = {}

        for (const [queryName, queryResult] of getTypedEntries<QueryScopeResult>(result as any)) {

            const groupName = queriesGroups[queryName]

            if (groupName) {
                if (!resultByGroup[groupName])
                    resultByGroup[groupName] = {}
                //@ts-ignore
                resultByGroup[groupName][queryName] = queryResult
            }
        }

        for (const [groupName, groupResults] of getTypedEntries(resultByGroup)) {
            if (groups[groupName]) {
                //@ts-ignore
                for (const groupListener of groups[groupName]) {
                    await groupListener(groupResults as any)
                }
            }
        }
    }

    const fetchScopesByRoute = async (to: any, requiredScopes?: Arrayable<QueryScope>) => {
        if (requiredScopes) {
            await fetchScopes(requiredScopes)
        }
    }

    const fetchScopesTask = useTask(fetchScopes)

    return {
        fetchedScopes,
        registerScopeQuery,
        fetchScopesByRoute,
        fetchScopes,
        fetchScopesTask
    }
})
