<template>

  <div class="component q-px-md q-py-xs flex">

    <q-btn
        flat
        dense
        :icon="ICONS.evaHomeOutline"
        label="Меню"
        stack
        class="c-btn-normal"
    />

    <q-btn
        flat
        dense
        icon="search"
        label="Поиск"
        stack
        class="c-btn-normal"
    />

    <div v-if="basketEmpty" style="">
      <q-btn
          :icon="ICONS.basket"
          size="12px"
          round
          color="primary"
          class=""
      >
      </q-btn>
    </div>
    <div v-else>
      <div class="c-btn-cart">
        <q-btn
            :icon="ICONS.basket"
            round
            color="primary"
            stack
            size="20px"
            dense
        >
          <span class="c-btn-cart__price">
               <AnimatedNumber
                   :number="pricePayDiscounted"
                   append=" ₽"
               />
          </span>
        </q-btn>
      </div>
    </div>

    <q-btn
        flat
        dense
        :icon="ICONS.person"
        label="Профиль"
        stack
        class="c-btn-normal"
        to="/personal/dashboard"
    />

    <q-btn
        flat
        dense
        icon="more_horiz"
        label="Еще"
        stack
        class="c-btn-normal"
    />

  </div>

</template>

<script setup lang="ts">

import {ICONS} from "@/assets/icons";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useCurrency} from "@/modules/shop/store/currency";

const vorderStore = useVorderStore()
const {formatPrice} = useCurrency()

const {basketEmpty, basketItems, pricePayDiscounted} = storeToRefs(vorderStore)

</script>

<style lang="scss" scoped>

.component {
  justify-content: space-between;
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: #fff;
  z-index: 5;
  border-top: 1px solid #eee;
  box-shadow: 0px 4px 8px 6px rgba(34, 60, 80, 0.2);
}

.c-btn-cart {
  position: absolute;
  top: -12px;
  left: 50%;
  transform: translateX(-50%);

  .q-btn {
    position: relative;
    margin: auto;

    :deep() {

      .q-icon {
        margin-top: -5px;
        font-size: 13px;
      }

      .block {
        line-height: 1;
        position: absolute;
        white-space: nowrap;
        font-size: 12px;
        font-weight: bold;
        bottom: 13px;
      }
    }
  }
}

.c-btn-cart__price {
  font-size: 12px;
  line-height: 1;
  margin-top: 4px;
  display: block;
}

.c-btn-normal {
  font-size: 11px;

  :deep() {
    .q-icon {
      font-size: 30px;
    }

    .block {
      margin-top: -3px;
      display: none !important;
    }
  }
}

</style>
