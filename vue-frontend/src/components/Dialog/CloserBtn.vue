<template>
  <q-btn
      :icon="ICONS.close"
      class="c-dialog-closer"
      color="primary-brown-1"
      round
      :dense="false"
      :size="size"
      text-color="dark"
      unelevated
      @click="emits('click')"
  />
</template>

<script setup lang="ts">
import {defineComponent} from "vue";
import {ICONS} from "@/assets/icons";

defineComponent({
  name: 'DialogCloser',
})

export interface DialogCloserProps {
  size?: string
}

const props = withDefaults(defineProps<DialogCloserProps>(), {
  size: '11px'
})

const {size} = props

const emits = defineEmits<{
  click: []
}>()

</script>
<style scoped lang="scss">

</style>
