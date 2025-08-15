import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryOfferCommonListVars = {}

export type OfferCommonListResult = Query['offer_common_list']

export function offerCommonListQuery(query: QueryGenqlSelection['offer_common_list'] | null) {
      
    const build = (variables: QueryOfferCommonListVars) => genqlBuilder.build(query ? {offer_common_list: query} : {__scalar: true}, {offer_common_list: variables})
    const run = (variables: QueryOfferCommonListVars | null = null): Promise<OfferCommonListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryOfferCommonListVars,
      _result: {} as OfferCommonListResult,
    }
}

export default offerCommonListQuery