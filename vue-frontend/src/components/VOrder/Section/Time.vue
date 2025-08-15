<template>
  <Wrapper
      :rules="rulesTime"
      @click="onClick"
      :icon="ICONS.clock"
      label="Время"
      code="time"
  >

  </Wrapper>
</template>

<script setup lang="ts">

import {useVorderSection, VOrderSectionProps, vorderSectionPropsDefault} from "./hook";
import Wrapper from "./Wrapper.vue";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import TimeDialog from "../Dialog/Time.vue";
import {VOrderDialogProps} from "@/components/VOrder/Dialog/hook";
import {ICONS} from "@/assets/icons";
import {ref} from "vue";

const vorderStore = useVorderStore()
const {} = vorderStore

const {attrs, rulesTime} = storeToRefs(vorderStore)

const model = defineModel()

const props = withDefaults(defineProps<VOrderSectionProps>(), {
  ...vorderSectionPropsDefault
})

const num = ref(0)

const {code} = props

const {} = useVorderSection(props)

const stackRouter = useStackRouter()

const onClick = () => {
  stackRouter.getStack()?.push<VOrderDialogProps>(TimeDialog, {
    ...props
  })
}

</script>

<style>

</style>
