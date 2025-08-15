<template>
  <div class="row q-col-gutter-sm no-wrap">

    <div class="col-4">
      <div v-if="product && product.LIST_IMAGE?.SRC" @click="onClick" class="cursor-pointer">
        <img
            v-if="product"
            :src="imageResolve(product.LIST_IMAGE?.SRC, 100)"
        />
      </div>
    </div>
    <div class="col-14">
      <div class="cursor-pointer" @click="onClick">
        <div class="s-font-sm text-weight-bold" style="line-height: 1.3em;">
          {{ productName }}
        </div>
        <div class="">
          <span class="s-font-sm" v-html="formatPrice(item.calc.price)"/>
        </div>
      </div>
    </div>
    <div class="col-6 text-right">
      <QuantityInput
          :model-value="item.model.fields.QUANTITY"
          @update:model-value="onQuantityUpdate"
          :btn="{
            size: '16px',
            outline: true,
            color: 'grey-7',
            class: 'q-px-sm',
            flat: true,
            dense: true
          }"
          :show-action-tooltip="true"
          class="c-quantity"
          control-class=""
          :input-disable="true"
      />
    </div>
  </div>
</template>

<script setup lang="ts">

import {useVorderStore} from "src/modules/shop/store/vorder";

import {computed, toRefs} from "vue";
import {useImager} from "@/core/store/imager";
import {useCurrency} from "@/modules/shop/store/currency";
import QuantityInput from "@/components/QuantityInput.vue";
import {useRouter} from "vue-router";
import toInt from "@/core/util/toInt";
import {useVorderBasketStore} from "@/modules/shop/store/vorder/basket";
import {BasketItemCalculated} from "@/modules/shop/store/vorder/util/benefit";

const basketStore = useVorderBasketStore()
const router = useRouter()
const {imageResolve} = useImager()
const {formatPrice} = useCurrency()
const {basketOp} = basketStore

type BasketItemProps = {
  item: BasketItemCalculated
}

const props = withDefaults(defineProps<BasketItemProps>(), {})

const {item} = toRefs(props)

const vorderStore = useVorderStore()

const productId = computed(() => item.value.model.fields.PRODUCT_ID)

const product = computed(() => item.value.model.product)

const productName = computed(() => product.value ? product.value.NAME : item.value.model.fields.NAME)

const onClick = () => {
  if (product.value) {
    router.push('/product/' + productId.value)
  }
}

const onQuantityUpdate = (value: number) => {
  value = toInt(value)
  if (value >= 0) {
    basketOp({
      action: 'quantity',
      productId: productId.value,
      quantity: value,
    })
  }
}

</script>

<style scoped lang="scss">


</style>
