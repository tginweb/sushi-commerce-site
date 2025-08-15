import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleClientCardRecordsetArgs} from "../schema"

export type QuerySaleClientCardRecordsetVars = QuerySaleClientCardRecordsetArgs

export type SaleClientCardRecordsetResult = Query['sale_client_card_recordset']

export function saleClientCardRecordsetQuery(query: QueryGenqlSelection['sale_client_card_recordset'] | null) {
      
    const build = (variables: QuerySaleClientCardRecordsetVars) => genqlBuilder.build(query ? {sale_client_card_recordset: query} : {__scalar: true}, {sale_client_card_recordset: variables})
    const run = (variables: QuerySaleClientCardRecordsetVars | null = null): Promise<SaleClientCardRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleClientCardRecordsetVars,
      _result: {} as SaleClientCardRecordsetResult,
    }
}

export default saleClientCardRecordsetQuery