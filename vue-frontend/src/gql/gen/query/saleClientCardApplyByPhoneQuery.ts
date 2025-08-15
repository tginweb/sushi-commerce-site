import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleClientCardApplyByPhoneArgs} from "../schema"

export type QuerySaleClientCardApplyByPhoneVars = QuerySaleClientCardApplyByPhoneArgs

export type SaleClientCardApplyByPhoneResult = Query['sale_client_card_apply_by_phone']

export function saleClientCardApplyByPhoneQuery(query: QueryGenqlSelection['sale_client_card_apply_by_phone'] | null) {
      
    const build = (variables: QuerySaleClientCardApplyByPhoneVars) => genqlBuilder.build(query ? {sale_client_card_apply_by_phone: query} : {__scalar: true}, {sale_client_card_apply_by_phone: variables})
    const run = (variables: QuerySaleClientCardApplyByPhoneVars | null = null): Promise<SaleClientCardApplyByPhoneResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleClientCardApplyByPhoneVars,
      _result: {} as SaleClientCardApplyByPhoneResult,
    }
}

export default saleClientCardApplyByPhoneQuery