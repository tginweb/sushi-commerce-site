<template>
  <div class="c-footer" :class="containerClass">

    <q-resize-observer @resize="onResize" :debounce="0"/>
    <div v-if="actions.length" class="row q-col-gutter-sm">
      <div
          :class="[action.width ? action.width : 'col-24']"
          v-for="(action, index) in actions"
          :key="action.id || index"
      >
        <q-btn
            color="primary"
            :dense="false"
            unelevated
            class="full-width c-btn"
            v-bind="menuItemToButton(action)"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import {defineComponent, inject, onMounted, toRefs} from "vue";
import {menuItemToButton} from "@/core/util/project/menuItemToButton";
import {MenuItem} from "@/gql/gen";

defineComponent({
  name: 'DialogFooter',
})

export interface DialogFooterProps {
  actions?: Partial<MenuItem>[] | undefined,
  containerClass?: any
}

const props = withDefaults(defineProps<DialogFooterProps>(), {
  actions: () => [],
  containerClass: 'q-pt-md'
})

const {} = props
const {actions, containerClass} = toRefs(props)

const dialogMetrics = inject<any>('dialogElementUpdate')

const onResize = (info: any) => {
  dialogMetrics('footer', info)
}

onMounted(() => {

})

</script>

<style scoped lang="scss">
.c-btn {
  height: 100%;
}
</style>
