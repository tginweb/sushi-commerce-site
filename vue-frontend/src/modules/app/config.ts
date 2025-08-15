import {AppContact} from "./types";
import {BaseConfig} from "@/core/classes/BaseConfig";

export class AppConfig extends BaseConfig {
    SERVER_HOST: string = ''
    SERVER_URL: string = ''
    SERVER_PRODUCTION_URL: string = ''
    API_CONFIG_URL: string = ''
    API_GRAPHQL_URL: string = ''
    API_REST_URL: string = ''

    APP_TITLE: string = ''
    APP_DISABLE: boolean = false

    IMAGE_DEV_URLS: string[] = []
    IMAGE_DEV_BASE_URL: string = ''
    IMAGE_BASE_URL: string = ''
    IMAGE_STYLER_TEMPLATE: string = ''
    IMAGE_CACHE_LIMIT: number = 200

    PHONE: string = ''

    TIMEZONE_OVERRIDE: string = ''

    CONTACTS: AppContact[] = []

    constructor(params: Partial<AppConfig>) {
        super()
        this.applyConfig(params)
    }
}


