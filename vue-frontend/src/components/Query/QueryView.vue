<template>
  <ProgressInnerLoading
      :model-value="status"
      :reserve-height="reserveHeight"
      :repeat-callback="query.query"
  >
    <template #persist>
      <ComponentDebugger
          :menu="debuggerMenu"
      />
    </template>
    <template #default>
      <slot
          :status="status"
          :loading="loading"
          :result="result"
          :isEmptyResult="isEmptyResult"
          v-if="!$slots.empty || !isEmptyResult"
      />
      <slot
          v-else
          name="empty"
      />
    </template>
  </ProgressInnerLoading>
</template>

<script setup lang="ts">
import {computed, defineProps, withDefaults} from "vue";
import ProgressInnerLoading from "@/components/Progress/ProgressInnerLoading.vue";
import {UseQuery} from "@/core/graphql/service";
import ComponentDebugger from "@/components/ComponentDebugger/ComponentDebugger.vue";
import {MenuItem} from "@/gql/gen";

const props = withDefaults(defineProps<{
  query: UseQuery,
  reserveHeight?: boolean | string | number,
}>(), {
  reserveHeight: '200px'
})

const {reserveHeight, query} = props

//const {query} = toRefs(props)

const isEmptyResult = computed(() => query.isEmptyResult)
const status = computed(() => query.status)
const loading = computed(() => query.loading)
const loaded = computed(() => query.loaded)
const result = computed(() => query.result)

const debuggerMenu = computed(() => {
  const res: Partial<MenuItem>[] = [
    {
      label: 'Query normal',
      onClick: () => {
        query.query()
      }
    },
    {
      label: 'Query refetch',
      onClick: () => {
        query.query(null, {refetch: true})
      }
    },
    {
      label: 'Invalidate cache',
      onClick: () => {
        query.invalidateCache()
      }
    },
    {
      label: 'Clear result',
      onClick: () => {
        query.clearResult()
      }
    },
    {
      label: 'Set empty result',
      onClick: () => {
        query.setEmptyResult()
      }
    },
  ]

  return res
})

</script>
<style lang="scss" scoped>
</style>
