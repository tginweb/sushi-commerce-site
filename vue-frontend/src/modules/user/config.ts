import {BaseConfig} from "@/core/classes/BaseConfig";

export class UserConfig extends BaseConfig {
    USER_PARAM: number = 100

    constructor(params: Partial<UserConfig>) {
        super()
        this.applyConfig(params)
    }
}


