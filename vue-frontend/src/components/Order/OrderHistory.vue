<template>
  <div class="history-order q-mb-lg q-px-sm q-px-lg-md q-py-md" v-if="order">

    <div class="flex q-mb-md q-pb-md ">
      <div>
        {{ timestampToFormat(order.DATE_INSERT, 'date') }}
      </div>
      <div class="q-ml-auto">
        № {{ order.ACCOUNT_NUMBER }}
      </div>
    </div>

    <div class="c-basket q-gutter-y-md">

      <div
          v-for="(item, index) in basketItems"
          :key="index"
          class="row q-col-gutter-x-sm q-col-gutter-x-lg-md items-center"
      >

        <div class="col-3 col-lg-3 col-xl-2 cursor-pointer" @click="onNavProduct(item)">
          <img v-if="item.ELEMENT?.LIST_IMAGE" :src="imageResolve(item.ELEMENT?.LIST_IMAGE.SRC)"/>
        </div>

        <div class="col-10 col-lg-10 col-xl-11 cursor-pointer s-font-xs s-font-md-sm s-font-lg-md"
             @click="onNavProduct(item)">
          {{ item.NAME }}
        </div>

        <div class="col-2 col-lg-2 col-xl-2 text-weight-bold s-text-nowrap">
          x {{ item.QUANTITY }}
        </div>

        <div class="col-2 col-lg-2 col-xl-2 text-weight-bold s-text-nowrap">
          {{ formatPrice(item.FINAL_PRICE_BASE) }}
        </div>

        <div class="col-7 col-lg-7 col-xl-7 text-right">
          <q-btn
              :icon="ICONS.basket"
              :label="$q.screen.gt.sm ? 'в корзину' : undefined"
              class="s-font-2xs s-font-lg-sm"
              color="primary"
              outline
              @click="onBasketAdd(item)"
          />
        </div>
      </div>
    </div>

    <q-btn
        class="s-font-xs s-font-md-md q-mt-md"
        color="primary"
        label="повторить весь заказ"
        unelevated
        @click="onBasketRepeat"
    />

  </div>
</template>

<script setup lang="ts">
import {useQuasar} from "quasar";
import {computed, defineProps, toRefs, watch, withDefaults} from "vue";
import {BasketItemWithElementModel, OrderViewProps, useOrder} from "@/components/Order/hooks";
import {useEntityDataloaderInstance} from "@/core/hooks/useEntityDataloaderInstance";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {useCurrency} from "@/modules/shop/store/currency";
import timestampToFormat from "@/core/util/date/timestampToFormat";
import {useRouter} from "vue-router";
import {useImager} from "@/core/store/imager";
import {ICONS} from "@/assets/icons";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

const {formatPrice} = useCurrency()
const {imageResolve} = useImager()

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
  actions,
  onBasketAdd,
  onBasketRepeat
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

watch(basketItems, () => {
  console.log(basketItems.value)
})
const router = useRouter()

const onNavProduct = (basketItem: BasketItemWithElementModel) => {
  router.push('/product/' + basketItem.PRODUCT_ID)
}

</script>

<style lang="scss" scoped>
.history-order {
  border: 1px solid #ddd;
  border-radius: 10px;
}
</style>
