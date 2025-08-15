import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationSaleClientCardCreateArgs} from "../schema"

export type MutationSaleClientCardCreateVars = MutationSaleClientCardCreateArgs

export function saleClientCardCreateMutation(mutation: MutationGenqlSelection['sale_client_card_create'] | null) {            
    return {
      build: (variables: MutationSaleClientCardCreateVars) => genqlBuilder.build(mutation ? {sale_client_card_create: mutation} : {__scalar: true}, {sale_client_card_create: variables}),  
      mutate: (variables: MutationSaleClientCardCreateVars) => ({} as Promise<Response>),
      _variables: {} as MutationSaleClientCardCreateVars,
      _result: {} as Response,
    }
}

export type MutationSaleClientCardCreate = {
  builder: (variables: MutationSaleClientCardCreateVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_client_card_create'],
  variables: MutationSaleClientCardCreateVars,
  result: Response
}

export default saleClientCardCreateMutation