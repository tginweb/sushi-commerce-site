import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleClientCardListArgs} from "../schema"

export type QuerySaleClientCardListVars = QuerySaleClientCardListArgs

export type SaleClientCardListResult = Query['sale_client_card_list']

export function saleClientCardListQuery(query: QueryGenqlSelection['sale_client_card_list'] | null) {
      
    const build = (variables: QuerySaleClientCardListVars) => genqlBuilder.build(query ? {sale_client_card_list: query} : {__scalar: true}, {sale_client_card_list: variables})
    const run = (variables: QuerySaleClientCardListVars | null = null): Promise<SaleClientCardListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleClientCardListVars,
      _result: {} as SaleClientCardListResult,
    }
}

export default saleClientCardListQuery