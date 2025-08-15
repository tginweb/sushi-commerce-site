<template>
  <div>

    <div v-if="false">
      <q-btn label="clear" @click="basketOp({action: 'clear'})"/>

      <q-spinner v-if="basketSync.isPending"/>
      <br>
      <br>
      Synced: {{ basketSynced }}<br>
    </div>

    <template v-if="basketItemsCalculated.length">
      <div class="q-gutter-y-md">
        <BasketItem
            v-for="(item, index) in basketItemsCalculated"
            :item="item"
            :key="index"
        />

        <div class="flex items-center">

          <div class="s-font-xs text-grey-8">
            Общий вес:
            <span class="text-grey-8 text-weight-bold">2400г</span>
          </div>

          <div class="q-ml-auto">
            <q-btn
                label="очистить корзину"
                flat
                dense
                size="12px"
                color="red-8 q-mx-auto"
                :icon="ICONS.trush"
                class="--icon-same"
                @click="onClear"
            />
          </div>

        </div>

      </div>
    </template>
    <template v-else>
      <div class="text-center s-font-sm">
        в корзине пока нет товаров
      </div>
    </template>

    <ProductsCarousel
        :items="upsaleProducts"
        v-if="false"
    />

  </div>

</template>

<script setup lang="ts">

import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import BasketItem from "./BasketItem.vue";
import ProductsCarousel from "@/components/ProductsCarousel/ProductsCarousel.vue";
import {computed} from "vue";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {useVorderComponent} from "@/components/VOrder/hooks";
import {ICONS} from "@/assets/icons";
import {useQuasar} from "quasar";

const $q = useQuasar()

const catalogStore = useCatalogStore()
const {products} = storeToRefs(catalogStore)

const vorderComponent = useVorderComponent()

const {validate, attrs} = vorderComponent

const vorderStore = useVorderStore()

const {basketSync, basketOp} = vorderStore
const {basketSynced, basketItemsCalculated} = storeToRefs(vorderStore)

const upsaleProducts = computed(() => {
  return products.value.slice(0, 10)
})

const onClear = () => {
  $q.dialog({
    title: 'Очистить корзину?',
    cancel: 'Отмена',
  }).onOk(() => {
    basketOp({action: 'clear'})
  })
}

</script>

<style>

</style>
