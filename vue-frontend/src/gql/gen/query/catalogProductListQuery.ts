import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductListArgs} from "../schema"

export type QueryCatalogProductListVars = QueryCatalogProductListArgs

export type CatalogProductListResult = Query['catalog_product_list']

export function catalogProductListQuery(query: QueryGenqlSelection['catalog_product_list'] | null) {
      
    const build = (variables: QueryCatalogProductListVars) => genqlBuilder.build(query ? {catalog_product_list: query} : {__scalar: true}, {catalog_product_list: variables})
    const run = (variables: QueryCatalogProductListVars | null = null): Promise<CatalogProductListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductListVars,
      _result: {} as CatalogProductListResult,
    }
}

export default catalogProductListQuery