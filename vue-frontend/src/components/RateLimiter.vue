<template>
  <div class="rate-limiter text-red-9"
    v-if="message">
    {{ message }}
  </div>
</template>

<script setup lang="ts">
import duration from '@/core/util/date/duration';
import dayjs from 'dayjs';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

let interval = ref<any>(null)

const props = withDefaults(defineProps<{
  text: string
}>(), {
  text: 'Запросить код снова можно через {t}'
})

const { text } = props

const model = defineModel<number>({ required: true })

const druation = computed(() => {
  return model.value > 0 ? duration(dayjs(), dayjs().subtract(model.value, 'second')) : null
})

const message = computed(() => {
  if (model.value > 0 && druation.value) {
    return text.replace('{t}', druation.value)
  }
})

onMounted(() => {
  interval.value = setInterval(() => {
    if (model.value && model.value > 0) {
      model.value--
    }
  }, 1000)
})

onBeforeUnmount(() => {
  clearInterval(interval.value)
})

</script>
<style lang="scss" scoped>
.rate-limiter {}
</style>
