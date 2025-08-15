<template>
  <q-page class="">

    <div class="container bg-fill q-pb-lg">

      <div class="c-header q-pt-xs q-pb-md q-mb-sm q-mb-md-lg q-py-md-lg q-px-lg-lg q-px-xl-xl ">

        <div class="c-tabs q-mb-md">
          <q-tabs v-model="menuTab" inline-label active-color="primary" v-tabs-scrollable indicator-color="transparent"
            no-caps class="">
            <q-route-tab exact v-for="(item, index) in menuItems.top" :key="item.label || index" class="c-tab"
              :class="{
                'q-pl-none q-pr-md': index === 0,
                'q-px-md': index > 0
              }" v-bind="menuItemToTab(item)" />
          </q-tabs>
        </div>

        <div v-if="title" class="c-title flex items-center q-gutter-y-md">
          <div class="flex items-center q-mr-auto">
            <h1
              class="c-title-title leading-e4 q-ma-none s-font-lg s-font-sm-2xl s-font-md-3xl s-font-lg-4xl s-font-xl-6xl text-weight-bolder">
              {{ title }}
            </h1>
          </div>
        </div>
      </div>

      <div class="q-px-lg-lg q-px-xl-xl s-font-md s-font-md-lg">
        <router-view />
      </div>

    </div>

  </q-page>
</template>

<script setup lang="ts">
import { useQuasar } from "quasar";
import { computed, ref, watch } from "vue";
import { useRouterStore } from "@/core/router/store";
import { storeToRefs } from "pinia";
import { useMenuStore } from "@/modules/menu/store/menu";
import { menuItemToTab } from "@/core/util/project/menuItemToTab";

const $q = useQuasar()

const routerStore = useRouterStore()
const { routeMeta } = storeToRefs(routerStore)

const menuStore = useMenuStore()
const { menuItems } = storeToRefs(menuStore)

const menuTab = ref()

const title = computed(() => routeMeta.value.title)
const layoutContentWide = computed(() => routeMeta.value.layoutContentWide)

const items = computed(() => menuItems.value?.top?.map(item => menuItemToTab(item)))

watch(items, (newVal) => {
  console.log('items', items.value)
}, { deep: true, immediate: true })


</script>
<style lang="scss" scoped>
@media (min-width: $breakpoint-md-min) {
  .c-menu {
    flex: 1;
  }
}

.c-header {
  @media (min-width: $breakpoint-md-min) {
    border-bottom: 2px solid #e6e6e6;
  }
}

.c-tabs {
  :deep() {
    .q-tab__label {
      font-size: 16px;
      font-weight: 500;
      line-height: 24px;
    }
  }
}

.c-tab {
  min-height: auto;
}
</style>
