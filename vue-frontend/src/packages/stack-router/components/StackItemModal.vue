<template>
  <div :data-visible="visible">

    <component
        :is="is"
        v-bind="bind"
        v-on="listeners"
        v-model="visible"
    >
      <template
          v-for="(_, slotName) in slots"
          #[slotName]="slotProps"
      >
        <slot
            :name="slotName"
            v-bind="slotProps ?? {}"
        />
      </template>
    </component>
  </div>

</template>
<script setup lang="ts">

import {StackItemPropsDefault, useStackItem} from "../hooks/useStackItem";
import {computed, useSlots, watch} from "vue";
import {
  StackItemEmits,
  StackItemModalState,
  TStackItemModalModes,
  TStackItemModalProps,
  TStackModalConfig
} from "../types";
import {useQuasar} from "quasar";
import {stackManager} from "../class/StackManager";

const $q = useQuasar()
const slots = useSlots()

const props = withDefaults(defineProps<TStackItemModalProps>(), {
  ...StackItemPropsDefault,
})

const stack = stackManager.getStackByItemId<TStackModalConfig>(props.stackItemId)

const config = {
  ...stack.config,
}

const emit = defineEmits<StackItemEmits>()

const {stackItem, stackItemState, visible} = useStackItem<StackItemModalState>(props, emit)

const mode = computed<TStackItemModalModes>(() => {
  if (config.breakpoint && $q.screen.lt[config.breakpoint as keyof typeof $q.screen.lt]) {
    return 'sheet'
  }
  return 'dialog'
})

if (stackItem) {
  watch(mode, () => {
    stackItemState.mode = mode.value
  }, {immediate: true})
}

const listeners: Record<keyof StackItemEmits, (args: any) => void> = {
  show: (...args: StackItemEmits['show']) => emit('show', ...args),
  hide: (...args: StackItemEmits['hide']) => emit('hide', ...args),
}

const is = computed(() => {
  switch (mode.value) {
    case "dialog":
      return 'Dialog'
    case "sheet":
      return 'BottomSheet'
  }
})

const bind = computed(() => {
  switch (mode.value) {
    case "dialog":
      return {
        actions: props.actions,
        actionClose: props.actionClose,
        title: props.title,
        onHeaderBack: props.onHeaderBack,
        //mode,
        ...props.dialog
      }
    case "sheet":
      return {
        actions: props.actions,
        actionClose: props.actionClose,
        title: props.title,
        onHeaderBack: props.onHeaderBack,
        //mode,
        ...props.sheet
      }
  }
})

</script>
<style>


</style>
