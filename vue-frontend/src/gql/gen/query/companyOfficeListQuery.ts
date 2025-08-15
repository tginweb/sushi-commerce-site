import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCompanyOfficeListArgs} from "../schema"

export type QueryCompanyOfficeListVars = QueryCompanyOfficeListArgs

export type CompanyOfficeListResult = Query['company_office_list']

export function companyOfficeListQuery(query: QueryGenqlSelection['company_office_list'] | null) {
      
    const build = (variables: QueryCompanyOfficeListVars) => genqlBuilder.build(query ? {company_office_list: query} : {__scalar: true}, {company_office_list: variables})
    const run = (variables: QueryCompanyOfficeListVars | null = null): Promise<CompanyOfficeListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCompanyOfficeListVars,
      _result: {} as CompanyOfficeListResult,
    }
}

export default companyOfficeListQuery