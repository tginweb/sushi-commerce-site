<template>
  <div>

    <q-btn
      label="Оставить свой отзыв"
      color="primary"
      outline
      to="/review/make"
    />



    <Review v-for="review in reviews" :key="review.ID" :entity="review"
      class="q-py-lg border-b-1 border-primary-brown-gray-1 s-font-md" />
  </div>
</template>
<script setup lang="ts">
import { useReviewStore } from '@/modules/review/store/review';
import { storeToRefs } from 'pinia';
import Review from './components/review.vue';

const reviewStore = useReviewStore()
const { reviews } = storeToRefs(reviewStore)

</script>
<style lang="scss" scoped>
@media (min-width: $breakpoint-xl-min) {
  .c-item-col {
    width: 20%;
  }
}

.c-item {
  border-radius: 16px;
  height: 100%;
}
</style>
