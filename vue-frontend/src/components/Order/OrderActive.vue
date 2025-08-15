<template>
  <div class="com-order" v-if="order">

    <div class="row q-col-gutter-md q-mb-md">

      <div class="col-24 col-sm-grow q-pr-md">

        <div class="s-font-xl s-font-md-xxl text-weight-bold q-my-none q-mb-sm">
          Заказ №{{ order.ACCOUNT_NUMBER || order.ID }} на {{ formatPrice(order.PRICE) }}
        </div>

        <div class="text-grey-8">от {{ order.DATE_FORMATTED }}</div>
      </div>

      <div class="col-24 col-sm-auto">
        <div class="flex column-md q-gutter-x-md">
          <div class="s-font-sm s-font-md-sm text-grey-8 q-mb-sm text-right">Статус заказа:</div>
          <div class="c-status s-font-md s-font-md-md text-right text-weight-bold text-primary">
            <q-spinner/>
            {{ order.CSTATUS_NAME }}
          </div>
        </div>
      </div>

    </div>

    <div class="q-mb-md q-mb-md-lg -q-pb-md -border-b-1 -border-primary-brown-1" v-if="order.IS_ACTIVE">
      <OrderStatuses
          :order="order"
          :statuses="statuses"
          icon-class="s-font-xl s-font-sm-2xl s-font-md-4xl s-font-lg-5xl s-font-xl-6xl"
          :icon-size="null"
          :show-labels="$q.screen.gt.sm"
      />
    </div>

    <div class="q-mb-sm s-font-2xs text-green text-weight-bold" v-if="order.IS_ACTIVE">
      Для внесения изменений в текущий заказ, свяжитесь с оператором доставки
    </div>

    <OrderActions
        :order="order"
        :dense="$q.screen.lt.md"
        :actions="actions"
        class="q-pt-sm q-pb-lg q-pb-md-lg -justify-between"
        item-class="s-font-sm s-font-md-md"
    />

    <div class="row q-col-gutter-x-xl q-col-gutter-y-md ">

      <div class="col-24 col-md-12 col-lg-12">

        <div class="text-weight-bold q-mb-md s-font-lg">
          Информация о заказе
        </div>

        <OrderFields
            :order="order"
            :fields="fields"
            item-class="col-24"
            class="q-col-gutter-md"
        />

      </div>

      <div class="col-24 col-md-12 col-lg-12">
        <div class="text-weight-bold q-mb-md s-font-lg">
          Состав заказа
        </div>

        <OrderBasket :order="order" :basket="basketItems"/>
        <OrderSummary :order="order" class="c-basket__summary q-py-sm q-mt-sm border-t-2 border-primary-brown-1"/>
      </div>

    </div>

  </div>
</template>

<script setup lang="ts">
import {useQuasar} from "quasar";
import {computed, defineProps, toRefs, withDefaults} from "vue";
import {BasketItemWithElementModel, OrderViewProps, useOrder} from "@/components/Order/hooks";
import OrderStatuses from "@/components/Order/components/OrderStatuses.vue";
import OrderFields from "@/components/Order/components/OrderFields.vue";
import OrderActions from "@/components/Order/components/OrderActions.vue";
import OrderBasket from "@/components/Order/components/OrderBasket.vue";
import OrderSummary from "@/components/Order/components/OrderSummary.vue";
import {useEntityDataloaderInstance} from "@/core/hooks/useEntityDataloaderInstance";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {useCurrency} from "@/modules/shop/store/currency";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

const {formatPrice} = useCurrency()
const $q = useQuasar()

const props = withDefaults(defineProps<OrderViewProps>(), {
  refetchEnable: false,
  refetchInterval: 1000 * 20,
  view: 'detail'
})

const {order} = toRefs(props)

const {
  statuses,
  fields,
  actions
} = useOrder(props)

const productIds = computed(() => order.value.BASKET.map(item => item.PRODUCT_ID))
const {loader} = useCatalogStore()
const {entitiesById: basketProductsById} = useEntityDataloaderInstance<ProductElementModel>({
  ids: productIds,
  loader: loader,
  subscriberIdStrategy: {
    random: true,
    prefix: 'order'
  }
})

const basketItems = computed<BasketItemWithElementModel[]>(() => {
  return order.value.BASKET.map(item => {
    return {
      ...item,
      ELEMENT: basketProductsById.value[item.PRODUCT_ID]
    }
  })
})

</script>

<style lang="scss" scoped>
.items {
  :deep() {
    .i-wrap {
      padding-left: 30px !important;
      padding-right: 30px !important;
    }
  }
}

:deep() {
  .q-item {
    min-height: auto;
  }
}

.q-expansion-item.q-expansion-item--expanded {
  border-bottom: 1px solid map-get($theme-colors, "primary-brown-gray-1");
}

</style>
