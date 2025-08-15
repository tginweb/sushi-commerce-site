import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, UserAuthLoginRequestResult} from "../schema"

import {MutationUserAuthLoginRequestArgs} from "../schema"

export type MutationUserAuthLoginRequestVars = MutationUserAuthLoginRequestArgs

export function userAuthLoginRequestMutation(mutation: MutationGenqlSelection['user_auth_login_request'] | null) {            
    return {
      build: (variables: MutationUserAuthLoginRequestVars) => genqlBuilder.build(mutation ? {user_auth_login_request: mutation} : {__scalar: true}, {user_auth_login_request: variables}),  
      mutate: (variables: MutationUserAuthLoginRequestVars) => ({} as Promise<UserAuthLoginRequestResult>),
      _variables: {} as MutationUserAuthLoginRequestVars,
      _result: {} as UserAuthLoginRequestResult,
    }
}

export type MutationUserAuthLoginRequest = {
  builder: (variables: MutationUserAuthLoginRequestVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_auth_login_request'],
  variables: MutationUserAuthLoginRequestVars,
  result: UserAuthLoginRequestResult
}

export default userAuthLoginRequestMutation