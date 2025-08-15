<template>
  <div class="c-vorder-summary">

    <div class="q-gutter-y-sm">

      <div
          class="c-line"
          v-if="tab === 'basket'"
      >
        <div class="c-line__title">
          Доставка
        </div>
        <div class="c-line__value s-font-xs">
          ул. Лермонтова, д. 31
        </div>
      </div>

      <template v-if="!basketEmpty">
        <div class="c-line text-weight-bold">
          <div class="c-line__title">
            Итого к оплате
          </div>
          <div class="c-line__value s-font-md">

            <span
                v-if="pricePay > pricePayDiscounted"
                class="price-old q-mr-sm"
            >{{ pricePay }} ₽</span>

            <AnimatedNumber
                :number="pricePayDiscounted"
                append=" ₽"
            />

          </div>
        </div>
      </template>

    </div>
  </div>
</template>

<script setup lang="ts">

import {storeToRefs} from "pinia";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {useCurrency} from "@/modules/shop/store/currency";

const {formatPrice} = useCurrency()

const props = withDefaults(defineProps<{}>(), {})

const vorderStore = useVorderStore()
const {tab, basketEmpty, pricePay, pricePayDiscounted} = storeToRefs(vorderStore)

</script>

<style scoped lang="scss">

.c-line {
  display: flex;
  flex-direction: row;
  flex-wrap: nowrap;
  gap: 10px;
  align-items: center;
}

.c-line__title {
  font-size: 15px;
}

.c-line__value {
  font-size: 15px;
  margin-right: auto;
  flex-grow: 1;
  text-align: right;
}

</style>
