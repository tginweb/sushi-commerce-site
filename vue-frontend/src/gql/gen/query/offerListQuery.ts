import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryOfferListArgs} from "../schema"

export type QueryOfferListVars = QueryOfferListArgs

export type OfferListResult = Query['offer_list']

export function offerListQuery(query: QueryGenqlSelection['offer_list'] | null) {
      
    const build = (variables: QueryOfferListVars) => genqlBuilder.build(query ? {offer_list: query} : {__scalar: true}, {offer_list: variables})
    const run = (variables: QueryOfferListVars | null = null): Promise<OfferListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryOfferListVars,
      _result: {} as OfferListResult,
    }
}

export default offerListQuery