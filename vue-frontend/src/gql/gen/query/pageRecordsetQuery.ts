import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryPageRecordsetArgs} from "../schema"

export type QueryPageRecordsetVars = QueryPageRecordsetArgs

export type PageRecordsetResult = Query['page_recordset']

export function pageRecordsetQuery(query: QueryGenqlSelection['page_recordset'] | null) {
      
    const build = (variables: QueryPageRecordsetVars) => genqlBuilder.build(query ? {page_recordset: query} : {__scalar: true}, {page_recordset: variables})
    const run = (variables: QueryPageRecordsetVars | null = null): Promise<PageRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryPageRecordsetVars,
      _result: {} as PageRecordsetResult,
    }
}

export default pageRecordsetQuery