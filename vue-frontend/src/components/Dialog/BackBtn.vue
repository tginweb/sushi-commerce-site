<template>
  <q-btn
      :icon="ICONS.arrowLeft"
      color="primary-brown-1"
      :size="size"
      dense
      :round="!label"
      text-color="dark"
      unelevated
      @click="emits('click')"
      :label="label ? (typeof label ==='string' ? label: 'назад') : undefined"
  />
</template>

<script setup lang="ts">
import {defineComponent, toRefs} from "vue";
import {ICONS} from "@/assets/icons";

defineComponent({
  name: 'DialogCloser',
})

export interface DialogCloserProps {
  label?: string | boolean
  size?: string
}

const props = withDefaults(defineProps<DialogCloserProps>(), {
  size: '11px'
})

const {size} = props
const {label} = toRefs(props)

const emits = defineEmits<{
  click: []
}>()

</script>
<style scoped lang="scss">

</style>
