<template>
  <div class="order-actions row q-col-gutter-sm q-col-gutter-md-md" v-if="actions.length">
    <div
        :class="[action.width ? action.width : 'col-24']"
        v-for="(action, index) in actions"
        :key="action.id || index"
    >
      <q-btn
          color="primary"
          unelevated
          class="full-width"
          v-bind="menuItemToButton(action)"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>

import {defineProps, toRefs, withDefaults} from "vue";
import {MenuItem, Order} from "@/gql/gen";
import {menuItemToButton} from "@/core/util/project/menuItemToButton";

const props = withDefaults(defineProps<{
  order: Order;
  actions: Partial<MenuItem>[];
}>(), {
  actions: () => ([])
})

const {actions} = toRefs(props)

</script>

<style lang="scss" scoped>


</style>
