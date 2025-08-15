<template>
  <div class="">

    <JsonViewer :value="debug"/>

    active {{ stack.activeIndex }}<br/>

    activeId {{ stack.activeId }}

    <q-tabs
        :model-value="stack.activeId"
        @update:model-value="stack.setActiveById($event)"
        dense
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        narrow-indicator
    >
      <q-tab
          :name="item.id"
          :key="item.id"
          :label="item.props.title"
          v-for="item in stack.queue"
      />
    </q-tabs>

    <q-separator/>

    <div
        :key="item.id"
        v-for="item in stack.queue"
        v-show="item.id === stack.activeId.value"
    >
      dddd
      <component
          :is="item.component()"
          v-bind="item.props"
          :stack-item-id="item.id"
      />
    </div>

    <br/><br/><br/>
  </div>
</template>
<script setup lang="ts">
import {StackPropsDefault, useStack} from "../hooks/useStack";
import {TStackTabsProps} from "../types";
import {computed} from "vue";

const props = withDefaults(defineProps<TStackTabsProps>(), {
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
