import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleVorderSubmitResult} from "../schema"

import {MutationSaleVorderSubmitArgs} from "../schema"

export type MutationSaleVorderSubmitVars = MutationSaleVorderSubmitArgs

export function saleVorderSubmitMutation(mutation: MutationGenqlSelection['sale_vorder_submit'] | null) {            
    return {
      build: (variables: MutationSaleVorderSubmitVars) => genqlBuilder.build(mutation ? {sale_vorder_submit: mutation} : {__scalar: true}, {sale_vorder_submit: variables}),  
      mutate: (variables: MutationSaleVorderSubmitVars) => ({} as Promise<SaleVorderSubmitResult>),
      _variables: {} as MutationSaleVorderSubmitVars,
      _result: {} as SaleVorderSubmitResult,
    }
}

export type MutationSaleVorderSubmit = {
  builder: (variables: MutationSaleVorderSubmitVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_vorder_submit'],
  variables: MutationSaleVorderSubmitVars,
  result: SaleVorderSubmitResult
}

export default saleVorderSubmitMutation