import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {MutationGenqlSelection, Response} from "../schema"

import {MutationCompanyVacancyOrderArgs} from "../schema"

export type MutationCompanyVacancyOrderVars = MutationCompanyVacancyOrderArgs

export function companyVacancyOrderMutation(mutation: MutationGenqlSelection['company_vacancy_order'] | null) {            
    return {
      build: (variables: MutationCompanyVacancyOrderVars) => genqlBuilder.build(mutation ? {company_vacancy_order: mutation} : {__scalar: true}, {company_vacancy_order: variables}),  
      mutate: (variables: MutationCompanyVacancyOrderVars) => ({} as Promise<Response>),
      _variables: {} as MutationCompanyVacancyOrderVars,
      _result: {} as Response,
    }
}

export type MutationCompanyVacancyOrder = {
  builder: (variables: MutationCompanyVacancyOrderVars) => MutationGenqlSelection,
  selection: MutationGenqlSelection['company_vacancy_order'],
  variables: MutationCompanyVacancyOrderVars,
  result: Response
}

export default companyVacancyOrderMutation