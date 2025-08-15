<template>
  <q-page class="">

    <q-tabs
        v-model="tab"
        active-color="primary"
        align="left"
        class="q-mb-md q-mb-md-lg"
        dense
        s-narrow-indicator
    >
      <q-route-tab
          label="Активные"
          class="q-px-none q-mr-md"
          content-class="q-px-none"
          to="/personal/orders/active"
          :exact="false"
      />
      <q-route-tab
          label="История заказов"
          class="q-px-none q-mr-md"
          content-class="q-px-none"
          to="/personal/orders/history"
          :exact="false"
      />
    </q-tabs>

    <router-view/>

  </q-page>
</template>
<script setup lang="ts">

import {ref} from "vue";

const props = withDefaults(defineProps<{}>(), {})

const tab = ref()

</script>
<style>

</style>
