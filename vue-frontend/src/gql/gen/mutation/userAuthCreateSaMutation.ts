import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationUserAuthCreateSaArgs} from "../schema"

export type MutationUserAuthCreateSaVars = MutationUserAuthCreateSaArgs

export function userAuthCreateSaMutation(mutation: MutationGenqlSelection['user_auth_create_sa'] | null) {            
    return {
      build: (variables: MutationUserAuthCreateSaVars) => genqlBuilder.build(mutation ? {user_auth_create_sa: mutation} : {__scalar: true}, {user_auth_create_sa: variables}),  
      mutate: (variables: MutationUserAuthCreateSaVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserAuthCreateSaVars,
      _result: {} as Response,
    }
}

export type MutationUserAuthCreateSa = {
  builder: (variables: MutationUserAuthCreateSaVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_auth_create_sa'],
  variables: MutationUserAuthCreateSaVars,
  result: Response
}

export default userAuthCreateSaMutation