import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleProfileDeleteResult} from "../schema"

import {MutationSaleProfileDeleteArgs} from "../schema"

export type MutationSaleProfileDeleteVars = MutationSaleProfileDeleteArgs

export function saleProfileDeleteMutation(mutation: MutationGenqlSelection['sale_profile_delete'] | null) {            
    return {
      build: (variables: MutationSaleProfileDeleteVars) => genqlBuilder.build(mutation ? {sale_profile_delete: mutation} : {__scalar: true}, {sale_profile_delete: variables}),  
      mutate: (variables: MutationSaleProfileDeleteVars) => ({} as Promise<SaleProfileDeleteResult>),
      _variables: {} as MutationSaleProfileDeleteVars,
      _result: {} as SaleProfileDeleteResult,
    }
}

export type MutationSaleProfileDelete = {
  builder: (variables: MutationSaleProfileDeleteVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_profile_delete'],
  variables: MutationSaleProfileDeleteVars,
  result: SaleProfileDeleteResult
}

export default saleProfileDeleteMutation