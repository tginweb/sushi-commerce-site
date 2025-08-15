<template>
  <q-page class=" ">

    PAGE {{id}}

  </q-page>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {useHashScroll} from "@/core/hooks/useHashScroll";
import {StackComponentProps} from "@/packages/stack-router/hooks/useStackComponent";

const props = withDefaults(defineProps<StackComponentProps & {
  id: string
}>(), {})

const hashScroll = useHashScroll({find: true})

const visible = ref(false)

</script>

<style>
.active {
  background-color: #00FF00;;
}
</style>
