import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryMenuMenusVars = {}

export type MenuMenusResult = Query['menu_menus']

export function menuMenusQuery(query: QueryGenqlSelection['menu_menus'] | null) {
      
    const build = (variables: QueryMenuMenusVars) => genqlBuilder.build(query ? {menu_menus: query} : {__scalar: true}, {menu_menus: variables})
    const run = (variables: QueryMenuMenusVars | null = null): Promise<MenuMenusResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryMenuMenusVars,
      _result: {} as MenuMenusResult,
    }
}

export default menuMenusQuery