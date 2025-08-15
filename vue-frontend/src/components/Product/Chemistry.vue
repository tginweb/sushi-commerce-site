<template>
  <div v-if="chemistry.BELKI" class="c-product-chemistry q-mt-md s-font-sm ">
    <div class="c-chemistry__table row no-wrap justify-between bg-grey-3 text-center q-px-md q-py-sm">

      <div class="col-auto">
        <div class="__val">на {{ chemistry.WEIGHT }}г</div>
        <div class="__name">пищ. цен.</div>
      </div>
      <div class="col-auto" v-if="chemistry.KKAL">
        <div class="__val">{{ chemistry.KKAL }}</div>
        <div class="__name">ккал</div>
      </div>
      <div class="col-auto">
        <div class="__val">{{ chemistry.BELKI }}</div>
        <div class="__name">белки</div>
      </div>
      <div class="col-auto">
        <div class="__val">{{ chemistry.ZHIRY }}</div>
        <div class="__name">жиры</div>
      </div>
      <div class="col-auto">
        <div class="__val">{{ chemistry.UGLEVODY }}</div>
        <div class="__name">угл</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

import {computed, toRefs} from "vue";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export type ProductSetItemsProps = {
  product: ProductElementModel
}

const props = withDefaults(defineProps<ProductSetItemsProps>(), {})

const {product} = toRefs(props)

const chemistry = computed(() => {
  const koef = parseFloat(product.value.PROPERTIES.WEIGHT || '0') / 100
  //const koef = 1
  return {
    WEIGHT: product.value.PROPERTIES.WEIGHT,
    KKAL: Math.round(parseFloat(product.value.PROPERTIES.KKAL || '0') * koef),
    BELKI: Math.round(parseFloat(product.value.PROPERTIES.BELKI || '0') * koef),
    ZHIRY: Math.round(parseFloat(product.value.PROPERTIES.ZHIRY || '0') * koef),
    UGLEVODY: Math.round(parseFloat(product.value.PROPERTIES.UGLEVODY || '0') * koef),
  }
})

</script>

<style lang="scss" scoped>

.c-chemistry__table {

  border-radius: 10px;

  > div {
    padding-right: 6px;
    padding-left: 6px;
    text-align: center;

    &:first-child {

    }

    &:last-child {

    }

    .__name {
      font-size: 11px;
    }
  }
}

</style>
