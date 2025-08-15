import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleOrderPayOnlineResult} from "../schema"

import {MutationSaleOrderPayOnlineArgs} from "../schema"

export type MutationSaleOrderPayOnlineVars = MutationSaleOrderPayOnlineArgs

export function saleOrderPayOnlineMutation(mutation: MutationGenqlSelection['sale_order_pay_online'] | null) {            
    return {
      build: (variables: MutationSaleOrderPayOnlineVars) => genqlBuilder.build(mutation ? {sale_order_pay_online: mutation} : {__scalar: true}, {sale_order_pay_online: variables}),  
      mutate: (variables: MutationSaleOrderPayOnlineVars) => ({} as Promise<SaleOrderPayOnlineResult>),
      _variables: {} as MutationSaleOrderPayOnlineVars,
      _result: {} as SaleOrderPayOnlineResult,
    }
}

export type MutationSaleOrderPayOnline = {
  builder: (variables: MutationSaleOrderPayOnlineVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_order_pay_online'],
  variables: MutationSaleOrderPayOnlineVars,
  result: SaleOrderPayOnlineResult
}

export default saleOrderPayOnlineMutation