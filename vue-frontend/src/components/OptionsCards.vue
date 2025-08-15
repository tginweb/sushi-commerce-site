<template>
  <div
      class="options-cards row"
      :class="{
        'no-wrap': noWrap,
        '--contrast': contrast
      }"
  >

    <div
        v-for="(option, index) of options"
        :key="index"
        :class="{
          'c-option cursor-pointer': true,
          'selected': option[optionValue] === model,
          [optionClass]: true
        }"
        @click="onClickOption(option)"
    >
      <div
          :class="{
          'c-option-inner': true,
          [optionInnerClass]: true
        }"
      >
        <div>
          <q-icon
              v-if="option[optionIcon]"
              :name="resolveIcon(option[optionIcon])"
              :class="{
              'c-icon': true,
              [iconClass]: true
            }"
          />
        </div>

        <div
            :class="{
              [captionClass]: true
            }"
            v-if="option[optionLabel]"
        >
          {{ option[optionLabel] }}
        </div>
      </div>
    </div>

  </div>
</template>

<script lang="ts" setup>

import {OptionsComponentProps, optionsComponentPropsDefault} from "@/core/hooks/useOptionsComponent";
import {toRefs} from "vue";
import {resolveIcon} from "@/assets/icons";

export type OptionsCardsProps = OptionsComponentProps & {
  contrast?: boolean
  options: any[]
  label?: string
  iconClass?: any
  optionClass?: any
  optionInnerClass?: any
  captionClass?: any
  noWrap?: boolean
}

const props = withDefaults(defineProps<OptionsCardsProps>(), {
  ...optionsComponentPropsDefault,
  noWrap: false,
  contrast: false,
  iconClass: 's-font-2xl',
  optionClass: 'col-md-8',
  optionInnerClass: 'q-px-md q-py-md full-height flex column-md items-center-md',
  captionClass: 'q-mt-auto q-pl-md q-pl-md-none q-pt-md-sm s-font-xs',
})

const {
  iconClass,
  optionClass,
  optionInnerClass,
  captionClass,
  optionIcon,
  optionLabel,
  optionValue,
  contrast,
  noWrap
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

.options-cards {
  &.--contrast {
    .c-option {
      &.selected {
        .c-option-inner {
          background-color: $primary;
          color: #FFFFFF;

          .c-icon {
            color: #FFFFFF !important;
          }
        }
      }
    }
  }
}

.c-option-inner {
  border: 1px solid #e6e6e6;
  color: #151619;
  border-radius: 20px;
}

.c-icon {
  color: #999;
}

.c-option {
  &.selected {
    .c-option-inner {
      border: 1px solid $primary;
    }

    .c-icon {
      color: $primary;
    }
  }
}

.-full-width {
  width: 100%;
  box-sizing: content-box;
}

</style>
