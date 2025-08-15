import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationUserProfileChildArgs} from "../schema"

export type MutationUserProfileChildVars = MutationUserProfileChildArgs

export function userProfileChildMutation(mutation: MutationGenqlSelection['user_profile_child'] | null) {            
    return {
      build: (variables: MutationUserProfileChildVars) => genqlBuilder.build(mutation ? {user_profile_child: mutation} : {__scalar: true}, {user_profile_child: variables}),  
      mutate: (variables: MutationUserProfileChildVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserProfileChildVars,
      _result: {} as Response,
    }
}

export type MutationUserProfileChild = {
  builder: (variables: MutationUserProfileChildVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_profile_child'],
  variables: MutationUserProfileChildVars,
  result: Response
}

export default userProfileChildMutation