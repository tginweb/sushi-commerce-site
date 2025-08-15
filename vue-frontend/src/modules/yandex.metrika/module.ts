import {AppModuleBase} from "@/core/classes/AppModuleBase";
import config from "@/config/config";
import {YMetrikaConfig} from "@/modules/yandex.metrika/config";

export class YandexMetrikaModule extends AppModuleBase {

    declare options: YMetrikaConfig

    constructor() {
        super()
        this.options = config.ymetrika
    }
}

