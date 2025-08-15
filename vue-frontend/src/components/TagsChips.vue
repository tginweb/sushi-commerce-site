<template>
  <div class="tags" v-if="items.length > 1">

    <div class="row no-wrap items-center q-col-gutter-sm">
      <div
          :class="allCellClass"
          v-if="modelValue.length"
      >
        <div @click="onReset">
          <q-chip
              class="cursor-pointer bg-white s-font-2xs s-font-md-sm q-ml-none q-mr-xs q-mr-md-sm q-px-sm q-px-md-sm"
              outline
          >
            Все
          </q-chip>
        </div>
      </div>
      <div class="col-shrink">
        <ScrollHorizontal>
          <div class="flex no-wrap items-center">
            <div
                v-if="title && !modelValue.length"
                class="q-mr-sm q-ml-sm s-font-2xs text-weight-medium"
            >
              {{ title }}:
            </div>
            <div
                v-for="item of items"
                :key="'selected' + item.ID"
                @click="toggleItem(item)"
            >
              <q-chip
                  v-if="modelValue.includes(item.ID)"
                  class="cursor-pointer bg-primary s-font-2xs s-font-md-md q-ml-none q-mr-xs q-mr-md-sm q-px-sm q-px-md-sm"
                  outline
                  text-color="white"
                  @click.native="toggleItem(item)"
              >
                {{ item.NAME }}
              </q-chip>
              <q-chip
                  v-else
                  class="cursor-pointer bg-white s-font-2xs s-font-md-sm q-ml-none q-mr-xs q-mr-md-sm q-px-sm q-px-md-sm"
                  outline
                  :class="item.FEATURED ? 'text-weight-bold' : ''"
                  :color="item.FEATURED ? 'deep-orange-8' : 'grey-6'"
                  @click.native="toggleItem(item)"
              >
                {{ item.NAME }}
              </q-chip>
            </div>
          </div>
        </ScrollHorizontal>
      </div>
    </div>

  </div>
</template>

<script setup lang="ts">
import ScrollHorizontal from "./ScrollHorizontal.vue";

import {QTabProps, QTabsProps, useQuasar} from "quasar";

export interface TagItem {
  ID: number
  NAME: string
  FEATURED?: boolean
}

export interface TagsProps {
  title?: string
  items: TagItem[]
  allCellClass?: string
  allTabsProps?: Partial<QTabsProps>
  allTabProps?: Partial<QTabProps>
}

const props = withDefaults(defineProps<TagsProps>(), {
  allCellClass: 'col-auto',
  allTabProps: () => ({}),
  allTabsProps: () => ({})
})

const {title, items, allTabProps, allTabsProps} = props

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

const modelValue = defineModel<number[]>({required: true})

const toggleItem = (item: TagItem) => {
  if (modelValue.value.includes(item.ID)) {
    modelValue.value = modelValue.value.filter(tagId => tagId !== item.ID)
  } else {
    modelValue.value.push(item.ID)
  }
  console.log('ss', modelValue.value)
}

const onReset = () => {
  console.log('reset')
  modelValue.value = []
}
</script>

<style>

</style>
