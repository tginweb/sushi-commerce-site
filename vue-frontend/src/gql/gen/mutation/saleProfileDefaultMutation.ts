import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleProfileDefaultResult} from "../schema"

import {MutationSaleProfileDefaultArgs} from "../schema"

export type MutationSaleProfileDefaultVars = MutationSaleProfileDefaultArgs

export function saleProfileDefaultMutation(mutation: MutationGenqlSelection['sale_profile_default'] | null) {            
    return {
      build: (variables: MutationSaleProfileDefaultVars) => genqlBuilder.build(mutation ? {sale_profile_default: mutation} : {__scalar: true}, {sale_profile_default: variables}),  
      mutate: (variables: MutationSaleProfileDefaultVars) => ({} as Promise<SaleProfileDefaultResult>),
      _variables: {} as MutationSaleProfileDefaultVars,
      _result: {} as SaleProfileDefaultResult,
    }
}

export type MutationSaleProfileDefault = {
  builder: (variables: MutationSaleProfileDefaultVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_profile_default'],
  variables: MutationSaleProfileDefaultVars,
  result: SaleProfileDefaultResult
}

export default saleProfileDefaultMutation