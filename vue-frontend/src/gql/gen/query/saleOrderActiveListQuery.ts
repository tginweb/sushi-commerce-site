import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleOrderActiveListArgs} from "../schema"

export type QuerySaleOrderActiveListVars = QuerySaleOrderActiveListArgs

export type SaleOrderActiveListResult = Query['sale_order_active_list']

export function saleOrderActiveListQuery(query: QueryGenqlSelection['sale_order_active_list'] | null) {
      
    const build = (variables: QuerySaleOrderActiveListVars) => genqlBuilder.build(query ? {sale_order_active_list: query} : {__scalar: true}, {sale_order_active_list: variables})
    const run = (variables: QuerySaleOrderActiveListVars | null = null): Promise<SaleOrderActiveListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleOrderActiveListVars,
      _result: {} as SaleOrderActiveListResult,
    }
}

export default saleOrderActiveListQuery