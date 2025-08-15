import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleVorderCurrentArgs} from "../schema"

export type QuerySaleVorderCurrentVars = QuerySaleVorderCurrentArgs

export type SaleVorderCurrentResult = Query['sale_vorder_current']

export function saleVorderCurrentQuery(query: QueryGenqlSelection['sale_vorder_current'] | null) {
      
    const build = (variables: QuerySaleVorderCurrentVars) => genqlBuilder.build(query ? {sale_vorder_current: query} : {__scalar: true}, {sale_vorder_current: variables})
    const run = (variables: QuerySaleVorderCurrentVars | null = null): Promise<SaleVorderCurrentResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleVorderCurrentVars,
      _result: {} as SaleVorderCurrentResult,
    }
}

export default saleVorderCurrentQuery