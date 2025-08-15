import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductRecordsetArgs} from "../schema"

export type QueryCatalogProductRecordsetVars = QueryCatalogProductRecordsetArgs

export type CatalogProductRecordsetResult = Query['catalog_product_recordset']

export function catalogProductRecordsetQuery(query: QueryGenqlSelection['catalog_product_recordset'] | null) {
      
    const build = (variables: QueryCatalogProductRecordsetVars) => genqlBuilder.build(query ? {catalog_product_recordset: query} : {__scalar: true}, {catalog_product_recordset: variables})
    const run = (variables: QueryCatalogProductRecordsetVars | null = null): Promise<CatalogProductRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductRecordsetVars,
      _result: {} as CatalogProductRecordsetResult,
    }
}

export default catalogProductRecordsetQuery