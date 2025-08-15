import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationUserAppClientArgs} from "../schema"

export type MutationUserAppClientVars = MutationUserAppClientArgs

export function userAppClientMutation(mutation: MutationGenqlSelection['user_app_client'] | null) {            
    return {
      build: (variables: MutationUserAppClientVars) => genqlBuilder.build(mutation ? {user_app_client: mutation} : {__scalar: true}, {user_app_client: variables}),  
      mutate: (variables: MutationUserAppClientVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserAppClientVars,
      _result: {} as Response,
    }
}

export type MutationUserAppClient = {
  builder: (variables: MutationUserAppClientVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_app_client'],
  variables: MutationUserAppClientVars,
  result: Response
}

export default userAppClientMutation