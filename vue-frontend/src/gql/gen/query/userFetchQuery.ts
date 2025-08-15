import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryUserFetchArgs} from "../schema"

export type QueryUserFetchVars = QueryUserFetchArgs

export type UserFetchResult = Query['user_fetch']

export function userFetchQuery(query: QueryGenqlSelection['user_fetch'] | null) {
      
    const build = (variables: QueryUserFetchVars) => genqlBuilder.build(query ? {user_fetch: query} : {__scalar: true}, {user_fetch: variables})
    const run = (variables: QueryUserFetchVars | null = null): Promise<UserFetchResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryUserFetchVars,
      _result: {} as UserFetchResult,
    }
}

export default userFetchQuery