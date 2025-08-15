<template>
  <div class="">
    <JsonViewer :value="debug" v-if="false"/>

    <component
        v-for="item in stack.queue"
        :key="item.id"
        :is="item.component()"
        v-bind="item.props"
        :stack-item-id="item.id"
    />
  </div>
</template>
<script setup lang="ts">
import {StackPropsDefault, useStack} from "../hooks/useStack";
import {TStackModalProps} from "../types";
import {computed} from "vue";

const props = withDefaults(defineProps<TStackModalProps>(), {
  ...StackPropsDefault
})

const {stack} = useStack(props)

const debug = computed(() => {
  return {
    config: stack.config,
    queue: stack.queue.map(item => item.id)
  }
})
</script>
