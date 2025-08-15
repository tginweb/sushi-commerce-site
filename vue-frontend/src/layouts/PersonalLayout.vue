<template>
  <div class="q-pt-lg">

    <UiBreadcrumbs
        :path="breadcrumbs"
        class="q-mb-sm"
    />

    <div class="c-header flex items-center no-wrap q-mb-md">
      <h1 class="c-header-title text-weight-bold leading-normal q-ma-none s-font-5xl font-tenor-sans">
        {{ routeMeta.title }}
      </h1>
    </div>


    <div class="c-desktop-tabs">
      <q-tabs
          v-model="menuTab"
          active-class="text-primary"
          align="justify"
          class=""
          indicator-color="primary"
          item-class="q-px-sm q-px-xl-none"
          style="margin-bottom: -2px;"
          inline-label
      >
        <q-route-tab
            :to="item.url"
            :exact="false"
            v-for="(item, index) in menuPersonal"
            :key="item.id || index"
            :label="item.label as string"
            :icon="resolveIcon(item.icon)"
            class="q-px-sm"
        />
      </q-tabs>
    </div>

    <div class="q-mt-lg" style="position: relative">
      <ProgressInnerLoading :model-value="'loading'" v-if="!fetchedScopes.user"/>
      <template v-else>
        <router-view/>
      </template>
    </div>

  </div>
</template>

<script setup lang="ts">
import {storeToRefs} from "pinia";
import {useQuasar} from "quasar";
import {ref} from "vue";
import {useRouterStore} from "@/core/router/store";
import {useUserStore} from "@/modules/user/store/user";
import {useRootStore} from "@/stores/root";
import {resolveIcon} from "@/assets/icons";
import UiBreadcrumbs from "@/components/UiBreadcrumbs.vue";
import useBreadcrumbs from "@/core/hooks/useBreadcrumbs";
import {useScopeQuery} from "@/core/store/scopeQuery";
import ProgressInnerLoading from "@/components/Progress/ProgressInnerLoading.vue";
import {MenuItem} from "@/gql/gen";
import Alerts from "@/components/Alert/Alerts.vue";

const $q = useQuasar()

const scopeQuery = useScopeQuery()
const routerStore = useRouterStore()

const {fetchedScopes} = storeToRefs(scopeQuery)
const {routeMeta} = storeToRefs(routerStore)

const menuTab = ref()

const userStore = useUserStore()
const {storeGetter} = useRootStore()

const {isAuthorized, menuPersonal} = storeToRefs(userStore)

const breadcrumbs = useBreadcrumbs()

</script>
<style lang="scss" scoped>

.c-desktop-tabs {
  border-bottom: 3px solid rgb(238, 238, 238);

  :deep() {
    .q-tab__icon {
      width: 20px;
      height: 20px;
      font-size: 20px;
    }

    .q-tab__label {
      font-size: 15px;
    }
  }
}

</style>
