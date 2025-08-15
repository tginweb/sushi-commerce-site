import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationUserProfileNameArgs} from "../schema"

export type MutationUserProfileNameVars = MutationUserProfileNameArgs

export function userProfileNameMutation(mutation: MutationGenqlSelection['user_profile_name'] | null) {            
    return {
      build: (variables: MutationUserProfileNameVars) => genqlBuilder.build(mutation ? {user_profile_name: mutation} : {__scalar: true}, {user_profile_name: variables}),  
      mutate: (variables: MutationUserProfileNameVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserProfileNameVars,
      _result: {} as Response,
    }
}

export type MutationUserProfileName = {
  builder: (variables: MutationUserProfileNameVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_profile_name'],
  variables: MutationUserProfileNameVars,
  result: Response
}

export default userProfileNameMutation