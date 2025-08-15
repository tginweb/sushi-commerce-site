import {BaseConfig} from "@/core/classes/BaseConfig";

export class CompanyConfig extends BaseConfig {
    MAX_OFFICE_COUNT: number = 100

    constructor(params: Partial<CompanyConfig>) {
        super()
        this.applyConfig(params)
    }
}


