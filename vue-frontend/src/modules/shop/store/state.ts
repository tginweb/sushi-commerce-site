import {
    BonusLevel,
    DeliveryCalculate,
    DeliveryZone,
    Order,
    OrderAttributesValue,
    OrderProfile,
    OrderStatus,
    SaleClientCard,
} from "@/gql/gen";
import {defineStore} from "pinia";
import {ref} from "vue";

const STORE_NAME = "shop-state";

export const useShopStateStore = defineStore(STORE_NAME, () => {


    const orderStatuses = ref<OrderStatus[]>([]);

    const ordersActive = ref<Order[]>([]);

    const clientCard = ref<SaleClientCard | null>();

    const profiles = ref<OrderProfile[]>([]);

    const profilesPayload = ref<Record<string, OrderProfilePayload>>({});

    const deliveryCalculatesMap = ref<Record<string, DeliveryCalculateEntry>>({});

    const deliveryZones = ref<DeliveryZone[]>([]);

    const bonusLevels = ref<BonusLevel[]>([]);

    return {
        profiles,
        profilesPayload,
        deliveryCalculatesMap,
        orderStatuses,
        ordersActive,
        clientCard,
        deliveryZones,
        bonusLevels,
    };
});


export type OrderProfilePayload = {}

export type DeliveryCalculateEntry = {
    locationHash: string
    tag?: string | number | null
    attrs: OrderAttributesValue
    fetchedAt: number
    calc: DeliveryCalculate
}
