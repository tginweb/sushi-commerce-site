import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductFavListArgs} from "../schema"

export type QueryCatalogProductFavListVars = QueryCatalogProductFavListArgs

export type CatalogProductFavListResult = Query['catalog_product_fav_list']

export function catalogProductFavListQuery(query: QueryGenqlSelection['catalog_product_fav_list'] | null) {
      
    const build = (variables: QueryCatalogProductFavListVars) => genqlBuilder.build(query ? {catalog_product_fav_list: query} : {__scalar: true}, {catalog_product_fav_list: variables})
    const run = (variables: QueryCatalogProductFavListVars | null = null): Promise<CatalogProductFavListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductFavListVars,
      _result: {} as CatalogProductFavListResult,
    }
}

export default catalogProductFavListQuery