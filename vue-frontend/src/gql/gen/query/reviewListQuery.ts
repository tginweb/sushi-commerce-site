import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryReviewListArgs} from "../schema"

export type QueryReviewListVars = QueryReviewListArgs

export type ReviewListResult = Query['review_list']

export function reviewListQuery(query: QueryGenqlSelection['review_list'] | null) {
      
    const build = (variables: QueryReviewListVars) => genqlBuilder.build(query ? {review_list: query} : {__scalar: true}, {review_list: variables})
    const run = (variables: QueryReviewListVars | null = null): Promise<ReviewListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryReviewListVars,
      _result: {} as ReviewListResult,
    }
}

export default reviewListQuery