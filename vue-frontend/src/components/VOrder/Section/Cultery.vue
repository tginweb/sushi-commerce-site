<template>
  <Wrapper
      :rules="rulesTime"
      @click="onClick"
      code="cultery"
      label="Количество приборов"
      :icon="ICONS.cutlery"
      colStyle="padding-bottom: 5px;"
  >

    <template v-slot:label-side>
      <QuantityInput
          containerBorderColor="#AAAAAA"
          v-model="attrs.PERSONS_NUMBER"
          :btn="{
            size: '13px'
          }"
          class="c-quantity i-quantity bg-primary-brown-1"
          input-control-class="bg-primary-brown-1"
          :measure-show="false"
      />
    </template>
  </Wrapper>
</template>

<script setup lang="ts">

import {useVorderSection, VOrderSectionProps, vorderSectionPropsDefault} from "./hook";
import Wrapper from "./Wrapper.vue";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {VOrderDialogProps} from "@/components/VOrder/Dialog/hook";
import QuantityInput from "@/components/QuantityInput.vue";
import {ICONS} from "@/assets/icons";
import {ref} from "vue";

import CulteryDialog from "../Dialog/Cultery.vue";

const vorderStore = useVorderStore()
const {rulesTime} = storeToRefs(vorderStore)

const {attrs} = storeToRefs(vorderStore)

const model = defineModel()

const props = withDefaults(defineProps<VOrderSectionProps>(), {
  ...vorderSectionPropsDefault
})

const num = ref(0)

const {code} = props

const {} = useVorderSection(props)

const stackRouter = useStackRouter()

const onClick = () => {
  stackRouter.getStack()?.push<VOrderDialogProps>(CulteryDialog, {
    ...props
  })
}

</script>

<style>

</style>
