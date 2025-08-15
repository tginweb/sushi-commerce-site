import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCompanyOfficeSingleArgs} from "../schema"

export type QueryCompanyOfficeSingleVars = QueryCompanyOfficeSingleArgs

export type CompanyOfficeSingleResult = Query['company_office_single']

export function companyOfficeSingleQuery(query: QueryGenqlSelection['company_office_single'] | null) {
      
    const build = (variables: QueryCompanyOfficeSingleVars) => genqlBuilder.build(query ? {company_office_single: query} : {__scalar: true}, {company_office_single: variables})
    const run = (variables: QueryCompanyOfficeSingleVars | null = null): Promise<CompanyOfficeSingleResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCompanyOfficeSingleVars,
      _result: {} as CompanyOfficeSingleResult,
    }
}

export default companyOfficeSingleQuery