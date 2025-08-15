import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

export type MutationUserLogoutVars = {}

export function userLogoutMutation(mutation: MutationGenqlSelection['user_logout'] | null) {            
    return {
      build: (variables: MutationUserLogoutVars) => genqlBuilder.build(mutation ? {user_logout: mutation} : {__scalar: true}, {user_logout: variables}),  
      mutate: (variables: MutationUserLogoutVars) => ({} as Promise<Response>),
      _variables: {} as MutationUserLogoutVars,
      _result: {} as Response,
    }
}

export type MutationUserLogout = {
  builder: (variables: MutationUserLogoutVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['user_logout'],
  variables: MutationUserLogoutVars,
  result: Response
}

export default userLogoutMutation