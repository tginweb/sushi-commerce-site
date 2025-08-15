<template>
  <div>
    <Carousel
        name=""
        v-bind="options"
    >
      <CarouselSlide
          v-for="product in items"
          :key="product.ID"
      >
        <ProductCardSlide
            :product="product"
        />
      </CarouselSlide>
      <template #addons>
        <CarouselNavigation/>
        <CarouselPagination v-if="pagerShow"/>
      </template>
    </Carousel>
  </div>
</template>

<script setup lang="ts">
import {computed, toRefs} from "vue";
import ProductCardSlide from "@/components/Product/ProductCardSlide.vue";
import {QDialogProps} from "quasar";
import {CarouselConfig} from "vue3-carousel";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

type DialogNativeProps = Omit<QDialogProps, 'modelValue'>

export interface DialogProps extends DialogNativeProps {
  items?: ProductElementModel[]
  itemsToShow?: number
  itemsToScroll?: number
  wrapAround?: boolean
  gap?: number
  pagerShow?: boolean
}

const props = withDefaults(defineProps<DialogProps>(), {
  items: () => [],
  itemsToShow: 2.5,
  itemsToScroll: 2,
  wrapAround: false,
  gap: 10,
  pagerShow: false
})

const {wrapAround, gap, pagerShow} = props

const {items, itemsToShow, itemsToScroll} = toRefs(props)

const options = computed<Partial<CarouselConfig>>(() => ({
  itemsToShow: itemsToShow.value,
  itemsToScroll: itemsToScroll.value,
  wrapAround,
  snapAlign: 'start',
  gap
}))

</script>

<style scoped lang="scss">

</style>
