import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryListVars = {}

export type ListResult = Query['list']

export function listQuery(query: QueryGenqlSelection['list'] | null) {
      
    const build = (variables: QueryListVars) => genqlBuilder.build(query ? {list: query} : {__scalar: true}, {list: variables})
    const run = (variables: QueryListVars | null = null): Promise<ListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryListVars,
      _result: {} as ListResult,
    }
}

export default listQuery