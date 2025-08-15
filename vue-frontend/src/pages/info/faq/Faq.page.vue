<template>
  <div>
    <Accordion
      :items="items"
      headerClass="text-h3 font-heading q-pa-none"
      itemClass="q-py-md"
      contentClass="q-pt-md"
    />
  </div>
</template>
<script setup lang="ts">
import { useFaqStore } from '@/modules/faq/store/faq';
import { storeToRefs } from 'pinia';
import { computed } from 'vue';

const faqStore = useFaqStore()

const { faqElements } = storeToRefs(faqStore)

const items = computed(() => faqElements.value.map(item => ({
  ...item,
  HTML: item.DETAIL_TEXT
})))

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
