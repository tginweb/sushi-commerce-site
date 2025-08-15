<template>
  <div>

    <VRuntimeTemplate
      :template="contentComputed"
      :templateProps="contentPropsComputed" />
  </div>

</template>

<script setup lang="ts">
import { QBtn } from "quasar";
import { computed, toRefs } from "vue";
import VRuntimeTemplate from "vue3-runtime-template";

const props = withDefaults(defineProps<{
  content: string,
  contentProps: any
}>(), {
  content: '',
  contentProps: () => ({})
})

const { content, contentProps } = toRefs(props)

const contentPropsComputed = computed(() => {
  return {
    ...contentProps.value,
    QBtn
  }
})

const contentComputed = computed(() => {
  let html = content.value
  if (html && (typeof html === 'string')) {
    html = html.replace(/\{\{/g, '<')
    html = html.replace(/\}\}/g, '/>')
    html = html.replace(/\{\#/g, '{{')
    html = html.replace(/\#\}/g, '}}')
  } else {
    html = ''
  }
  return '<div>' + html + '</div>'
})

</script>

<style></style>
