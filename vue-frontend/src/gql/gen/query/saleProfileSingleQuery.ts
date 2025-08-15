import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleProfileSingleArgs} from "../schema"

export type QuerySaleProfileSingleVars = QuerySaleProfileSingleArgs

export type SaleProfileSingleResult = Query['sale_profile_single']

export function saleProfileSingleQuery(query: QueryGenqlSelection['sale_profile_single'] | null) {
      
    const build = (variables: QuerySaleProfileSingleVars) => genqlBuilder.build(query ? {sale_profile_single: query} : {__scalar: true}, {sale_profile_single: variables})
    const run = (variables: QuerySaleProfileSingleVars | null = null): Promise<SaleProfileSingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleProfileSingleVars,
      _result: {} as SaleProfileSingleResult,
    }
}

export default saleProfileSingleQuery