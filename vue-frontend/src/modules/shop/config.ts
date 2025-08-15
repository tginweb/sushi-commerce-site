import {BaseConfig} from "@/core/classes/BaseConfig";

export class ShopConfig extends BaseConfig {
    SHOP_PARAM: number = 100

    constructor(params: Partial<ShopConfig>) {
        super()
        this.applyConfig(params)
    }
}


