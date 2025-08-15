import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCompanyVacancyListArgs} from "../schema"

export type QueryCompanyVacancyListVars = QueryCompanyVacancyListArgs

export type CompanyVacancyListResult = Query['company_vacancy_list']

export function companyVacancyListQuery(query: QueryGenqlSelection['company_vacancy_list'] | null) {
      
    const build = (variables: QueryCompanyVacancyListVars) => genqlBuilder.build(query ? {company_vacancy_list: query} : {__scalar: true}, {company_vacancy_list: variables})
    const run = (variables: QueryCompanyVacancyListVars | null = null): Promise<CompanyVacancyListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCompanyVacancyListVars,
      _result: {} as CompanyVacancyListResult,
    }
}

export default companyVacancyListQuery