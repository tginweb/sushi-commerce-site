import {useScopeQuery} from "@/core/store/scopeQuery";
import {defineStore, storeToRefs} from "pinia";
import {computed, toRefs} from "vue";
import {useGraphql} from "@/core/graphql/service";
import {useShopStateStore} from "@/modules/shop/store/state";
import {useCompanyStore} from "@/modules/company/store/company";

const STORE_NAME = "shop";

export const useShopStore = defineStore(STORE_NAME, () => {
    const {registerScopeQuery} = useScopeQuery();

    const companyStore = useCompanyStore()
    const {offices} = storeToRefs(companyStore)

    const state = useShopStateStore()

    const {
        orderStatuses,
        deliveryZones,
    } = toRefs(state)

    const {} = useGraphql();

    // SCOPE QUERY
    registerScopeQuery(
        STORE_NAME,
        "app",
        {
            sale_order_statuses: {
                __fragment: "OrderStatus",
            },
            sale_delivery_zones: {
                __fragment: "DeliveryZoneElement",
            },
        },
        (data) => {
            if (data.sale_delivery_zones)
                deliveryZones.value = data.sale_delivery_zones;

            if (data.sale_order_statuses)
                orderStatuses.value = data.sale_order_statuses;
        }
    );

    const orderStatusesOrder = computed(() => {
        return orderStatuses.value.filter(status => status.TYPE === 'O')
    })

    const saleOffices = computed(() => {
        return offices.value.filter(item => item)
    })

    return {
        orderStatusesOrder,
        saleOffices,
        deliveryZones
    };
});

