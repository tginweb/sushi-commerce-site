import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationSaleClientCardDeleteArgs} from "../schema"

export type MutationSaleClientCardDeleteVars = MutationSaleClientCardDeleteArgs

export function saleClientCardDeleteMutation(mutation: MutationGenqlSelection['sale_client_card_delete'] | null) {            
    return {
      build: (variables: MutationSaleClientCardDeleteVars) => genqlBuilder.build(mutation ? {sale_client_card_delete: mutation} : {__scalar: true}, {sale_client_card_delete: variables}),  
      mutate: (variables: MutationSaleClientCardDeleteVars) => ({} as Promise<Response>),
      _variables: {} as MutationSaleClientCardDeleteVars,
      _result: {} as Response,
    }
}

export type MutationSaleClientCardDelete = {
  builder: (variables: MutationSaleClientCardDeleteVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_client_card_delete'],
  variables: MutationSaleClientCardDeleteVars,
  result: Response
}

export default saleClientCardDeleteMutation