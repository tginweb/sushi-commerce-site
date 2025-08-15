import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductAllArgs} from "../schema"

export type QueryCatalogProductAllVars = QueryCatalogProductAllArgs

export type CatalogProductAllResult = Query['catalog_product_all']

export function catalogProductAllQuery(query: QueryGenqlSelection['catalog_product_all'] | null) {
      
    const build = (variables: QueryCatalogProductAllVars) => genqlBuilder.build(query ? {catalog_product_all: query} : {__scalar: true}, {catalog_product_all: variables})
    const run = (variables: QueryCatalogProductAllVars | null = null): Promise<CatalogProductAllResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductAllVars,
      _result: {} as CatalogProductAllResult,
    }
}

export default catalogProductAllQuery