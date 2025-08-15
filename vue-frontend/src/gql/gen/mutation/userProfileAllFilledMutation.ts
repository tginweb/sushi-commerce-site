import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

export type MutationUserProfileAllFilledVars = {}

export function userProfileAllFilledMutation(mutation: MutationGenqlSelection['user_profile_all_filled'] | null) {            
    return {
      build: (variables: MutationUserProfileAllFilledVars) => genqlBuilder.build(mutation ? {user_profile_all_filled: mutation} : {__scalar: true}, {user_profile_all_filled: variables}),  
      mutate: (variables: MutationUserProfileAllFilledVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserProfileAllFilledVars,
      _result: {} as Response,
    }
}

export type MutationUserProfileAllFilled = {
  builder: (variables: MutationUserProfileAllFilledVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_profile_all_filled'],
  variables: MutationUserProfileAllFilledVars,
  result: Response
}

export default userProfileAllFilledMutation