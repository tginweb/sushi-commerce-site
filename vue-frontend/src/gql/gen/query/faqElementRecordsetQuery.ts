import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryFaqElementRecordsetArgs} from "../schema"

export type QueryFaqElementRecordsetVars = QueryFaqElementRecordsetArgs

export type FaqElementRecordsetResult = Query['faq_element_recordset']

export function faqElementRecordsetQuery(query: QueryGenqlSelection['faq_element_recordset'] | null) {
      
    const build = (variables: QueryFaqElementRecordsetVars) => genqlBuilder.build(query ? {faq_element_recordset: query} : {__scalar: true}, {faq_element_recordset: variables})
    const run = (variables: QueryFaqElementRecordsetVars | null = null): Promise<FaqElementRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryFaqElementRecordsetVars,
      _result: {} as FaqElementRecordsetResult,
    }
}

export default faqElementRecordsetQuery