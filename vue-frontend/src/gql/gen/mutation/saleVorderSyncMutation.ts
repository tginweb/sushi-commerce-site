import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleVorderSyncResult} from "../schema"

import {MutationSaleVorderSyncArgs} from "../schema"

export type MutationSaleVorderSyncVars = MutationSaleVorderSyncArgs

export function saleVorderSyncMutation(mutation: MutationGenqlSelection['sale_vorder_sync'] | null) {            
    return {
      build: (variables: MutationSaleVorderSyncVars) => genqlBuilder.build(mutation ? {sale_vorder_sync: mutation} : {__scalar: true}, {sale_vorder_sync: variables}),  
      mutate: (variables: MutationSaleVorderSyncVars) => ({} as Promise<SaleVorderSyncResult>),
      _variables: {} as MutationSaleVorderSyncVars,
      _result: {} as SaleVorderSyncResult,
    }
}

export type MutationSaleVorderSync = {
  builder: (variables: MutationSaleVorderSyncVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_vorder_sync'],
  variables: MutationSaleVorderSyncVars,
  result: SaleVorderSyncResult
}

export default saleVorderSyncMutation