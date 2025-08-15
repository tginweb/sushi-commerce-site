<template>
  <div class="order-basket">
    <div
        v-for="basketItem in basket"
        :key="basketItem.ID"
        class="c-basket__item q-py-sm border-primary-brown-1  cursor-pointer"
        @click="onItemClick(basketItem)"
    >
      <div class="row items-center q-col-gutter-sm q-col-gutter-md-sm q-col-gutter-lg-md">

        <div class="col-3">
          <img
              v-if="basketItem?.ELEMENT && basketItem.ELEMENT.LIST_IMAGE"
              :src="imageResolve(basketItem.ELEMENT.LIST_IMAGE.SRC, 'r100')"
          />
        </div>

        <div class="col-15">
          <div class="s-font-sm s-font-md-sm s-font-lg-md leading-e4">{{ basketItem.NAME }}</div>
        </div>

        <div class="col-6 text-right text-weight-medium s-font-xs s-font-md-sm s-font-lg-md"
             style="white-space: nowrap">
          <span style="" v-if="basketItem.FINAL_PRICE_BASE">{{ formatPrice(basketItem.FINAL_PRICE_BASE) }}</span>
          <span class="text-primary-brown-gray-4 text-weight-bold">
            x {{ basketItem.QUANTITY }}
          </span>
        </div>

      </div>

    </div>
  </div>
</template>

<script lang="ts" setup>

import {defineProps, toRefs, withDefaults} from "vue";
import {Order} from "@/gql/gen";
import {useImager} from "@/core/store/imager";
import {useCurrency} from "@/modules/shop/store/currency";
import {BasketItemWithElementModel} from "@/components/Order/hooks";
import {useRouter} from "vue-router";

const {imageResolve} = useImager()
const {formatPrice} = useCurrency()

const props = withDefaults(defineProps<{
  order: Order;
  hideSummary?: boolean;
  basket: BasketItemWithElementModel[];
}>(), {
  hideSummary: false
})

const {basket} = toRefs(props)

const router = useRouter()

const onItemClick = (item: BasketItemWithElementModel) => {
  router.push('/product/' + item.PRODUCT_ID)
}

</script>

<style lang="scss" scoped>


</style>
