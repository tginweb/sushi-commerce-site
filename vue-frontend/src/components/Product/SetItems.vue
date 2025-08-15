<template>
  <div class="product-set-items">
    <div :class="itemsClass">
      <div
          v-for="item in items"
          :key="item.PRODUCT_ID"
          class="c-item text-grey-8"
      >
        <q-tooltip class="c-tooltip text-white s-font-sm" max-width="200px">
          {{ item.product?.PREVIEW_TEXT }}
        </q-tooltip>
        <span class="c-quantity q-pr-2xs s-font-xs" v-if="item.QUANTITY>1">
        {{ item.QUANTITY }} x
      </span>
        <span class="c-name s-font-2xs">
        {{ item.product?.NAME }}
      </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {toRefs} from "vue";
import {ProductSetItemModel} from "@/modules/shop/store/catalog";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export type ProductSetItemsProps = {
  items: ProductSetItemModel[],
  product: ProductElementModel,
  itemsClass?: any
}

const props = withDefaults(defineProps<ProductSetItemsProps>(), {
  items: () => [],
  itemsClass: 'q-pt-sm flex q-gutter-x-sm q-gutter-y-sm q-gutter-md-sm text-grey-6 s-font-sm q-mb-sm'
})

const {items, itemsClass} = toRefs(props)


</script>

<style scoped lang="scss">

.product-set-items {

}

.c-item {
  line-height: 1.2;
  border: 1px solid #ccc;
  border-radius: 12px;
  padding: 4px 9px;
  position: relative;
}

.c-quantity {
  font-weight: bold;
}

.c-tooltip {

}
</style>
