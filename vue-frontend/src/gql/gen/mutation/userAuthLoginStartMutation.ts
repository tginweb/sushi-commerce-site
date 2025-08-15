import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, UserAuthLoginStartResult} from "../schema"

import {MutationUserAuthLoginStartArgs} from "../schema"

export type MutationUserAuthLoginStartVars = MutationUserAuthLoginStartArgs

export function userAuthLoginStartMutation(mutation: MutationGenqlSelection['user_auth_login_start'] | null) {            
    return {
      build: (variables: MutationUserAuthLoginStartVars) => genqlBuilder.build(mutation ? {user_auth_login_start: mutation} : {__scalar: true}, {user_auth_login_start: variables}),  
      mutate: (variables: MutationUserAuthLoginStartVars) => ({} as Promise<UserAuthLoginStartResult>),
      _variables: {} as MutationUserAuthLoginStartVars,
      _result: {} as UserAuthLoginStartResult,
    }
}

export type MutationUserAuthLoginStart = {
  builder: (variables: MutationUserAuthLoginStartVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_auth_login_start'],
  variables: MutationUserAuthLoginStartVars,
  result: UserAuthLoginStartResult
}

export default userAuthLoginStartMutation