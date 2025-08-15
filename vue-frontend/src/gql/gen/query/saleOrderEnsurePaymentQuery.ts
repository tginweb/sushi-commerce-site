import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleOrderEnsurePaymentArgs} from "../schema"

export type QuerySaleOrderEnsurePaymentVars = QuerySaleOrderEnsurePaymentArgs

export type SaleOrderEnsurePaymentResult = Query['sale_order_ensure_payment']

export function saleOrderEnsurePaymentQuery(query: QueryGenqlSelection['sale_order_ensure_payment'] | null) {
      
    const build = (variables: QuerySaleOrderEnsurePaymentVars) => genqlBuilder.build(query ? {sale_order_ensure_payment: query} : {__scalar: true}, {sale_order_ensure_payment: variables})
    const run = (variables: QuerySaleOrderEnsurePaymentVars | null = null): Promise<SaleOrderEnsurePaymentResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleOrderEnsurePaymentVars,
      _result: {} as SaleOrderEnsurePaymentResult,
    }
}

export default saleOrderEnsurePaymentQuery