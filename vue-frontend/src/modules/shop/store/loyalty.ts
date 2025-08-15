import {useScopeQuery} from "@/core/store/scopeQuery";
import {defineStore} from "pinia";
import {toRefs} from "vue";

import saleClientCardApplyByPhoneQuery from "@/gql/gen/query/saleClientCardApplyByPhoneQuery";
import {useGraphql} from "@/core/graphql/service";
import {useShopStateStore} from "@/modules/shop/store/state";
import saleClientCardFetchQuery from "@/gql/gen/query/saleClientCardFetchQuery";

const STORE_NAME = "shop-loyalty";

export const useLoalityStore = defineStore(STORE_NAME, () => {
    const {registerScopeQuery} = useScopeQuery();

    const state = useShopStateStore()

    const {
        clientCard,
        bonusLevels,
    } = toRefs(state)

    const {useLazyQuery} = useGraphql();

    // SCOPE QUERY
    registerScopeQuery(
        STORE_NAME,
        "app",
        {
            sale_bonus_level_list: {
                __fragment: "BonusLevelFields",
            },
        },
        (data) => {
            if (data.sale_bonus_level_list)
                bonusLevels.value = data.sale_bonus_level_list;
        }
    );

    registerScopeQuery(
        STORE_NAME,
        "user",
        {
            sale_client_card_fetch: {
                __fragment: "ClientCardFields",
            },
            sale_bonus_level_list: {
                __fragment: "BonusLevelFields",
            },
        },
        (data) => {

            if (data.sale_client_card_fetch)
                clientCard.value = data.sale_client_card_fetch;

            if (data.sale_bonus_level_list)
                bonusLevels.value = data.sale_bonus_level_list;
        }
    );

    const clientCardApplyByPhoneQuery = useLazyQuery(
        saleClientCardApplyByPhoneQuery({
            __fragment: "ClientCardFields",
        }),
        {
            debounced: 1000,
        }
    );

    clientCardApplyByPhoneQuery.onResult((card) => {
        if (card) {
            clientCard.value = card;
        }
    });

    const clientCardRequest = useLazyQuery(saleClientCardFetchQuery({
        __fragment: 'ClientCardFields'
    }), {
        initialResult: () => clientCard.value,
        onResult: (result) => clientCard.value = result,
        cache: 1000,
    })

    return {
        clientCardRequest,
        clientCard,
        clientCardApplyByPhoneQuery,
        bonusLevels
    };
});

