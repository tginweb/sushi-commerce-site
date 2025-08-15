import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationUserProfileBirthdayArgs} from "../schema"

export type MutationUserProfileBirthdayVars = MutationUserProfileBirthdayArgs

export function userProfileBirthdayMutation(mutation: MutationGenqlSelection['user_profile_birthday'] | null) {            
    return {
      build: (variables: MutationUserProfileBirthdayVars) => genqlBuilder.build(mutation ? {user_profile_birthday: mutation} : {__scalar: true}, {user_profile_birthday: variables}),  
      mutate: (variables: MutationUserProfileBirthdayVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserProfileBirthdayVars,
      _result: {} as Response,
    }
}

export type MutationUserProfileBirthday = {
  builder: (variables: MutationUserProfileBirthdayVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_profile_birthday'],
  variables: MutationUserProfileBirthdayVars,
  result: Response
}

export default userProfileBirthdayMutation