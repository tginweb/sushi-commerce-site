import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryOfferSingleArgs} from "../schema"

export type QueryOfferSingleVars = QueryOfferSingleArgs

export type OfferSingleResult = Query['offer_single']

export function offerSingleQuery(query: QueryGenqlSelection['offer_single'] | null) {
      
    const build = (variables: QueryOfferSingleVars) => genqlBuilder.build(query ? {offer_single: query} : {__scalar: true}, {offer_single: variables})
    const run = (variables: QueryOfferSingleVars | null = null): Promise<OfferSingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryOfferSingleVars,
      _result: {} as OfferSingleResult,
    }
}

export default offerSingleQuery