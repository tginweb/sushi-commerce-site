<template>
  <StackItemModal
      :dialog="{
         width: '300px'
      }"
      :sheet="{}"
      :actions="actions"
      :actionClose="true"
      v-bind="bind"
      v-on="listeners"
      title="Deliver11"
  >
    <template #content="bind">
      <DialogHeader
          v-bind="bind"
      />
      <DialogBody>
        dddd
      </DialogBody>
      <DialogFooter
          v-bind="bind"
      />
    </template>
  </StackItemModal>
</template>
<script setup lang="ts">
import {useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {useVorderDialog, VOrderDialogProps, vorderDialogPropsDefault} from "./hook";
import {computed} from "vue";
import {MenuItem} from "@/gql/gen";

const props = withDefaults(defineProps<VOrderDialogProps>(), {
  ...vorderDialogPropsDefault
})

const {bind, stackItem} = useStackComponent(props)

const {code, param} = props

const {
  listeners
} = useVorderDialog(props)

const actions = computed<Partial<MenuItem>[]>(() => {
  return [
    {
      label: 'Find time',
      onClick: () => {
        param()
      }
    }
  ]
})
</script>
<style>

</style>
