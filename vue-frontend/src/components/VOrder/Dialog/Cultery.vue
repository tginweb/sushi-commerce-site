<template>
  <StackItemModal
      :dialog="{}"
      :sheet="{}"
      breakpoint="md"
      :actions="actions"
      :actionClose="true"
      v-bind="stackComponent.bind"
      v-on="listeners"
      title="Количество приборов"
  >
    <div class="flex">
      <QuantityInput
          containerBorderColor="#AAAAAA"
          v-model="attrs.PERSONS_NUMBER"
          :btn="{
            size: '25px'
          }"
          input-size="20px"
          class="c-quantity bg-primary-brown-1 q-mx-auto"
          input-control-class="bg-primary-brown-1"
      />
    </div>
  </StackItemModal>
</template>
<script setup lang="ts">
import {useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {useVorderDialog, VOrderDialogProps, vorderDialogPropsDefault} from "./hook";
import {computed} from "vue";
import {MenuItem} from "@/gql/gen";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import QuantityInput from "@/components/QuantityInput.vue";

const vorderStore = useVorderStore()
const {attrs} = storeToRefs(vorderStore)

const props = withDefaults(defineProps<VOrderDialogProps>(), {
  ...vorderDialogPropsDefault
})

const stackComponent = useStackComponent(props)

const {code} = props

const {
  listeners
} = useVorderDialog(props)

const actions = computed<Partial<MenuItem>[]>(() => {
  return []
})
</script>
<style scoped>

.c-quantity {
  min-width: 140px;

}
</style>
