<template>

  <div class="order-summary">
    <template v-if="order.COUPONS && order.COUPONS.length">
      <div
          v-for="coupon of order.COUPONS"
          :key="coupon.ID"
          class="c-line flex no-wrap "
      >
        <div class="q-mr-sm">
          Промокод:
        </div>
        <div class="q-ml-auto text-right  text-weight-bold">
          {{ coupon.COUPON }}
        </div>
      </div>
    </template>
    <div v-else-if="order.BONUSES" class="c-line flex">
      <div>Списать бонусы</div>
      <div class="q-ml-auto text-actions-red text-weight-bold">
        {{ order.BONUSES }}
      </div>
    </div>
    <div v-else-if="order.DISCOUNT_PERCENT" class="c-line flex">
      <div>{{ order.DISCOUNT_REASON ? order.DISCOUNT_REASON : 'Скидка' }}</div>
      <div class="q-ml-auto text-actions-red text-weight-bold">
        {{ order.DISCOUNT_PERCENT }}%
      </div>
    </div>

    <div v-if="order.PRICE_DELIVERY_BASE" class="c-line flex">
      <div>
        <div>Стоимость доставки:</div>
        <div class="s-font-3xs text-weight-medium text-red-7" style="margin-top: -5px;"
             v-if="order.DELIVERY_FREE_FROM_PRICE">
          бесплатно от {{ formatPrice(order.DELIVERY_FREE_FROM_PRICE) }}
        </div>
      </div>
      <div class="q-ml-auto">
        <span class="c-price-no-discount q-mr-sm" v-if="order.PRICE_DELIVERY_BASE !== order.PRICE_DELIVERY">
          {{ formatPrice(order.PRICE_DELIVERY_BASE, true) }}
        </span>
        {{ formatPrice(order.PRICE_DELIVERY, true) }}
      </div>
    </div>

    <div class="c-line flex">
      <div>
        <span class="text-weight-bold">Итого к оплате</span>
      </div>
      <div class="q-ml-auto">
            <span class="c-price-no-discount q-mr-sm" v-if="order.PRICE_PAY !== order.PRICE_PAY_BASE">{{
                formatPrice(order.PRICE_PAY_BASE, true)
              }}</span>
        <span class="s-font-sm text-weight-bold">
              {{ formatPrice(order.PRICE_PAY, true) }}
            </span>
      </div>
    </div>

  </div>

</template>

<script lang="ts" setup>
import {defineProps, withDefaults} from "vue";
import {Order} from "@/gql/gen";
import {useCurrency} from "@/modules/shop/store/currency";

const {formatPrice} = useCurrency()

const props = withDefaults(defineProps<{
  order: Order;
}>(), {})

</script>

<style lang="scss" scoped>


</style>
