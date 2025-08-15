<template>
  <div>
    <div
        v-for="entity of cameraElements"
        :key="entity.ID"
        class="q-mb-xl"
    >
      <div
          v-html="entity.PROPERTIES.CODE"
          class="q-mb-md"
          v-if="entity.PROPERTIES .CODE"
      />
      <div>
        {{ entity.NAME }}
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import {useCameraStore} from '@/stores/camera';
import {storeToRefs} from 'pinia';

const cameraStore = useCameraStore()
const {cameraElements} = storeToRefs(cameraStore)
</script>
<style lang="scss" scoped>
</style>
