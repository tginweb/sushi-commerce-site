import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleVorderNewResult} from "../schema"

export type MutationSaleVorderNewVars = {}

export function saleVorderNewMutation(mutation: MutationGenqlSelection['sale_vorder_new'] | null) {            
    return {
      build: (variables: MutationSaleVorderNewVars) => genqlBuilder.build(mutation ? {sale_vorder_new: mutation} : {__scalar: true}, {sale_vorder_new: variables}),  
      mutate: (variables: MutationSaleVorderNewVars) => ({} as Promise<SaleVorderNewResult>),
      _variables: {} as MutationSaleVorderNewVars,
      _result: {} as SaleVorderNewResult,
    }
}

export type MutationSaleVorderNew = {
  builder: (variables: MutationSaleVorderNewVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_vorder_new'],
  variables: MutationSaleVorderNewVars,
  result: SaleVorderNewResult
}

export default saleVorderNewMutation