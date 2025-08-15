<template>
  <StackItemModal
      :actionClose="true"
      :actions="actions"
      v-bind="stackComponent.bind"
      title="Отменить заказ"
  >
    Вы действительно хотите отменить заказ №{{ order && (order.ACCOUNT_NUMBER || order.ID) }}?
  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {computed, toRefs} from "vue";
import {MenuItem} from "@/gql/gen";
import saleOrderCancelMutation from "@/gql/gen/mutation/saleOrderCancelMutation";
import {useGraphql} from "@/core/graphql/service";
import toInt from "@/core/util/toInt";
import {computedAsync} from "@vueuse/core";
import {storeToRefs} from "pinia";
import {useOrdersStore} from "@/modules/shop/store/orders";

const ordersStore = useOrdersStore()
const {} = ordersStore
const {ordersActive} = storeToRefs(ordersStore)

const {useMutation, responseSelection} = useGraphql()

const props = withDefaults(defineProps<StackComponentProps & {
  id: string
}>(), {})
const stackComponent = useStackComponent<StackItemModalState>(props)
const {stackItem} = stackComponent

const {id} = toRefs((props))
const orderId = computed(() => toInt(id.value))

const order = computedAsync(() => getActiveOrderOrFetch(orderId.value))

const mutation = useMutation(saleOrderCancelMutation({
  payload: {
    __fragment: 'OrderFields'
  },
  ...responseSelection()
}))

const actions = computed<Partial<MenuItem>[]>(() => {
  return [
    {
      label: 'Отменить',
      color: 'red',
      onClick: onSubmit,
      loading: mutation.loading.value
    }
  ]
})

const onSubmit = async () => {
  const res = await mutation.mutate({
    id: orderId.value
  })
  if (res.success) {
    ordersActive.value = res.payload || []
    //setActiveOrders(res.payload)
    stackItem?.close()
  }
}

</script>
<style>

</style>
