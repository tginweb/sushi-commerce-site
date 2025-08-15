<template>
  <div class="c-product-actions">
    <div class="flex q-gutter-sm no-wrap">
      <q-btn
          :icon="inFav ? ICONS.favoriteFilled : ICONS.favorite"
          @click.prevent.stop="favToggle"
          :title="inFav ? 'удалить из избранного' : 'добавить в израбранное'"
          v-if="!product.IS_SALE_SPECIAL"
          color="white"
          :text-color="inFav ? 'red-8' : 'primary'"
          unelevated
          size="13px"
          padding="6px 6px"
          dense
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import {useProductActions} from "./hooks";
import {ICONS} from "@/assets/icons";
import {toRefs} from "vue";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export type ProductSetItemsProps = {
  product: ProductElementModel
  hover?: boolean
}

const props = withDefaults(defineProps<ProductSetItemsProps>(), {
  hover: false
})

const {hover} = toRefs(props)

const {product, favToggle, inFav, favMutating} = useProductActions(props)

</script>

<style scoped lang="scss">


</style>
