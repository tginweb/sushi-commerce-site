import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleProfileListArgs} from "../schema"

export type QuerySaleProfileListVars = QuerySaleProfileListArgs

export type SaleProfileListResult = Query['sale_profile_list']

export function saleProfileListQuery(query: QueryGenqlSelection['sale_profile_list'] | null) {
      
    const build = (variables: QuerySaleProfileListVars) => genqlBuilder.build(query ? {sale_profile_list: query} : {__scalar: true}, {sale_profile_list: variables})
    const run = (variables: QuerySaleProfileListVars | null = null): Promise<SaleProfileListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleProfileListVars,
      _result: {} as SaleProfileListResult,
    }
}

export default saleProfileListQuery