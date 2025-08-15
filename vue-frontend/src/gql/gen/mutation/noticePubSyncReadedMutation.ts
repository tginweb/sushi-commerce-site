import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, NoticePubSyncReadedResult} from "../schema"

import {MutationNoticePubSyncReadedArgs} from "../schema"

export type MutationNoticePubSyncReadedVars = MutationNoticePubSyncReadedArgs

export function noticePubSyncReadedMutation(mutation: MutationGenqlSelection['notice_pub_sync_readed'] | null) {            
    return {
      build: (variables: MutationNoticePubSyncReadedVars) => genqlBuilder.build(mutation ? {notice_pub_sync_readed: mutation} : {__scalar: true}, {notice_pub_sync_readed: variables}),  
      mutate: (variables: MutationNoticePubSyncReadedVars) => ({} as Promise<NoticePubSyncReadedResult>),
      _variables: {} as MutationNoticePubSyncReadedVars,
      _result: {} as NoticePubSyncReadedResult,
    }
}

export type MutationNoticePubSyncReaded = {
  builder: (variables: MutationNoticePubSyncReadedVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['notice_pub_sync_readed'],
  variables: MutationNoticePubSyncReadedVars,
  result: NoticePubSyncReadedResult
}

export default noticePubSyncReadedMutation