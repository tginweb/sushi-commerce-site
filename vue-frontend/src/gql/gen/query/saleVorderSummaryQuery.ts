import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QuerySaleVorderSummaryVars = {}

export type SaleVorderSummaryResult = Query['sale_vorder_summary']

export function saleVorderSummaryQuery(query: QueryGenqlSelection['sale_vorder_summary'] | null) {
      
    const build = (variables: QuerySaleVorderSummaryVars) => genqlBuilder.build(query ? {sale_vorder_summary: query} : {__scalar: true}, {sale_vorder_summary: variables})
    const run = (variables: QuerySaleVorderSummaryVars | null = null): Promise<SaleVorderSummaryResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleVorderSummaryVars,
      _result: {} as SaleVorderSummaryResult,
    }
}

export default saleVorderSummaryQuery