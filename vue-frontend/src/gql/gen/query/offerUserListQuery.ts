import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryOfferUserListVars = {}

export type OfferUserListResult = Query['offer_user_list']

export function offerUserListQuery(query: QueryGenqlSelection['offer_user_list'] | null) {
      
    const build = (variables: QueryOfferUserListVars) => genqlBuilder.build(query ? {offer_user_list: query} : {__scalar: true}, {offer_user_list: variables})
    const run = (variables: QueryOfferUserListVars | null = null): Promise<OfferUserListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryOfferUserListVars,
      _result: {} as OfferUserListResult,
    }
}

export default offerUserListQuery