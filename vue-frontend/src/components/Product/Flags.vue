<template>
  <div
      class="c-product-flags s-font-3xs s-font-md-xs"
      v-if="items.length"
  >
    <div class="c-items q-gutter-sm flex">
      <div
          v-for="item of items"
          :key="item.ID"
          class="c-item"
      >
        <div
            :class="[item.CLASS]"
            :style="{
            backgroundColor: item.BG_COLOR_HEX ? item.BG_COLOR_HEX : undefined,
            color: item.TEXT_COLOR_HEX ? item.TEXT_COLOR_HEX : undefined
          }"
            class="c-item__block leading-none text-white text-center q-px-xs q-py-xs"
            v-if="!item.IMAGE && !item.ICON"
        >
          <span class="gt-sm">{{ item.NAME }}</span>
          <span class="lt-md">{{ item.NAME_SHORT }}</span>
        </div>
        <div
            v-else-if="item.IMAGE"
            class="c-item__image"
        >
          <img :src="imageResolve(item.IMAGE.SRC, 60, true)"/>
          <q-tooltip class="c-tooltip text-white s-font-sm" max-width="200px">
            {{ item.NAME }}
          </q-tooltip>
        </div>
        <div
            v-else-if="item.ICON"
            class="c-item__icon"
            :title="item.NAME || ''"
        >
          <q-icon
              :name="item.ICON"
              :color="item.COLOR || ''"
          />
          <q-tooltip class="c-tooltip text-white s-font-sm" max-width="200px">
            {{ item.NAME }}
          </q-tooltip>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

import {toRefs} from "vue";
import {ProductFlag} from "@/gql/gen";
import {useImager} from "@/core/store/imager";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export type ProductSetItemsProps = {
  items: ProductFlag[],
  product: ProductElementModel
}

const props = withDefaults(defineProps<ProductSetItemsProps>(), {
  items: () => []
})

const {items} = toRefs(props)

const {imageResolve} = useImager()

</script>

<style scoped lang="scss">

.c-item__image {
  position: relative;

  img {
    max-width: 33px;
  }
}

.c-item__icon {
  position: relative;

}

</style>
