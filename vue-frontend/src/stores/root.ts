import {usePagesStore} from "@/modules/page/store/pages";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {useShopStore} from "@/modules/shop/store/shop";
import {useOfferStore} from "@/modules/offer/store/offer";
import {useMenuStore} from "@/modules/menu/store/menu";
import {useNoticeStore} from "@/modules/notice/store/notice";
import {useUserStore} from "@/modules/user/store/user";
import {useShopFavStore} from "@/modules/shop/store/fav";
import {useCompanyStore} from "@/modules/company/store/company";
import {useFaqStore} from "@/modules/faq/store/faq";
import {useCameraStore} from "./camera";
import {useReviewStore} from "@/modules/review/store/review";
import {useVacancyStore} from "@/modules/company/store/vacancy";
import {useCommonStore} from "@/core/store/common";
import {useDebugStore} from "@/core/store/debug";
import {createRootStore} from "@/core/store/root";
import {StoreConfig} from "@/core/types";
import {useOrderProfileStore} from "@/modules/shop/store/order-profile";
import {useDeliveryCalculateStore} from "@/modules/shop/store/delivery-calculate";
import {useOrdersStore} from "@/modules/shop/store/orders";
import {useLoalityStore} from "@/modules/shop/store/loyalty";

export function useRootStore() {

    const stores: Record<string, StoreConfig> = {
        common: {
            callback: useCommonStore,
            boot: true,
        },
        debug: {
            callback: useDebugStore,
            boot: true,
        },
        pages: {
            callback: usePagesStore,
            boot: true,
        },
        catalog: {
            callback: useCatalogStore,
            boot: true,
        },
        vorder: {
            callback: useVorderStore,
            boot: true,
        },
        shop: {
            callback: useShopStore,
            boot: true,
        },
        delivery: {
            callback: useCompanyStore,
            boot: true,
        },
        offer: {
            callback: useOfferStore,
            boot: true,
        },
        menu: {
            callback: useMenuStore,
            boot: true,
            rootGetters: true,
        },
        notice: {
            callback: useNoticeStore,
            boot: true,
            rootGetters: true,
        },
        user: {
            callback: useUserStore,
            boot: true,
        },
        fav: {
            callback: useShopFavStore,
            boot: true,
        },
        faq: {
            callback: useFaqStore,
            boot: true,
        },
        camera: {
            callback: useCameraStore,
            boot: true,
        },
        review: {
            callback: useReviewStore,
            boot: true,
        },
        vacancy: {
            callback: useVacancyStore,
            boot: true,
        },
        order_profile: {
            callback: useOrderProfileStore,
            boot: true,
        },
        delivery_calculate: {
            callback: useDeliveryCalculateStore,
            boot: true,
        },
        orders: {
            callback: useOrdersStore,
            boot: true,
        },
        loality: {
            callback: useLoalityStore,
            boot: true,
        },
    };

    const root = createRootStore(stores)

    return {
        stores,
        ...root
    };
}
