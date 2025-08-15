import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleVorderCouponResult} from "../schema"

import {MutationSaleVorderCouponArgs} from "../schema"

export type MutationSaleVorderCouponVars = MutationSaleVorderCouponArgs

export function saleVorderCouponMutation(mutation: MutationGenqlSelection['sale_vorder_coupon'] | null) {            
    return {
      build: (variables: MutationSaleVorderCouponVars) => genqlBuilder.build(mutation ? {sale_vorder_coupon: mutation} : {__scalar: true}, {sale_vorder_coupon: variables}),  
      mutate: (variables: MutationSaleVorderCouponVars) => ({} as Promise<SaleVorderCouponResult>),
      _variables: {} as MutationSaleVorderCouponVars,
      _result: {} as SaleVorderCouponResult,
    }
}

export type MutationSaleVorderCoupon = {
  builder: (variables: MutationSaleVorderCouponVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_vorder_coupon'],
  variables: MutationSaleVorderCouponVars,
  result: SaleVorderCouponResult
}

export default saleVorderCouponMutation