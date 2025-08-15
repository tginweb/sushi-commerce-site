<template>
  <StackItemModal
      v-bind="bind"
      :title="title"
      :dialog="{
        closePosition: 'outside-right',
        headerHide: true,
        containerClass: 'q-py-xl q-px-lg',
        width: '850px',
        bodyMaxHeight: '70vh'
      }"
  >
    <ProductModal
        :product="product"
        :mode="stackItemState.mode"
        v-if="product"
    />
  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {computed, ref} from "vue";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {storeToRefs} from "pinia";
import ProductModal from "@/components/Product/ProductModal.vue";

const props = withDefaults(defineProps<StackComponentProps & {
  id: string
}>(), {})

const {id} = props

const title = ref('Продукт')

const {bind, stackItemState} = useStackComponent<StackItemModalState>(props)

const catalog = useCatalogStore()

const {productsModelsById} = storeToRefs(catalog)

const product = computed(() => productsModelsById.value[id])

</script>
<style>

</style>
