import {defineStore} from "pinia";
import config from "@/config/config";
import {Config} from "@/config/type";
import {getTypedEntries} from "@/core/util/getTypedEntries";
import {merge} from "lodash-es";
import {useRest} from "@/core/rest/service";
import {ref} from "vue";

export const useConfig = defineStore("config", () => {

    const set = <T extends keyof Config = keyof Config, P extends keyof Config[T] = keyof Config[T]>(ns: T, name: P, val: Config[T][P]): any => {
        config[ns][name] = val
    }

    const get = <T extends keyof Config = keyof Config, P extends keyof Config[T] = keyof Config[T]>(ns: T, name?: P, def?: Config[T][P]): any => {
        const res = name ? config[ns][name] : config[ns]
        return typeof res === 'undefined' ? def : res
    }

    const assign = (params: Partial<Config>) => {
        Object.assign(config, params)
    }

    const mergeConfigWithServerSettings = (settings: Record<string, any>) => {
        const result: any = {}
        for (let [paramName, paramValue] of getTypedEntries(settings)) {
            if (paramName.match('.')) {
                const [paramNs, paramNsName] = paramName.split('.')
                if (paramNs && paramNsName && paramValue !== null) {
                    if (!result[paramNs]) {
                        result[paramNs] = {}
                    }
                    result[paramNs][paramNsName] = paramValue
                }
            }
        }

        mergeConfig(result)

        return result
    }

    const mergeConfig = (...args: Partial<Config>[]) => {
        merge(config, ...args)
    }

    const serverConfig = ref<any>()

    const loadServerConfig = async () => {
        const rest = useRest()

        const configUrl = get('app', 'API_CONFIG_URL')
        if (configUrl) {
            if (serverConfig.value) {
                mergeConfigWithServerSettings(serverConfig.value)
                return;
            }
            const res = await rest.queryWrapped<any>(configUrl)
            if (res) {
                serverConfig.value = res
                mergeConfigWithServerSettings(res)
            }
        }
    }

    return {
        serverConfig,
        loadServerConfig,
        mergeConfig,
        mergeConfigWithServerSettings,
        getConfig: get,
        get,
        set,
        assign,
        data: config,
        config
    }
})


