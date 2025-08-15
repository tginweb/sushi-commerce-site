<template>
  <div class="order-fields row">
    <div
        v-for="(field, fieldIndex) in fields"
        :key="fieldIndex"
        class="c-field"
        :class="itemClass"
    >
      <div class="__inner flex no-wrap ">
        <div class="text-grey-9 text-weight-medium q-mr-auto s-font-xs s-font-lg-sm q-pr-sm">{{ field.name }}</div>
        <div class="text-right s-font-xs s-font-lg-sm">
          <template v-if="field.values">
            <div
                v-for="(fieldValue, fieldValueIndex) of field.values"
                :key="fieldValueIndex"
                :class="field.valueClass"
            >
              {{ fieldValue }}
            </div>
          </template>
          <div
              v-else-if="field.value"
              :class="field.valueClass"
          >
            {{ field.value }}
          </div>
          <div
              v-else-if="field.valueHtml"
              v-html="field.valueHtml"
          />
        </div>
      </div>
    </div>

  </div>
</template>

<script lang="ts" setup>

import {defineProps, toRefs, withDefaults} from "vue";
import {Order} from "@/gql/gen";
import {OrderViewField} from "@/components/Order/hooks";

const props = withDefaults(defineProps<{
  order: Order;
  fields: OrderViewField[];
  itemClass?: any
}>(), {
  fields: () => ([]),
  itemClass: 'q-mb-md qx-mb-last-none'
})

const {fields} = toRefs(props)

</script>

<style lang="scss" scoped>

.c-field {
  .__inner {
    border-bottom: 1px dotted #DDDDDD;
  }
  &:last-child {
    .__inner {
      border-bottom: 0;
    }
  }
}

</style>
