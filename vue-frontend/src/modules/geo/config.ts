import {BaseConfig} from "@/core/classes/BaseConfig";

export class GeoConfig extends BaseConfig {
    GEO_PARAM: number = 100

    constructor(params: Partial<GeoConfig>) {
        super()
        this.applyConfig(params)
    }
}


