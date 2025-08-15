import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCompanyVacancySingleArgs} from "../schema"

export type QueryCompanyVacancySingleVars = QueryCompanyVacancySingleArgs

export type CompanyVacancySingleResult = Query['company_vacancy_single']

export function companyVacancySingleQuery(query: QueryGenqlSelection['company_vacancy_single'] | null) {
      
    const build = (variables: QueryCompanyVacancySingleVars) => genqlBuilder.build(query ? {company_vacancy_single: query} : {__scalar: true}, {company_vacancy_single: variables})
    const run = (variables: QueryCompanyVacancySingleVars | null = null): Promise<CompanyVacancySingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCompanyVacancySingleVars,
      _result: {} as CompanyVacancySingleResult,
    }
}

export default companyVacancySingleQuery