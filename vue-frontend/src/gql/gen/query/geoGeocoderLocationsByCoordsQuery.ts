import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryGeoGeocoderLocationsByCoordsArgs} from "../schema"

export type QueryGeoGeocoderLocationsByCoordsVars = QueryGeoGeocoderLocationsByCoordsArgs

export type GeoGeocoderLocationsByCoordsResult = Query['geo_geocoder_locations_by_coords']

export function geoGeocoderLocationsByCoordsQuery(query: QueryGenqlSelection['geo_geocoder_locations_by_coords'] | null) {
      
    const build = (variables: QueryGeoGeocoderLocationsByCoordsVars) => genqlBuilder.build(query ? {geo_geocoder_locations_by_coords: query} : {__scalar: true}, {geo_geocoder_locations_by_coords: variables})
    const run = (variables: QueryGeoGeocoderLocationsByCoordsVars | null = null): Promise<GeoGeocoderLocationsByCoordsResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryGeoGeocoderLocationsByCoordsVars,
      _result: {} as GeoGeocoderLocationsByCoordsResult,
    }
}

export default geoGeocoderLocationsByCoordsQuery