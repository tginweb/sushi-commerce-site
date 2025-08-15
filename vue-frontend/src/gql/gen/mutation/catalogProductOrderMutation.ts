import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationCatalogProductOrderArgs} from "../schema"

export type MutationCatalogProductOrderVars = MutationCatalogProductOrderArgs

export function catalogProductOrderMutation(mutation: MutationGenqlSelection['catalog_product_order'] | null) {            
    return {
      build: (variables: MutationCatalogProductOrderVars) => genqlBuilder.build(mutation ? {catalog_product_order: mutation} : {__scalar: true}, {catalog_product_order: variables}),  
      mutate: (variables: MutationCatalogProductOrderVars) => ({} as Promise<Response>),
      _variables: {} as MutationCatalogProductOrderVars,
      _result: {} as Response,
    }
}

export type MutationCatalogProductOrder = {
  builder: (variables: MutationCatalogProductOrderVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['catalog_product_order'],
  variables: MutationCatalogProductOrderVars,
  result: Response
}

export default catalogProductOrderMutation