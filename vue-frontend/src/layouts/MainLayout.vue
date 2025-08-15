<template>
  <q-layout view="hHh lpR fFf">

    <q-no-ssr>
      <MobileBottomPanel v-if="$q.screen.lt.lg"/>
    </q-no-ssr>

    <Header/>

    <Drawer/>

    <q-page-container>

      <router-view name="top"/>

      <div class="row">
        <div class="col-24" :class="{ 'col-lg-18': !layoutContentWide }">
          <div class="container nopad-lt-md">
            <router-view :include="routerStore.cachedComponents" :max="5"/>
          </div>
        </div>
        <q-no-ssr>
          <div class="col-24 col-lg-6" v-if="$q.screen.gt.md && !layoutContentWide">
            <VOrder/>
          </div>
        </q-no-ssr>
      </div>

    </q-page-container>

  </q-layout>
</template>

<script setup lang="ts">
import Header from './shared/Header.vue'
import Drawer from './shared/Drawer.vue'
import {useLayoutStore} from "@/stores/layout";
import {storeToRefs} from "pinia";
import {useQuasar} from "quasar";
import VOrder from '@/components/VOrder/VOrder.vue'
import {useRouterStore} from "@/core/router/store";
import {useConfig} from "@/core/store/config";
import MobileBottomPanel from "@/layouts/shared/MobileBottomPanel.vue";
import {computed} from 'vue';

const $q = useQuasar()

const layoutStore = useLayoutStore()
const {drawerBasket, headerHeight} = storeToRefs(layoutStore)

const routerStore = useRouterStore()
const config = useConfig()

const {routeMeta} = storeToRefs(routerStore)

const layoutContentWide = computed(() => routeMeta.value.layoutContentWide)

</script>

<style scoped lang="scss"></style>
