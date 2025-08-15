import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryPageSingleArgs} from "../schema"

export type QueryPageSingleVars = QueryPageSingleArgs

export type PageSingleResult = Query['page_single']

export function pageSingleQuery(query: QueryGenqlSelection['page_single'] | null) {
      
    const build = (variables: QueryPageSingleVars) => genqlBuilder.build(query ? {page_single: query} : {__scalar: true}, {page_single: variables})
    const run = (variables: QueryPageSingleVars | null = null): Promise<PageSingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryPageSingleVars,
      _result: {} as PageSingleResult,
    }
}

export default pageSingleQuery