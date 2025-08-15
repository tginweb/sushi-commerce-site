import {ShopConfig} from "@/modules/shop/config";
import {AppModuleBase} from "@/core/classes/AppModuleBase";
import config from "@/config/config";

export class ShopModule extends AppModuleBase {

    declare options: ShopConfig

    constructor() {
        super()
        this.options = config.shop
    }

    routerAfterEach(to: string, from: string) {

    }
}

