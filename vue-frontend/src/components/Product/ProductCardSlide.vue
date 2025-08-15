<template>
  <div class="product-card">
    <div class="inner" @click="onOpen">

      <div class="c-img">
        <img
            src="https://res.cloudinary.com/dqtjsvycj/image/fetch/f_auto,c_limit,w_400,h_1000/https://irkutsk.sushi-studio.ru/upload/iblock/2dd/1200x840_LE2025_10.png"/>
      </div>
      <div class="c-name">
        {{ product.NAME }}
      </div>
      <div class="c-desc">
        {{ product.DETAIL_TEXT }}
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import {ProductCardProps, useProductView} from "./hooks";

const props = withDefaults(defineProps<ProductCardProps>(), {})

const {product} = useProductView(props)

const onOpen = () => {
  // stackRouter.getStack().push()
}

</script>

<style scoped>
.product-card {
  width: 100%;
}

.inner {
  border: 1px solid #AAAAAA;
}
</style>
