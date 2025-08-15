import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryUserAppClientVars = {}

export type UserAppClientResult = Query['user_app_client']

export function userAppClientQuery(query: QueryGenqlSelection['user_app_client'] | null) {
      
    const build = (variables: QueryUserAppClientVars) => genqlBuilder.build(query ? {user_app_client: query} : {__scalar: true}, {user_app_client: variables})
    const run = (variables: QueryUserAppClientVars | null = null): Promise<UserAppClientResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryUserAppClientVars,
      _result: {} as UserAppClientResult,
    }
}

export default userAppClientQuery