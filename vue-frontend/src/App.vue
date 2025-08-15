<template>
  <div>
    <q-no-ssr>
      <Bus/>
      <StackModal namespace="modal"/>
    </q-no-ssr>
    <router-view/>
  </div>
</template>

<script setup lang="ts">
import Bus from "@/components/Bus.vue";
import StackModal from "@/packages/stack-router/components/StackModal.vue";
import {onMounted} from "vue";

onMounted(() => {
  //if (typeof window !== undefined && window.hidePreloader) window.hidePreloader()
})

</script>
