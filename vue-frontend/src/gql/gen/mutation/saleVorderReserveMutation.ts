import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleVorderReserveResult} from "../schema"

import {MutationSaleVorderReserveArgs} from "../schema"

export type MutationSaleVorderReserveVars = MutationSaleVorderReserveArgs

export function saleVorderReserveMutation(mutation: MutationGenqlSelection['sale_vorder_reserve'] | null) {            
    return {
      build: (variables: MutationSaleVorderReserveVars) => genqlBuilder.build(mutation ? {sale_vorder_reserve: mutation} : {__scalar: true}, {sale_vorder_reserve: variables}),  
      mutate: (variables: MutationSaleVorderReserveVars) => ({} as Promise<SaleVorderReserveResult>),
      _variables: {} as MutationSaleVorderReserveVars,
      _result: {} as SaleVorderReserveResult,
    }
}

export type MutationSaleVorderReserve = {
  builder: (variables: MutationSaleVorderReserveVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_vorder_reserve'],
  variables: MutationSaleVorderReserveVars,
  result: SaleVorderReserveResult
}

export default saleVorderReserveMutation