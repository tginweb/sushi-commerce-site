<template>
  <QueryView :query="activeOrdersRequest">
    <template v-slot="{result}">
      <div class="">
        <OrderActive
            v-for="(item, index) of ordersActive"
            :key="item.ID || index"
            :order="item"
            :nav="true"
            :index="index"
            class="c-order bg-white q-px-md q-py-md q-mb-lg"
        />
      </div>
    </template>
    <template #empty>
      <div class="text-primary-brown-gray-4">
        нет активных заказов
      </div>
    </template>
  </QueryView>
</template>
<script setup lang="ts">

import OrderActive from "@/components/Order/OrderActive.vue";
import {storeToRefs} from "pinia";
import {onMounted} from "vue";
import QueryView from "@/components/Query/QueryView.vue";
import {useShopStore} from "@/modules/shop/store/shop";
import {useOrdersStore} from "@/modules/shop/store/orders";

const saleStore = useShopStore()
const ordersStore = useOrdersStore()
const {activeOrdersRequest} = ordersStore
const {ordersActive} = storeToRefs(ordersStore)

const props = withDefaults(defineProps<{}>(), {})

onMounted(() => {
  activeOrdersRequest.query()
})

</script>
<style>
.c-order {
  border: 1px solid #ddd;
  border-radius: 10px;
}
</style>
