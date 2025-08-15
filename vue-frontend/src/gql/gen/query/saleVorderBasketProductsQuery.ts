import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QuerySaleVorderBasketProductsVars = {}

export type SaleVorderBasketProductsResult = Query['sale_vorder_basket_products']

export function saleVorderBasketProductsQuery(query: QueryGenqlSelection['sale_vorder_basket_products'] | null) {
      
    const build = (variables: QuerySaleVorderBasketProductsVars) => genqlBuilder.build(query ? {sale_vorder_basket_products: query} : {__scalar: true}, {sale_vorder_basket_products: variables})
    const run = (variables: QuerySaleVorderBasketProductsVars | null = null): Promise<SaleVorderBasketProductsResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleVorderBasketProductsVars,
      _result: {} as SaleVorderBasketProductsResult,
    }
}

export default saleVorderBasketProductsQuery