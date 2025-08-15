import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductSearchArgs} from "../schema"

export type QueryCatalogProductSearchVars = QueryCatalogProductSearchArgs

export type CatalogProductSearchResult = Query['catalog_product_search']

export function catalogProductSearchQuery(query: QueryGenqlSelection['catalog_product_search'] | null) {
      
    const build = (variables: QueryCatalogProductSearchVars) => genqlBuilder.build(query ? {catalog_product_search: query} : {__scalar: true}, {catalog_product_search: variables})
    const run = (variables: QueryCatalogProductSearchVars | null = null): Promise<CatalogProductSearchResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductSearchVars,
      _result: {} as CatalogProductSearchResult,
    }
}

export default catalogProductSearchQuery