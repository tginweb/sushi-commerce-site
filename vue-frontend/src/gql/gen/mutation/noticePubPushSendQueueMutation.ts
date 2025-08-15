import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

export type MutationNoticePubPushSendQueueVars = {}

export function noticePubPushSendQueueMutation(mutation: MutationGenqlSelection['notice_pub_push_send_queue'] | null) {            
    return {
      build: (variables: MutationNoticePubPushSendQueueVars) => genqlBuilder.build(mutation ? {notice_pub_push_send_queue: mutation} : {__scalar: true}, {notice_pub_push_send_queue: variables}),  
      mutate: (variables: MutationNoticePubPushSendQueueVars) => ({} as Promise<Response>),
      _variables: {} as MutationNoticePubPushSendQueueVars,
      _result: {} as Response,
    }
}

export type MutationNoticePubPushSendQueue = {
  builder: (variables: MutationNoticePubPushSendQueueVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['notice_pub_push_send_queue'],
  variables: MutationNoticePubPushSendQueueVars,
  result: Response
}

export default noticePubPushSendQueueMutation