import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryEntityInfoListVars = {}

export type EntityInfoListResult = Query['entity_info_list']

export function entityInfoListQuery(query: QueryGenqlSelection['entity_info_list'] | null) {
      
    const build = (variables: QueryEntityInfoListVars) => genqlBuilder.build(query ? {entity_info_list: query} : {__scalar: true}, {entity_info_list: variables})
    const run = (variables: QueryEntityInfoListVars | null = null): Promise<EntityInfoListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryEntityInfoListVars,
      _result: {} as EntityInfoListResult,
    }
}

export default entityInfoListQuery