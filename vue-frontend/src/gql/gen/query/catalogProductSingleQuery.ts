import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductSingleArgs} from "../schema"

export type QueryCatalogProductSingleVars = QueryCatalogProductSingleArgs

export type CatalogProductSingleResult = Query['catalog_product_single']

export function catalogProductSingleQuery(query: QueryGenqlSelection['catalog_product_single'] | null) {
      
    const build = (variables: QueryCatalogProductSingleVars) => genqlBuilder.build(query ? {catalog_product_single: query} : {__scalar: true}, {catalog_product_single: variables})
    const run = (variables: QueryCatalogProductSingleVars | null = null): Promise<CatalogProductSingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductSingleVars,
      _result: {} as CatalogProductSingleResult,
    }
}

export default catalogProductSingleQuery