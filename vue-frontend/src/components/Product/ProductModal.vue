<template>
  <div
      class="product-modal"
      v-bind="bindContainer"
  >

    <div class="row q-col-gutter-x-md q-col-gutter-x-lg-lg">

      <div class="col-24 col-md-11">

        <div class="flex column no-wrap" style="position: sticky; top:0; max-height1: 70vh; overflow1: hidden;">

          <div class="c-media">

            <div class="c-media__top q-px-sm q-pt-sm flex">
              <ProductFlags
                  :items="product.FLAG_ITEMS"
                  :product="product"
                  class="c-flags"
              />

            </div>

            <div class="c-media__thumb text-center">
              <q-img
                  fit="contain"
                  :src="imageUrl"
                  class="c-media__thumb__img"
                  :ratio="4/3"
              />
            </div>

          </div>

          <ProductActionsDetail
              :product="product"
              class=""
          />

        </div>

      </div>

      <div class="col-24 col-md-13 flex column no-wrap">

        <h1
            v-if="!isSheet"
            class="i-title s-font-sm-2xl s-font-md-3xl s-font-lg-4xl s-font-xl-4xl text-weight-bolder leading-e4 q-ma-none q-m"
        >
          {{ product.NAME }}
        </h1>

        <div
            v-if="descTeaser"
            class="i-teaser q-mt-md-md -text-weight-bold" v-html="descTeaser"
        />

        <Chemistry
            v-if="product.PROPERTIES.BELKI"
            :product="product"
        />

        <ProductSetItemsDetails
            :product="product"
            :items="setItems"
            v-if="!!setItems.length"
            class="q-mt-md"
        />

        <div v-if="!isSheet" class="bg-white q-mt-auto q-pt-md q-mb-sm" style="position:sticky;bottom:0;">

          <div class="row q-col-gutter-md items-center" v-if="!product.IS_SALE_SPECIAL">

            <div class="col-12 q-mr-auto" v-if="priceAvailable">

              <div class="col-auto flex items-center q-gutter-sm ">

                <div class="i-price leading-none flex">

                  <div
                      class="i-price__discounted s-font-4xl leading-none text-weight-bold"
                      v-html="formatPrice(calc.price)"
                  />

                </div>

                <div
                    v-if="weight"
                    class="i-measure text-grey-6 leading-none q-ml-md"
                >
                  /&nbsp;&nbsp;{{ weight }} гр.
                </div>

              </div>

            </div>

            <div class="col-12 flex items-center">

              <q-btn
                  class="full-width"
                  color="primary"
                  dense
                  label="в корзину"
                  unelevated
                  @click="onBasketAdd"
                  :loading="basketMutating"
                  v-if="!inBasket"
              />
              <div v-else class="flex full-width">
                <QuantityInput
                    :model-value="basketItem.fields.QUANTITY"
                    @update:model-value="onBasketQuantity"
                    :btnOutline="true"
                    :show-action-tooltip="true"
                    btnSize="14px"
                    class="c-quantity q-ml-auto"
                    controlClass="q-gutter-xs"
                    :btn="{
                        color: 'primary',
                        outline: false,
                        class: 'q-px-md'
                      }"
                />
              </div>

            </div>

          </div>

        </div>

      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
import {ProductCardProps, useProductView} from "./hooks";
import {useCurrency} from "@/modules/shop/store/currency";
import {ProductBenefit} from "@/gql/gen";
import Result from "@/core/classes/Result";
import {useAlerts} from "@/core/store/alerts";
import {useMounted} from "@vueuse/core";
import {TStackItemModalModes} from "@/packages/stack-router/types";
import {computed, toRefs} from "vue";
import Chemistry from "@/components/Product/Chemistry.vue";
import QuantityInput from "@/components/QuantityInput.vue";
import ProductSetItemsDetails from "@/components/Product/SetItemsDetails.vue";
import ProductFlags from "@/components/Product/Flags.vue";
import ProductActionsDetail from "./ActionsDetail.vue";

export type ProductCardPropsModal = ProductCardProps & {
  mode: TStackItemModalModes
}

const props = withDefaults(defineProps<ProductCardPropsModal>(), {})

const {mode} = toRefs(props)

const isSheet = computed(() => mode.value === 'sheet')

const {
  onClick,
  product,
  onBasketAdd,
  onBasketQuantity,
  weight,
  basketItem,
  bindContainer,
  view,
  flags,
  imageUrl,
  bindLinkComponent,
  bindLink,
  bindLinkListeners,
  descTeaser,
  basketMutating,
  priceAvailable,
  calc,
  setItems,
  inBasket
} = useProductView(props, {
  calcOptions: {
    round: true
  },
})

const {formatPrice} = useCurrency()
const {showAlerts} = useAlerts()

const setSpecial = (benefit: ProductBenefit, quantity: number) => {
  const result = new Result()
  basketItem.value.setSpecial(benefit.PRODUCT_ID, quantity, benefit, result)
  if (!result.isSuccess()) {
    showAlerts(result.messages)
  }
}

const mounted = useMounted()


</script>

<style scoped lang="scss">

.product-modal {
  //overflow: hidden;
}

.c-media {
  position: relative;
}

.c-media__top {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 2;
}

</style>
