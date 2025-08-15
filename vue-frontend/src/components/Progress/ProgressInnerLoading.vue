<template>
  <div class="progress-inner-loading">

    <slot name="persist"/>

    <div
        v-if="reserveHeight && (isPending || isLoading)"
        :style="{
          height: reserveHeightComp
        }"
    >
    </div>

    <q-inner-loading :showing="isLoading">
      <q-spinner
          color="primary"
          :size="size"
          :thickness="thickness"
      />
    </q-inner-loading>

    <slot name="default" v-if="isResolved"/>

    <template v-else-if="isRejected || isFailed">
      <slot
          v-if="isRejected && $slots.rejected"
          name="rejected"
      />
      <slot
          v-else-if="isFailed && $slots.failed"
          name="failed"
      />
      <div v-else>
        <div class="text-red">ошибка получения данных</div>
        <div v-if="!isFailed && repeatCallback" class="q-mt-md">
          <q-btn
              color="primary"
              unelevated
              dense
              label="попробуйте еще раз"
              @click="onRepeatCallback"
          />
        </div>
      </div>
    </template>

  </div>
</template>
<script lang="ts" setup>

import {computed, toRefs} from "vue";
import {TaskStatus} from "@/core/types";

const props = withDefaults(defineProps<{
  repeatCallback?: any,
  reserveHeight?: boolean | number | string,
  size?: string,
  thickness?: number,
}>(), {
  reserveHeight: true,
  size: '3em',
  thickness: 10,
})

const {size, thickness} = props

const {repeatCallback, reserveHeight} = toRefs(props)

const model = defineModel<TaskStatus>({
  default: 'pending',
})

const reserveHeightComp = computed(() => {
  return reserveHeight.value ? (typeof reserveHeight.value === 'boolean' ? '200px' : reserveHeight.value) :undefined
})

const isPending = computed(() => {
  return !model.value || model.value === 'pending'
})

const isLoading = computed(() => {
  return model.value === 'process' || model.value === 'loading'
})

const isRejected = computed(() => {
  return model.value === 'rejected'
})

const isFailed = computed(() => {
  return model.value === 'failed'
})

const isResolved = computed(() => {
  return model.value === true || model.value === 'resolved'
})

const onRepeatCallback = () => {
  repeatCallback.value()
}

</script>

<style lang="scss" scoped>

.progress-inner-loading {
  position: relative;
}

</style>
