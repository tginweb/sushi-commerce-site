import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleOrderCancelResult} from "../schema"

import {MutationSaleOrderCancelArgs} from "../schema"

export type MutationSaleOrderCancelVars = MutationSaleOrderCancelArgs

export function saleOrderCancelMutation(mutation: MutationGenqlSelection['sale_order_cancel'] | null) {            
    return {
      build: (variables: MutationSaleOrderCancelVars) => genqlBuilder.build(mutation ? {sale_order_cancel: mutation} : {__scalar: true}, {sale_order_cancel: variables}),  
      mutate: (variables: MutationSaleOrderCancelVars) => ({} as Promise<SaleOrderCancelResult>),
      _variables: {} as MutationSaleOrderCancelVars,
      _result: {} as SaleOrderCancelResult,
    }
}

export type MutationSaleOrderCancel = {
  builder: (variables: MutationSaleOrderCancelVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_order_cancel'],
  variables: MutationSaleOrderCancelVars,
  result: SaleOrderCancelResult
}

export default saleOrderCancelMutation