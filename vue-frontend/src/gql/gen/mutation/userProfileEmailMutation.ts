import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationUserProfileEmailArgs} from "../schema"

export type MutationUserProfileEmailVars = MutationUserProfileEmailArgs

export function userProfileEmailMutation(mutation: MutationGenqlSelection['user_profile_email'] | null) {            
    return {
      build: (variables: MutationUserProfileEmailVars) => genqlBuilder.build(mutation ? {user_profile_email: mutation} : {__scalar: true}, {user_profile_email: variables}),  
      mutate: (variables: MutationUserProfileEmailVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserProfileEmailVars,
      _result: {} as Response,
    }
}

export type MutationUserProfileEmail = {
  builder: (variables: MutationUserProfileEmailVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_profile_email'],
  variables: MutationUserProfileEmailVars,
  result: Response
}

export default userProfileEmailMutation