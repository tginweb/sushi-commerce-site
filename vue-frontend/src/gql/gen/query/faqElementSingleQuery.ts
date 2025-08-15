import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryFaqElementSingleArgs} from "../schema"

export type QueryFaqElementSingleVars = QueryFaqElementSingleArgs

export type FaqElementSingleResult = Query['faq_element_single']

export function faqElementSingleQuery(query: QueryGenqlSelection['faq_element_single'] | null) {
      
    const build = (variables: QueryFaqElementSingleVars) => genqlBuilder.build(query ? {faq_element_single: query} : {__scalar: true}, {faq_element_single: variables})
    const run = (variables: QueryFaqElementSingleVars | null = null): Promise<FaqElementSingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryFaqElementSingleVars,
      _result: {} as FaqElementSingleResult,
    }
}

export default faqElementSingleQuery