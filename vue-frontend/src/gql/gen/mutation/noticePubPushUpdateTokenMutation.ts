import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationNoticePubPushUpdateTokenArgs} from "../schema"

export type MutationNoticePubPushUpdateTokenVars = MutationNoticePubPushUpdateTokenArgs

export function noticePubPushUpdateTokenMutation(mutation: MutationGenqlSelection['notice_pub_push_update_token'] | null) {            
    return {
      build: (variables: MutationNoticePubPushUpdateTokenVars) => genqlBuilder.build(mutation ? {notice_pub_push_update_token: mutation} : {__scalar: true}, {notice_pub_push_update_token: variables}),  
      mutate: (variables: MutationNoticePubPushUpdateTokenVars) => ({} as Promise<Response>),
      _variables: {} as MutationNoticePubPushUpdateTokenVars,
      _result: {} as Response,
    }
}

export type MutationNoticePubPushUpdateToken = {
  builder: (variables: MutationNoticePubPushUpdateTokenVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['notice_pub_push_update_token'],
  variables: MutationNoticePubPushUpdateTokenVars,
  result: Response
}

export default noticePubPushUpdateTokenMutation