import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleProfileCalcDeliveryResult} from "../schema"

import {MutationSaleProfileCalcDeliveryArgs} from "../schema"

export type MutationSaleProfileCalcDeliveryVars = MutationSaleProfileCalcDeliveryArgs

export function saleProfileCalcDeliveryMutation(mutation: MutationGenqlSelection['sale_profile_calc_delivery'] | null) {            
    return {
      build: (variables: MutationSaleProfileCalcDeliveryVars) => genqlBuilder.build(mutation ? {sale_profile_calc_delivery: mutation} : {__scalar: true}, {sale_profile_calc_delivery: variables}),  
      mutate: (variables: MutationSaleProfileCalcDeliveryVars) => ({} as Promise<SaleProfileCalcDeliveryResult>),
      _variables: {} as MutationSaleProfileCalcDeliveryVars,
      _result: {} as SaleProfileCalcDeliveryResult,
    }
}

export type MutationSaleProfileCalcDelivery = {
  builder: (variables: MutationSaleProfileCalcDeliveryVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_profile_calc_delivery'],
  variables: MutationSaleProfileCalcDeliveryVars,
  result: SaleProfileCalcDeliveryResult
}

export default saleProfileCalcDeliveryMutation