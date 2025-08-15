<template>
  <div v-if="page">
    <RuntimeContent
        :content="page.DETAIL_TEXT"
    />
  </div>
</template>

<script setup lang="ts">
import {usePage} from "@/modules/page/store/pages"
import {toRefs, watch} from "vue"
import {useRouterStore} from "@/core/router/store"
import {useMeta} from "quasar"
import {storeToRefs} from "pinia";
import RuntimeContent from "@/components/RuntimeContent.vue";

const routerStore = useRouterStore()
const {setRouteMeta} = routerStore
const {} = storeToRefs(routerStore)

const props = withDefaults(defineProps<{
  pageUrl: string
}>(), {})

const {pageUrl} = toRefs(props)
const {page, pageMeta} = usePage(pageUrl)

watch(page, () => {
  if (page.value) {
    setRouteMeta('title', page.value.NAME || '')
  }
}, {immediate: true})

useMeta(() => pageMeta.value)

</script>

<style>
</style>
