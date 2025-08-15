import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationUserProfileSaveArgs} from "../schema"

export type MutationUserProfileSaveVars = MutationUserProfileSaveArgs

export function userProfileSaveMutation(mutation: MutationGenqlSelection['user_profile_save'] | null) {            
    return {
      build: (variables: MutationUserProfileSaveVars) => genqlBuilder.build(mutation ? {user_profile_save: mutation} : {__scalar: true}, {user_profile_save: variables}),  
      mutate: (variables: MutationUserProfileSaveVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserProfileSaveVars,
      _result: {} as Response,
    }
}

export type MutationUserProfileSave = {
  builder: (variables: MutationUserProfileSaveVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_profile_save'],
  variables: MutationUserProfileSaveVars,
  result: Response
}

export default userProfileSaveMutation