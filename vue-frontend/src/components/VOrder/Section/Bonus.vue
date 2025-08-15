<template>
  <Wrapper
      code="bonus"
      label="Бонусы"
      :icon="ICONS.bonus"
      :rules="rulesDelivery"
      @click="onClick"
  >
    {{ bonuses }}
  </Wrapper>
</template>

<script lang="ts" setup>

import BenefitDialog from "../Dialog/Benefit.vue";
import {useVorderSection, VOrderSectionProps, vorderSectionPropsDefault} from "./hook";
import Wrapper from "./Wrapper.vue";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {ICONS} from "@/assets/icons";

const vorderStore = useVorderStore()

const {rulesDelivery, bonuses} = storeToRefs(vorderStore)

const {attrs} = storeToRefs(vorderStore)

const model = defineModel()

const props = withDefaults(defineProps<VOrderSectionProps>(), {
  ...vorderSectionPropsDefault,
})

const {} = useVorderSection(props)

const stackRouter = useStackRouter()

const onClick = () => {
  stackRouter.getStack()?.push(BenefitDialog, {role: 'vorder'})
}

</script>

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
