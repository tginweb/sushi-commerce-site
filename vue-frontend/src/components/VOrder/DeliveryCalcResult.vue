<template>

  <div v-if="alert">
    <Alert :message="alert"/>
  </div>

</template>

<script lang="ts" setup>

import {computed, ref, Ref, toRefs} from "vue";
import {DeliveryCalculate, Message} from "@/gql/gen";
import Alert from "@/components/Alert/Alert.vue";
import {useVorderStore} from "@/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
const vorderStore = useVorderStore()

const {deliveryCalc} = storeToRefs(vorderStore)

type AlertType = 'success' | 'info' | 'error'

const styles: Record<AlertType, {
  icon: string
  color: string
}> = {
  success: {
    icon: 'check_circle',
    color: 'green-8'
  },
  info: {
    icon: 'info',
    color: 'dark'
  },
  error: {
    icon: 'error',
    color: 'red-9'
  },
}

const props = withDefaults(defineProps<{}>(), {})

const {modelValue} = toRefs(props)




const calcToAlert = (calc: DeliveryCalculate) => {

  const res: Message = {}

  if (calc) {
    if (calc.RES_STATUS === 'success') {
      res.type = 'success'
      res.message = 'время свободно'
    } else if (calc.RES_STATUS === 'time_busy') {
      res.type = 'error'
      res.message = 'Время занято, выбрать ближайшее:'
    } else if (calc.RES_STATUS === 'service_unavailable') {
      res.type = 'error'
      res.message = 'сервис недоступен'
    }
  }

  return res
}

const style = computed(() => styles[alert.value?.type])

</script>

<style lang="scss" scoped>


</style>
