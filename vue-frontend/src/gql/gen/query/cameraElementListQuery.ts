import {useGraphql} from "@/core/graphql/service"

import {genqlBuilder} from "@/core/graphql/genql/builder"

import {JsonFragmentName} from "../fragments"

import {QueryGenqlSelection, Query} from "../schema"

import {QueryCameraElementListArgs} from "../schema"

export type QueryCameraElementListVars = QueryCameraElementListArgs

export type CameraElementListResult = Query['camera_element_list']

export function cameraElementListQuery(query: QueryGenqlSelection['camera_element_list'] | null) {
      
    const build = (variables: QueryCameraElementListVars) => genqlBuilder.build(query ? {camera_element_list: query} : {__scalar: true}, {camera_element_list: variables})
    const run = (variables: QueryCameraElementListVars | null = null): Promise<CameraElementListResult | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as QueryCameraElementListVars,
      _result: {} as CameraElementListResult,
    }
}

export default cameraElementListQuery