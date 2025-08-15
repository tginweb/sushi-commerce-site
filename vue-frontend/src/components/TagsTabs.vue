<template>
  <div class="tags" v-if="options.length > 1">

    <div class="row no-wrap">
      <div
          :class="allCellClass"
          v-if="modelValue"
      >
        <q-tabs
            v-model="modelValue"
            class="c-tabs"
            v-bind="_allTabsProps"
        >
          <q-tab
              v-bind="_allTabProps"
              :name="0"
              class="q-px-none"
              label="Все"
          />
        </q-tabs>

      </div>
      <div class="col-shrink">
        <q-tabs
            v-model="modelValue"
            inline-label
            dense
            mobile-arrows
            active-color="primary"
            align="left"
            class="c-tabs"
            v-tabs-scrollable
            v-if="q.screen.gt.md"
        >
          <q-tab
              class="q-px-sm"
              :name="option.value"
              v-for="option of options"
              :key="option.key"
              :label="option.label"
              no-caps
          />
        </q-tabs>
        <q-tabs
            v-else
            v-model="modelValue"
            inline-label
            dense
            active-color="primary"
            align="left"
            class="c-tabs"
            mobile-arrows
        >
          <q-tab
              class="q-px-sm"
              v-for="option of options"
              :key="option.key"
              :label="option.label"
              :name="option.value"
              no-caps
          />
        </q-tabs>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

import {QTabProps, QTabsProps, useQuasar} from "quasar";
import {
  OptionsComponentOption,
  OptionsComponentProps,
  optionsComponentPropsDefault,
  useOptionsComponent
} from "@/core/hooks/useOptionsComponent";

export type TagsProps = OptionsComponentProps & {
  optionFeatured?: string
  allCellClass?: string
  allTabsProps?: Partial<QTabsProps>
  allTabProps?: Partial<QTabProps>
}

type TagOption = OptionsComponentOption & {
  featured: boolean
}

type TagOptionValue = number

const q = useQuasar()

const props = withDefaults(defineProps<TagsProps>(), {
  ...optionsComponentPropsDefault,
  optionFeatured: 'featured',
  allCellClass: 'col-auto',
  allTabProps: () => ({}),
  allTabsProps: () => ({})
})

const {allTabProps, allTabsProps} = props

const modelValue = defineModel<TagOptionValue | null>()

const {options, selectedOption} = useOptionsComponent<TagOption, TagOptionValue>(props, modelValue, [
  {
    name: 'featured',
    propName: 'optionFeatured',
    default: false
  }
])

const _allTabProps: Partial<QTabProps> = {
  noCaps: true,
  ...allTabProps
}

const _allTabsProps: Partial<QTabsProps> = {
  inlineLabel: true,
  dense: true,
  activeColor: "primary",
  align: "left",
  ...allTabsProps
}

</script>

<style>

</style>
