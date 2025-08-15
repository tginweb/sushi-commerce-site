import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryUserSessionVars = {}

export type UserSessionResult = Query['user_session']

export function userSessionQuery(query: QueryGenqlSelection['user_session'] | null) {
      
    const build = (variables: QueryUserSessionVars) => genqlBuilder.build(query ? {user_session: query} : {__scalar: true}, {user_session: variables})
    const run = (variables: QueryUserSessionVars | null = null): Promise<UserSessionResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryUserSessionVars,
      _result: {} as UserSessionResult,
    }
}

export default userSessionQuery