<template>
  <div>
    <q-btn
        v-for="option in options"
        :key="option.key"
        :label="option.label"
        :icon="option.icon"
        v-bind="option.value === model ? btnPropsActive : btnPropsInactive"
        @click="model = option.value"
    />
  </div>
</template>

<script setup lang="ts">

import {QBtnProps} from "quasar";
import {defineComponent} from "vue";
import {
  OptionsComponentOption,
  OptionsComponentProps,
  optionsComponentPropsDefault,
  useOptionsComponent
} from "@/core/hooks/useOptionsComponent";

defineComponent({
  name: 'ButtonsToggle',
})

export type ButtonsToggleProps = OptionsComponentProps & {
  props?: QBtnProps & { class?: any }
  propsActive?: QBtnProps & { class?: any }
  propsInactive?: QBtnProps & { class?: any }
  options: any[]
}

type OptionValue = any

const defaultBtn: QBtnProps = {}

const defaultBtnInactive: QBtnProps = {
  outline: true,
}

const defaultBtnActive: QBtnProps = {
  color: "primary"
}

const props = withDefaults(defineProps<ButtonsToggleProps>(), {
  ...optionsComponentPropsDefault,
  props: () => ({}),
  propsActive: () => ({}),
  propsInactive: () => ({}),
  options: () => [],
})

const {
  props: propsCommon,
  propsInactive,
  propsActive,
} = props

const btnPropsInactive = {
  ...defaultBtn,
  ...defaultBtnInactive,
  ...propsCommon,
  ...propsInactive
}

const btnPropsActive = {
  ...defaultBtn,
  ...defaultBtnActive,
  ...propsCommon,
  ...propsActive
}

const model = defineModel<OptionValue>()

const {options, selectedOption} = useOptionsComponent<OptionsComponentOption, OptionValue>(props, model, [])


</script>

<style>

</style>
