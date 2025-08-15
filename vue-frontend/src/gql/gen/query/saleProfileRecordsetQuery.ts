import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleProfileRecordsetArgs} from "../schema"

export type QuerySaleProfileRecordsetVars = QuerySaleProfileRecordsetArgs

export type SaleProfileRecordsetResult = Query['sale_profile_recordset']

export function saleProfileRecordsetQuery(query: QueryGenqlSelection['sale_profile_recordset'] | null) {
      
    const build = (variables: QuerySaleProfileRecordsetVars) => genqlBuilder.build(query ? {sale_profile_recordset: query} : {__scalar: true}, {sale_profile_recordset: variables})
    const run = (variables: QuerySaleProfileRecordsetVars | null = null): Promise<SaleProfileRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleProfileRecordsetVars,
      _result: {} as SaleProfileRecordsetResult,
    }
}

export default saleProfileRecordsetQuery