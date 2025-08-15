import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryGeoGeocoderLocationByAddressArgs} from "../schema"

export type QueryGeoGeocoderLocationByAddressVars = QueryGeoGeocoderLocationByAddressArgs

export type GeoGeocoderLocationByAddressResult = Query['geo_geocoder_location_by_address']

export function geoGeocoderLocationByAddressQuery(query: QueryGenqlSelection['geo_geocoder_location_by_address'] | null) {
      
    const build = (variables: QueryGeoGeocoderLocationByAddressVars) => genqlBuilder.build(query ? {geo_geocoder_location_by_address: query} : {__scalar: true}, {geo_geocoder_location_by_address: variables})
    const run = (variables: QueryGeoGeocoderLocationByAddressVars | null = null): Promise<GeoGeocoderLocationByAddressResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryGeoGeocoderLocationByAddressVars,
      _result: {} as GeoGeocoderLocationByAddressResult,
    }
}

export default geoGeocoderLocationByAddressQuery