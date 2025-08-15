<template>
  <div
      class="product-set-items"
  >
    <div
        v-if="!model"
        class="c-items-collapsed q-pt-sm flex q-gutter-x-sm q-gutter-y-sm q-gutter-md-sm text-grey-6 s-font-sm q-mb-sm"
    >
      <div
          v-for="item in items"
          :key="item.PRODUCT_ID"
          class="c-item-collapsed text-grey-8 s-font-sm cursor-pointer q-px-sm q-py-xs"
          @click="model = !model"
      >
        <q-tooltip class="c-tooltip text-white s-font-sm" max-width="200px">
          {{ item.product?.PREVIEW_TEXT }}
        </q-tooltip>
        <span class="c-quantity q-pr-2xs text-weight-bold" v-if="item.QUANTITY>1">
          {{ item.QUANTITY }} x
        </span>
        <span class="c-name">
          {{ item.product?.NAME }}
        </span>
      </div>
    </div>
    <div
        v-else
        class="c-items-expanded flex column no-wrap q-gutter-y-sm"
        :class="itemsClass"
    >
      <div
          v-for="item in items"
          :key="item.PRODUCT_ID"
          class="c-item-expanded q-px-sm q-py-sm text-grey-8 cursor-pointer flex column"
          @click="model = !model"
      >
        <div class="c-header text-grey-9 s-font-xs text-weight-bold">
          <span class="c-quantity q-pr-2xs" v-if="item.QUANTITY>1">
            {{ item.QUANTITY }} x
          </span>
          <span class="c-name">
            {{ item.product?.NAME }}
          </span>
        </div>
        <div class="c-body s-font-xs">
          {{ item.product?.PREVIEW_TEXT }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {ProductSetItemModel} from "@/modules/shop/store/catalog";
import {toRefs} from "vue";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export type ProductSetItemsProps = {
  items: ProductSetItemModel[],
  product: ProductElementModel,
  expanded?: boolean,
  itemsClass?: any
}

const props = withDefaults(defineProps<ProductSetItemsProps>(), {
  items: () => [],
  expanded: false,
})

const {items, itemsClass, expanded} = toRefs(props)

const model = defineModel<boolean>()

</script>

<style scoped lang="scss">

.c-item-collapsed {
  line-height: 1.2;
  border: 1px solid #ccc;
  border-radius: 12px;
  position: relative;
}

.c-item-expanded {
  line-height: 1.2;
  border: 1px solid #ccc;
  border-radius: 12px;
  position: relative;
  gap: 2px;
}


</style>
