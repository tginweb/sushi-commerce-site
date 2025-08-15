import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductFavList2Args} from "../schema"

export type QueryCatalogProductFavList2Vars = QueryCatalogProductFavList2Args

export type CatalogProductFavList2Result = Query['catalog_product_fav_list2']

export function catalogProductFavList2Query(query: QueryGenqlSelection['catalog_product_fav_list2'] | null) {
      
    const build = (variables: QueryCatalogProductFavList2Vars) => genqlBuilder.build(query ? {catalog_product_fav_list2: query} : {__scalar: true}, {catalog_product_fav_list2: variables})
    const run = (variables: QueryCatalogProductFavList2Vars | null = null): Promise<CatalogProductFavList2Result | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductFavList2Vars,
      _result: {} as CatalogProductFavList2Result,
    }
}

export default catalogProductFavList2Query