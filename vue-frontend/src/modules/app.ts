import {appInstance} from "@/modules/app-instance";

import {AppModule} from "@/modules/app/module";
import {ShopModule} from "@/modules/shop/module";
import {UserModule} from "@/modules/user/module";

import {YandexMetrikaModule} from "@/modules/yandex.metrika/module";

appInstance.registerClassModule(new AppModule());
appInstance.registerClassModule(new YandexMetrikaModule());
appInstance.registerClassModule(new UserModule());
appInstance.registerClassModule(new ShopModule());

export const app = appInstance;

export function useApp() {
    return app;
}
