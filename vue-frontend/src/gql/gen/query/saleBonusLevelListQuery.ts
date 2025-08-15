import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySaleBonusLevelListArgs} from "../schema"

export type QuerySaleBonusLevelListVars = QuerySaleBonusLevelListArgs

export type SaleBonusLevelListResult = Query['sale_bonus_level_list']

export function saleBonusLevelListQuery(query: QueryGenqlSelection['sale_bonus_level_list'] | null) {
      
    const build = (variables: QuerySaleBonusLevelListVars) => genqlBuilder.build(query ? {sale_bonus_level_list: query} : {__scalar: true}, {sale_bonus_level_list: variables})
    const run = (variables: QuerySaleBonusLevelListVars | null = null): Promise<SaleBonusLevelListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleBonusLevelListVars,
      _result: {} as SaleBonusLevelListResult,
    }
}

export default saleBonusLevelListQuery