import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCompanyVacancyRecordsetArgs} from "../schema"

export type QueryCompanyVacancyRecordsetVars = QueryCompanyVacancyRecordsetArgs

export type CompanyVacancyRecordsetResult = Query['company_vacancy_recordset']

export function companyVacancyRecordsetQuery(query: QueryGenqlSelection['company_vacancy_recordset'] | null) {
      
    const build = (variables: QueryCompanyVacancyRecordsetVars) => genqlBuilder.build(query ? {company_vacancy_recordset: query} : {__scalar: true}, {company_vacancy_recordset: variables})
    const run = (variables: QueryCompanyVacancyRecordsetVars | null = null): Promise<CompanyVacancyRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCompanyVacancyRecordsetVars,
      _result: {} as CompanyVacancyRecordsetResult,
    }
}

export default companyVacancyRecordsetQuery