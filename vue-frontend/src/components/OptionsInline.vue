<template>
  <div
      class="options-inline flex"
      :class="{
        '-full-width': fullWidth,
        '-shadow': shadow,
        'no-wrap': noWrap || fullWidth
      }"
  >
    <div
        v-for="(option, index) of options"
        :key="index"
        :class="{
          'c-option flex full-height1 q-py-sm leading-none cursor-pointer': true,
          'selected': option[optionValue] === model,
          [optionClass]: true
        }"
        @click="onClickOption(option)"
        :style="{
          width: fullWidth ? '100%' : 'auto'
        }"
    >

      <div class="flex q-gutter-x-xs items-center q-mx-auto q-my-auto q-px-xs no-wrap">
        <q-icon
            v-if="optionImage && option[optionImage]"
            :name="option[optionImage]"
            size="13px"
        />
        <q-icon
            v-if="optionIcon && option[optionIcon]"
            :name="$icons[option[optionIcon]]"
            size="13px"
        />
        <span v-if="option[optionLabel]" style="white-space: nowrap;">
          {{ option[optionLabel] }}
        </span>
      </div>

    </div>

  </div>
</template>

<script lang="ts" setup>

import {OptionsComponentProps, optionsComponentPropsDefault} from "@/core/hooks/useOptionsComponent";
import {toRefs} from "vue";

export type OptionsCardsProps = OptionsComponentProps & {
  options: any[]
  optionClass?: any
  fullWidth?: boolean
  shadow?: boolean
  noWrap?: boolean
}

const props = withDefaults(defineProps<OptionsCardsProps>(), {
  ...optionsComponentPropsDefault,
  fullWidth: false,
  shadow: false,
  noWrap: false,
})

const {
  optionClass,
  optionIcon,
  optionImage,
  optionLabel,
  optionValue,
  noWrap,
  fullWidth,
  shadow,
} = props

const {
  options
} = toRefs(props)

type OptionValue = any

const model = defineModel<OptionValue>()

const onClickOption = (option: any) => {
  model.value = option[optionValue]
}

</script>
<style lang="scss" scoped>

.options-inline {
  background-color: #f2f2f2;
  border-radius: 10px;
  padding: 2px 2px 2px 2px;

  &.-shadow {
    .c-option {
      &.selected {
        box-shadow: 4px 4px 9px -1px rgba(0, 0, 0, 0.15);
      }
    }
  }

  &.-full-width {
    width: 100%;
  }
}

.c-option {
  color: #555;
  border-radius: 10px;

  &.selected {
    border: 1px solid #eee;
    color: $primary !important;
    background-color: #fff;
  }
}

</style>
