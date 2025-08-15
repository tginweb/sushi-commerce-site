import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QuerySearchSuggestionsArgs} from "../schema"

export type QuerySearchSuggestionsVars = QuerySearchSuggestionsArgs

export type SearchSuggestionsResult = Query['search_suggestions']

export function searchSuggestionsQuery(query: QueryGenqlSelection['search_suggestions'] | null) {
      
    const build = (variables: QuerySearchSuggestionsVars) => genqlBuilder.build(query ? {search_suggestions: query} : {__scalar: true}, {search_suggestions: variables})
    const run = (variables: QuerySearchSuggestionsVars | null = null): Promise<SearchSuggestionsResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySearchSuggestionsVars,
      _result: {} as SearchSuggestionsResult,
    }
}

export default searchSuggestionsQuery