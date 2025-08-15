import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationCompanyOfficeUpdateArgs} from "../schema"

export type MutationCompanyOfficeUpdateVars = MutationCompanyOfficeUpdateArgs

export function companyOfficeUpdateMutation(mutation: MutationGenqlSelection['company_office_update'] | null) {            
    return {
      build: (variables: MutationCompanyOfficeUpdateVars) => genqlBuilder.build(mutation ? {company_office_update: mutation} : {__scalar: true}, {company_office_update: variables}),  
      mutate: (variables: MutationCompanyOfficeUpdateVars) => ({} as Promise<Response>),
      _variables: {} as MutationCompanyOfficeUpdateVars,
      _result: {} as Response,
    }
}

export type MutationCompanyOfficeUpdate = {
  builder: (variables: MutationCompanyOfficeUpdateVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['company_office_update'],
  variables: MutationCompanyOfficeUpdateVars,
  result: Response
}

export default companyOfficeUpdateMutation