import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleOrderRepeatResult} from "../schema"

import {MutationSaleOrderRepeatArgs} from "../schema"

export type MutationSaleOrderRepeatVars = MutationSaleOrderRepeatArgs

export function saleOrderRepeatMutation(mutation: MutationGenqlSelection['sale_order_repeat'] | null) {            
    return {
      build: (variables: MutationSaleOrderRepeatVars) => genqlBuilder.build(mutation ? {sale_order_repeat: mutation} : {__scalar: true}, {sale_order_repeat: variables}),  
      mutate: (variables: MutationSaleOrderRepeatVars) => ({} as Promise<SaleOrderRepeatResult>),
      _variables: {} as MutationSaleOrderRepeatVars,
      _result: {} as SaleOrderRepeatResult,
    }
}

export type MutationSaleOrderRepeat = {
  builder: (variables: MutationSaleOrderRepeatVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_order_repeat'],
  variables: MutationSaleOrderRepeatVars,
  result: SaleOrderRepeatResult
}

export default saleOrderRepeatMutation