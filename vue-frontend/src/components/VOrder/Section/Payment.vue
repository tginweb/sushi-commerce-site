<template>
  <Wrapper
      code="payment"
      :icon="ICONS.payment"
      :rules="rulesDelivery"
      @click="onClick"
      label="Оплата"
  >
    Payment
  </Wrapper>
</template>

<script lang="ts" setup>

import PaymentDialog from "../Dialog/Payment.vue";
import {useVorderSection, VOrderSectionProps, vorderSectionPropsDefault} from "./hook";
import Wrapper from "./Wrapper.vue";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {ICONS} from "@/assets/icons";

const vorderStore = useVorderStore()
const {rulesDelivery, attrs} = storeToRefs(vorderStore)

const model = defineModel()

const props = withDefaults(defineProps<VOrderSectionProps>(), {
  ...vorderSectionPropsDefault
})

const {} = useVorderSection(props)

const stackRouter = useStackRouter()

const onClick = () => {
  stackRouter.getStack()?.push(PaymentDialog, {role: 'vorder'})
}

</script>
BenefitDialog
<style scoped>

.c-view {
  display: table;
  table-layout: fixed;
  width: 100%;

  div {
    display: table-cell;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }
}
</style>
