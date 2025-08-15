import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationSaleClientCardUpdateArgs} from "../schema"

export type MutationSaleClientCardUpdateVars = MutationSaleClientCardUpdateArgs

export function saleClientCardUpdateMutation(mutation: MutationGenqlSelection['sale_client_card_update'] | null) {            
    return {
      build: (variables: MutationSaleClientCardUpdateVars) => genqlBuilder.build(mutation ? {sale_client_card_update: mutation} : {__scalar: true}, {sale_client_card_update: variables}),  
      mutate: (variables: MutationSaleClientCardUpdateVars) => ({} as Promise<Response>),
      _variables: {} as MutationSaleClientCardUpdateVars,
      _result: {} as Response,
    }
}

export type MutationSaleClientCardUpdate = {
  builder: (variables: MutationSaleClientCardUpdateVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_client_card_update'],
  variables: MutationSaleClientCardUpdateVars,
  result: Response
}

export default saleClientCardUpdateMutation