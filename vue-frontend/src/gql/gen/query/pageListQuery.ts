import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryPageListArgs} from "../schema"

export type QueryPageListVars = QueryPageListArgs

export type PageListResult = Query['page_list']

export function pageListQuery(query: QueryGenqlSelection['page_list'] | null) {
      
    const build = (variables: QueryPageListVars) => genqlBuilder.build(query ? {page_list: query} : {__scalar: true}, {page_list: variables})
    const run = (variables: QueryPageListVars | null = null): Promise<PageListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryPageListVars,
      _result: {} as PageListResult,
    }
}

export default pageListQuery