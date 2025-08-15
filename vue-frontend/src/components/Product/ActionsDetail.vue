<template>
  <div class="c-product-actions-detail">
    <div class="flex q-gutter-md justify-center">
      <q-btn
          @click.prevent.stop="favToggle"
          class="q-px-sm"
          color="primary-brown-1"
          dense
          size="14px"
          text-color="dark"
          unelevated
      >
        <template v-if="inFav">
          <q-icon
              :name="ICONS.favoriteFilled"
              class="q-mr-sm"
              color="actions-red"
              size="16px"
              v-if="inFav"
          />
          <span>в избранном</span>
        </template>
        <template v-else>
          <q-icon
              :name="ICONS.favorite"
              class="q-mr-sm"
              color="dark"
              size="16px"
          />
          <span>в избранное</span>
        </template>
      </q-btn>
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
