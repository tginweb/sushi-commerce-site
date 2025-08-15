import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

export type QuerySaleDeliveryZonesVars = {}

export type SaleDeliveryZonesResult = Query['sale_delivery_zones']

export function saleDeliveryZonesQuery(query: QueryGenqlSelection['sale_delivery_zones'] | null) {
      
    const build = (variables: QuerySaleDeliveryZonesVars) => genqlBuilder.build(query ? {sale_delivery_zones: query} : {__scalar: true}, {sale_delivery_zones: variables})
    const run = (variables: QuerySaleDeliveryZonesVars | null = null): Promise<SaleDeliveryZonesResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QuerySaleDeliveryZonesVars,
      _result: {} as SaleDeliveryZonesResult,
    }
}

export default saleDeliveryZonesQuery