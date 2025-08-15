import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QuerySaleOrderStatusesVars = {}

export type SaleOrderStatusesResult = Query['sale_order_statuses']

export function saleOrderStatusesQuery(query: QueryGenqlSelection['sale_order_statuses'] | null) {
      
    const build = (variables: QuerySaleOrderStatusesVars) => genqlBuilder.build(query ? {sale_order_statuses: query} : {__scalar: true}, {sale_order_statuses: variables})
    const run = (variables: QuerySaleOrderStatusesVars | null = null): Promise<SaleOrderStatusesResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleOrderStatusesVars,
      _result: {} as SaleOrderStatusesResult,
    }
}

export default saleOrderStatusesQuery