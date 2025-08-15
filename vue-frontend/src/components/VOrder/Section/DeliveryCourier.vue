<template>
  <Wrapper
      code="delivery-courier"
      :icon="ICONS.delivery"
      :model-value="attrs.ADDRESS"
      :rules="rulesDelivery"
      @click="onClick"
      label="Адрес доставки"
  >
    {{ attrs.ADDRESS }}
  </Wrapper>
</template>

<script lang="ts" setup>

import {useVorderSection, VOrderSectionProps, vorderSectionPropsDefault} from "./hook";
import Wrapper from "./Wrapper.vue";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {ICONS} from "@/assets/icons";

const vorderStore = useVorderStore()
const {rulesDelivery} = storeToRefs(vorderStore)

const {attrs} = storeToRefs(vorderStore)

const model = defineModel()

const props = withDefaults(defineProps<VOrderSectionProps>(), {
  ...vorderSectionPropsDefault
})

const {} = useVorderSection(props)

const stackRouter = useStackRouter()

const onClick = () => {
  //stackRouter.getStack()?.push(OrderProfilesPage, {role: 'vorder'})
}

</script>

<style>

</style>
