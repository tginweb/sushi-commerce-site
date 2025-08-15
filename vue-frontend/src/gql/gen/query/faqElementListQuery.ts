import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryFaqElementListArgs} from "../schema"

export type QueryFaqElementListVars = QueryFaqElementListArgs

export type FaqElementListResult = Query['faq_element_list']

export function faqElementListQuery(query: QueryGenqlSelection['faq_element_list'] | null) {
      
    const build = (variables: QueryFaqElementListVars) => genqlBuilder.build(query ? {faq_element_list: query} : {__scalar: true}, {faq_element_list: variables})
    const run = (variables: QueryFaqElementListVars | null = null): Promise<FaqElementListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryFaqElementListVars,
      _result: {} as FaqElementListResult,
    }
}

export default faqElementListQuery