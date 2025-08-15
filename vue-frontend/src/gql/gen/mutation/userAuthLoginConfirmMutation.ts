import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, UserAuthLoginConfirmResult} from "../schema"

import {MutationUserAuthLoginConfirmArgs} from "../schema"

export type MutationUserAuthLoginConfirmVars = MutationUserAuthLoginConfirmArgs

export function userAuthLoginConfirmMutation(mutation: MutationGenqlSelection['user_auth_login_confirm'] | null) {            
    return {
      build: (variables: MutationUserAuthLoginConfirmVars) => genqlBuilder.build(mutation ? {user_auth_login_confirm: mutation} : {__scalar: true}, {user_auth_login_confirm: variables}),  
      mutate: (variables: MutationUserAuthLoginConfirmVars) => ({} as Promise<UserAuthLoginConfirmResult>),
      _variables: {} as MutationUserAuthLoginConfirmVars,
      _result: {} as UserAuthLoginConfirmResult,
    }
}

export type MutationUserAuthLoginConfirm = {
  builder: (variables: MutationUserAuthLoginConfirmVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_auth_login_confirm'],
  variables: MutationUserAuthLoginConfirmVars,
  result: UserAuthLoginConfirmResult
}

export default userAuthLoginConfirmMutation