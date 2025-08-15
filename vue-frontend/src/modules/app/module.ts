import {AppConfig} from "./config";
import config from "@/config/config";
import {AppModuleBase} from "@/core/classes/AppModuleBase";

export class AppModule extends AppModuleBase {

    declare options: AppConfig

    constructor() {
        super()
        this.options = config.app
    }

    boot(): void {

    }
}

