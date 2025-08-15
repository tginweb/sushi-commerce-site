<template>
  <StackItemModal
      :actionClose="true"
      v-bind="bind"
      title="Контакты"
  >
    <div class="order-actions row q-col-gutter-sm q-col-gutter-md-md">
      <div
          :class="[action.width ? action.width : 'col-24']"
          v-for="(action, index) in actions"
          :key="action.label || index"
      >
        <q-btn
            color="primary"
            unelevated
            class="full-width --icon-same"
            v-bind="menuItemToButton(action)"
        />
      </div>
    </div>
  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {menuItemToButton} from "@/core/util/project/menuItemToButton";
import {computed} from "vue";
import {MenuItem} from "@/gql/gen";
import {ICONS} from "@/assets/icons";
import {useConfig} from "@/core/store/config";

const props = withDefaults(defineProps<StackComponentProps & {}>(), {})

const {} = props

const {bind} = useStackComponent<StackItemModalState>(props)

const {getConfig} = useConfig()

const actions = computed(() => {
  const res: Partial<MenuItem>[] = []
  res.push({
    icon: ICONS.phone,
    label: getConfig('app', 'PHONE'),
    color: 'primary-brown-1',
    textColor: 'dark',
    native: true,
    url: 'tel:+73952506130',
  })
  return res
})

</script>
<style>

</style>
