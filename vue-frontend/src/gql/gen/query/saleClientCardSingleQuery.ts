import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleClientCardSingleArgs} from "../schema"

export type QuerySaleClientCardSingleVars = QuerySaleClientCardSingleArgs

export type SaleClientCardSingleResult = Query['sale_client_card_single']

export function saleClientCardSingleQuery(query: QueryGenqlSelection['sale_client_card_single'] | null) {
      
    const build = (variables: QuerySaleClientCardSingleVars) => genqlBuilder.build(query ? {sale_client_card_single: query} : {__scalar: true}, {sale_client_card_single: variables})
    const run = (variables: QuerySaleClientCardSingleVars | null = null): Promise<SaleClientCardSingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleClientCardSingleVars,
      _result: {} as SaleClientCardSingleResult,
    }
}

export default saleClientCardSingleQuery