import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, SaleProfileSaveResult} from "../schema"

import {MutationSaleProfileSaveArgs} from "../schema"

export type MutationSaleProfileSaveVars = MutationSaleProfileSaveArgs

export function saleProfileSaveMutation(mutation: MutationGenqlSelection['sale_profile_save'] | null) {            
    return {
      build: (variables: MutationSaleProfileSaveVars) => genqlBuilder.build(mutation ? {sale_profile_save: mutation} : {__scalar: true}, {sale_profile_save: variables}),  
      mutate: (variables: MutationSaleProfileSaveVars) => ({} as Promise<SaleProfileSaveResult>),
      _variables: {} as MutationSaleProfileSaveVars,
      _result: {} as SaleProfileSaveResult,
    }
}

export type MutationSaleProfileSave = {
  builder: (variables: MutationSaleProfileSaveVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['sale_profile_save'],
  variables: MutationSaleProfileSaveVars,
  result: SaleProfileSaveResult
}

export default saleProfileSaveMutation