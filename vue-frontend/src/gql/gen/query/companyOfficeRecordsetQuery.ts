import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCompanyOfficeRecordsetArgs} from "../schema"

export type QueryCompanyOfficeRecordsetVars = QueryCompanyOfficeRecordsetArgs

export type CompanyOfficeRecordsetResult = Query['company_office_recordset']

export function companyOfficeRecordsetQuery(query: QueryGenqlSelection['company_office_recordset'] | null) {
      
    const build = (variables: QueryCompanyOfficeRecordsetVars) => genqlBuilder.build(query ? {company_office_recordset: query} : {__scalar: true}, {company_office_recordset: variables})
    const run = (variables: QueryCompanyOfficeRecordsetVars | null = null): Promise<CompanyOfficeRecordsetResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCompanyOfficeRecordsetVars,
      _result: {} as CompanyOfficeRecordsetResult,
    }
}

export default companyOfficeRecordsetQuery