import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryPageRouteArgs} from "../schema"

export type QueryPageRouteVars = QueryPageRouteArgs

export type PageRouteResult = Query['page_route']

export function pageRouteQuery(query: QueryGenqlSelection['page_route'] | null) {
      
    const build = (variables: QueryPageRouteVars) => genqlBuilder.build(query ? {page_route: query} : {__scalar: true}, {page_route: variables})
    const run = (variables: QueryPageRouteVars | null = null): Promise<PageRouteResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryPageRouteVars,
      _result: {} as PageRouteResult,
    }
}

export default pageRouteQuery