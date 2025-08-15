import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleClientCardFetchArgs} from "../schema"

export type QuerySaleClientCardFetchVars = QuerySaleClientCardFetchArgs

export type SaleClientCardFetchResult = Query['sale_client_card_fetch']

export function saleClientCardFetchQuery(query: QueryGenqlSelection['sale_client_card_fetch'] | null) {
      
    const build = (variables: QuerySaleClientCardFetchVars) => genqlBuilder.build(query ? {sale_client_card_fetch: query} : {__scalar: true}, {sale_client_card_fetch: variables})
    const run = (variables: QuerySaleClientCardFetchVars | null = null): Promise<SaleClientCardFetchResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleClientCardFetchVars,
      _result: {} as SaleClientCardFetchResult,
    }
}

export default saleClientCardFetchQuery