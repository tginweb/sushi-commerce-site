import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleVorderBasketResult} from "../schema"

import {MutationSaleVorderBasketArgs} from "../schema"

export type MutationSaleVorderBasketVars = MutationSaleVorderBasketArgs

export function saleVorderBasketMutation(mutation: MutationGenqlSelection['sale_vorder_basket'] | null) {            
    return {
      build: (variables: MutationSaleVorderBasketVars) => genqlBuilder.build(mutation ? {sale_vorder_basket: mutation} : {__scalar: true}, {sale_vorder_basket: variables}),  
      mutate: (variables: MutationSaleVorderBasketVars) => ({} as Promise<SaleVorderBasketResult>),
      _variables: {} as MutationSaleVorderBasketVars,
      _result: {} as SaleVorderBasketResult,
    }
}

export type MutationSaleVorderBasket = {
  builder: (variables: MutationSaleVorderBasketVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_vorder_basket'],
  variables: MutationSaleVorderBasketVars,
  result: SaleVorderBasketResult
}

export default saleVorderBasketMutation