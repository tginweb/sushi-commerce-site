import {BaseConfig} from "@/core/classes/BaseConfig";

export class YMetrikaConfig extends BaseConfig {
    Y_PARAM: number = 100

    constructor(params: Partial<YMetrikaConfig>) {
        super()
        this.applyConfig(params)
    }
}


