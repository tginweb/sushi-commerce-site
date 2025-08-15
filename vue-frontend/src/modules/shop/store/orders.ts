import {useScopeQuery} from "@/core/store/scopeQuery";
import {defineStore} from "pinia";
import {toRefs} from "vue";
import {useGraphql} from "@/core/graphql/service";
import {saleOrderActiveListQuery} from "@/gql/gen/query/saleOrderActiveListQuery";
import {useShopStateStore} from "@/modules/shop/store/state";

const STORE_NAME = "shop-orders";

export const useOrdersStore = defineStore(STORE_NAME, () => {
    const {registerScopeQuery} = useScopeQuery();

    const state = useShopStateStore()

    const {
        ordersActive,
    } = toRefs(state)

    const {useLazyQuery} = useGraphql();

    registerScopeQuery(
        STORE_NAME,
        "user",
        {
            sale_order_active_list: {
                __fragment: "OrderFields",
            },
        },
        (data) => {
            if (data.sale_order_active_list)
                ordersActive.value = data.sale_order_active_list;
        }
    );

    const activeOrdersRequest = useLazyQuery(saleOrderActiveListQuery({
        __fragment: 'OrderFields'
    }), {
        initialResult: () => ordersActive.value,
        onResult: (result) => ordersActive.value = result,
        cache: 1000,
    })

    const getActiveOrderOrFetch = async (id: number) => {
        let order = ordersActive.value.find(order => order.ID === id)
        if (!order) {
            const orders = await activeOrdersRequest.query()
            if (orders) {
                order = ordersActive.value.find(order => order.ID === id)
            }
        }
        return order
    }

    return {
        ordersActive,
        activeOrdersRequest,
        getActiveOrderOrFetch,
    };
});

