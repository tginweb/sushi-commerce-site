import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCatalogProductSectionListArgs} from "../schema"

export type QueryCatalogProductSectionListVars = QueryCatalogProductSectionListArgs

export type CatalogProductSectionListResult = Query['catalog_product_section_list']

export function catalogProductSectionListQuery(query: QueryGenqlSelection['catalog_product_section_list'] | null) {
      
    const build = (variables: QueryCatalogProductSectionListVars) => genqlBuilder.build(query ? {catalog_product_section_list: query} : {__scalar: true}, {catalog_product_section_list: variables})
    const run = (variables: QueryCatalogProductSectionListVars | null = null): Promise<CatalogProductSectionListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCatalogProductSectionListVars,
      _result: {} as CatalogProductSectionListResult,
    }
}

export default catalogProductSectionListQuery