<template>
  <StackItemModal
      v-bind="bind"
      title="Поиск"
      :dialog="{
        position: 'top',
        width: '1200px'
      }"
  >
    <q-input
        v-model="query"
        outlined
    />
  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {ref} from "vue";

const props = withDefaults(defineProps<StackComponentProps & {}>(), {})

const {bind} = useStackComponent<StackItemModalState>(props)

const query = ref<string>('')
</script>
<style>

</style>
