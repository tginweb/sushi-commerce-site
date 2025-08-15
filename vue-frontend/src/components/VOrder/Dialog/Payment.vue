<template>
  <StackItemModal
      :dialog="{}"
      :sheet="{}"
      breakpoint="md"
      :actions="actions"
      :actionClose="true"
      v-bind="stackComponent.bind"
      v-on="listeners"
      title="Способ оплаты"
  >

    <div class="q-mb-md">
      <OptionsCards
          :options="options"
          v-model="attrs.PAYMENT_TYPE"
          class="q-col-gutter-md"
          optionClass="col-24 col-md-8"
          optionIcon="ICON"
          optionLabel="NAME"
          optionValue="VALUE"
      ></OptionsCards>
      <div class="q-mt-md" v-if="attrs.PAYMENT_TYPE === 'cash' && cashOptions.length">
        <div class="q-mb-sm">
          Сдача с:
        </div>
        <OptionsInline
            :full-width="true"
            v-model="attrs.CASH_SUM"
            :options="cashOptions"
            optionIcon="ICON"
            optionLabel="NAME"
            optionValue="VALUE"
        />
      </div>
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
import getCashOptions from "@/core/util/getCashOptions";

const vorderStore = useVorderStore()
const {attrs, attrByCode, pricePayDiscounted} = storeToRefs(vorderStore)

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

const options = computed(() => {
  return (attrByCode.value.PAYMENT_TYPE?.OPTIONS || [])
})

const cashOptions = computed(() => {
  return getCashOptions(pricePayDiscounted.value)
})

</script>
<style scoped>

.c-quantity {
  min-width: 140px;

}
</style>
