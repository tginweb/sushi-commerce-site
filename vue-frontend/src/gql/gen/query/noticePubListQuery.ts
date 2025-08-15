import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QueryNoticePubListVars = {}

export type NoticePubListResult = Query['notice_pub_list']

export function noticePubListQuery(query: QueryGenqlSelection['notice_pub_list'] | null) {
      
    const build = (variables: QueryNoticePubListVars) => genqlBuilder.build(query ? {notice_pub_list: query} : {__scalar: true}, {notice_pub_list: variables})
    const run = (variables: QueryNoticePubListVars | null = null): Promise<NoticePubListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryNoticePubListVars,
      _result: {} as NoticePubListResult,
    }
}

export default noticePubListQuery