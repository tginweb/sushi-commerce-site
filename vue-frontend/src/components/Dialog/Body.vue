<template>
  <div
      class="dialog-body scroll"
      :class="contentClass"
      :style="{
        maxHeight: maxHeight
      }"
      v-bind="{}"
  >
    <slot/>
  </div>
</template>
<script setup lang="ts">

import {toRefs, useAttrs, watch} from "vue";

defineOptions({
  name: 'DialogBody',
  inheritAttrs: false
})

const attrs = useAttrs()

export interface DialogFooterProps {
  elements?: any
  maxHeight?: string | undefined
  contentClass?: any
}

const props = withDefaults(defineProps<DialogFooterProps>(), {
  maxHeight: '50vh',
  contentClass: ''
})

const {contentClass} = props
const {maxHeight} = toRefs(props)

watch(maxHeight, () => {
  console.log('maxHeight', maxHeight.value)
})


</script>
<style lang="scss" scoped>
.dialog-body {
  //transition: max-height 0.15s ease-in-out;
}
</style>
