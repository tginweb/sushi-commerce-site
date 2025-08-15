<template>
  <StackItemModal
      :dialog="{}"
      :sheet="{}"
      breakpoint="md"
      :actions="actions"
      :actionClose="true"
      v-bind="stackComponent.bind"
      v-on="listeners"
      title="Комментарий"
  >
    <q-input
        v-model="attrs.COMMENT"
        autogrow
        outlined
    />
  </StackItemModal>
</template>
<script setup lang="ts">
import {useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {useVorderDialog, VOrderDialogProps, vorderDialogPropsDefault} from "./hook";
import {computed} from "vue";
import {MenuItem} from "@/gql/gen";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";

const vorderStore = useVorderStore()
const {attrs} = storeToRefs(vorderStore)

const props = withDefaults(defineProps<VOrderDialogProps>(), {
  ...vorderDialogPropsDefault
})

const stackComponent = useStackComponent(props)

const {stackItem} = stackComponent

const {code} = props

const {
  listeners
} = useVorderDialog(props)

const actions = computed<Partial<MenuItem>[]>(() => {
  return []
})
</script>
<style>

</style>
