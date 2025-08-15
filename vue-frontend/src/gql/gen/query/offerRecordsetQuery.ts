import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryOfferRecordsetArgs} from "../schema"

export type QueryOfferRecordsetVars = QueryOfferRecordsetArgs

export type OfferRecordsetResult = Query['offer_recordset']

export function offerRecordsetQuery(query: QueryGenqlSelection['offer_recordset'] | null) {
      
    const build = (variables: QueryOfferRecordsetVars) => genqlBuilder.build(query ? {offer_recordset: query} : {__scalar: true}, {offer_recordset: variables})
    const run = (variables: QueryOfferRecordsetVars | null = null): Promise<OfferRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryOfferRecordsetVars,
      _result: {} as OfferRecordsetResult,
    }
}

export default offerRecordsetQuery