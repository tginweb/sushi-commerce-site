<template>
  <q-breadcrumbs
      active-color="dark"
      class="breadcrumbs text-primary-brown-gray-3 s-font-3xs"
  >
    <template v-slot:separator>
      <span class="c-separator">•</span>
    </template>
    <q-breadcrumbs-el
        v-for="(item, index) of path"
        :key="index"
        :label="item.title"
        :to="item.to"
        exact
    />
  </q-breadcrumbs>
</template>

<script lang="ts" setup>

import {toRefs} from "vue";

const props = withDefaults(defineProps<{
  path?: any[],
}>(), {
  path: () => []
})

const {path} = toRefs(props)

</script>
<style lang="scss" scoped>

.c-separator {
  font-size: 8px;
  line-height: 1;
  vertical-align: middle;
}

.breadcrumbs {
  :deep() {
    ul {
      margin: 0 !important;
    }
  }
}

</style>
