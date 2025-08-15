import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleVorderApplyResult} from "../schema"

import {MutationSaleVorderApplyArgs} from "../schema"

export type MutationSaleVorderApplyVars = MutationSaleVorderApplyArgs

export function saleVorderApplyMutation(mutation: MutationGenqlSelection['sale_vorder_apply'] | null) {            
    return {
      build: (variables: MutationSaleVorderApplyVars) => genqlBuilder.build(mutation ? {sale_vorder_apply: mutation} : {__scalar: true}, {sale_vorder_apply: variables}),  
      mutate: (variables: MutationSaleVorderApplyVars) => ({} as Promise<SaleVorderApplyResult>),
      _variables: {} as MutationSaleVorderApplyVars,
      _result: {} as SaleVorderApplyResult,
    }
}

export type MutationSaleVorderApply = {
  builder: (variables: MutationSaleVorderApplyVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_vorder_apply'],
  variables: MutationSaleVorderApplyVars,
  result: SaleVorderApplyResult
}

export default saleVorderApplyMutation