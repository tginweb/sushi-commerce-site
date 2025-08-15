import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QuerySearchSuggestionsPopularVars = {}

export type SearchSuggestionsPopularResult = Query['search_suggestions_popular']

export function searchSuggestionsPopularQuery(query: QueryGenqlSelection['search_suggestions_popular'] | null) {
      
    const build = (variables: QuerySearchSuggestionsPopularVars) => genqlBuilder.build(query ? {search_suggestions_popular: query} : {__scalar: true}, {search_suggestions_popular: variables})
    const run = (variables: QuerySearchSuggestionsPopularVars | null = null): Promise<SearchSuggestionsPopularResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySearchSuggestionsPopularVars,
      _result: {} as SearchSuggestionsPopularResult,
    }
}

export default searchSuggestionsPopularQuery