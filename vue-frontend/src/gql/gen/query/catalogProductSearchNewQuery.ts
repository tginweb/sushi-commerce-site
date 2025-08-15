import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductSearchNewArgs} from "../schema"

export type QueryCatalogProductSearchNewVars = QueryCatalogProductSearchNewArgs

export type CatalogProductSearchNewResult = Query['catalog_product_search_new']

export function catalogProductSearchNewQuery(query: QueryGenqlSelection['catalog_product_search_new'] | null) {
      
    const build = (variables: QueryCatalogProductSearchNewVars) => genqlBuilder.build(query ? {catalog_product_search_new: query} : {__scalar: true}, {catalog_product_search_new: variables})
    const run = (variables: QueryCatalogProductSearchNewVars | null = null): Promise<CatalogProductSearchNewResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductSearchNewVars,
      _result: {} as CatalogProductSearchNewResult,
    }
}

export default catalogProductSearchNewQuery