import {AppRouteContext} from "@/core/classes/App";
import Result from "@/core/classes/Result";
import {UserConfig} from "@/modules/user/config";
import {AppModuleBase} from "@/core/classes/AppModuleBase";
import config from "@/config/config";

export class UserModule extends AppModuleBase {

    declare options: UserConfig

    constructor() {
        super()
        this.options = config.user
    }

    guardAccess(to: any, from: any, result: Result, context: AppRouteContext): void | boolean | null {

        const needAuth = context.meta.auth

        if (needAuth && to.s) {
            console.log('user guardAccess', context)
            result.addError('Not authorized')
            result.setRedirect({
                type: 'router',
                path: '/auth'
            })
            return false
        }

    }
}

