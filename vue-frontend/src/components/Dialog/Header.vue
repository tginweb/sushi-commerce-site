<template>
  <div class="c-header q-pb-sm">
    <q-resize-observer @resize="onResize"/>
    <div class="flex q-gutter-md no-wrap items-center">
      <div v-if="onBack" class="c-header__back col-shrink">
        <DialogBackBtn @click="onBack"/>
      </div>
      <div class="c-header__title s-font-xl">
        {{ title }}
      </div>
      <div class="c-header__side q-ml-auto">
        <DialogCloserBtn @click="onClose"/>
      </div>
    </div>
    <slot name="bottom"/>
  </div>
</template>

<script setup lang="ts">
import {defineComponent, inject, toRefs} from "vue";
import DialogCloserBtn from "./CloserBtn.vue";
import DialogBackBtn from "./BackBtn.vue";

defineComponent({
  name: 'DialogHeader',
})

export interface DialogHeaderProps {
  title?: string | undefined
  closable?: boolean | undefined
  onClose?: (() => void) | undefined
  onBack?: (() => void) | undefined
}

const props = withDefaults(defineProps<DialogHeaderProps>(), {
  closable: true,
})

const {closable, onClose} = props
const {title} = toRefs(props)

const emits = defineEmits<{
  close: []
}>()

const dialogMetrics = inject<any>('dialogElementUpdate')

const onResize = (info: any) => {
  dialogMetrics('header', info)
}

</script>
<style scoped lang="scss">
.c-header {
  .c-header__title {
    font-weight: 500;
  }

  .c-header__side {

  }
}
</style>
