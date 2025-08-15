<template>
  <q-page class=" ">

    <q-tabs
        v-model="tab"
        :breakpoint="0"
        active-bg-color="primary"
        active-color="white"
        align="left"
        class="c-tabs q-mb-lg text-primary"
        color="primary"
    >
      <q-tab label="Доставка" name="delivery"/>
      <q-tab label="Самовывоз" name="pickup"/>
    </q-tabs>

    <q-tab-panels v-model="tab" animated>

      <q-tab-panel class="q-px-none q-py-none" name="delivery">
        <CDelivery/>
      </q-tab-panel>

      <q-tab-panel class="q-px-none q-py-none" name="pickup">
        <CPickup/>
      </q-tab-panel>

    </q-tab-panels>

  </q-page>
</template>

<script setup lang="ts">
import {useRouterStore} from "@/core/router/store"
import {storeToRefs} from "pinia";
import {useMeta} from "quasar";
import {ref} from "vue";

const routerStore = useRouterStore()
const {htmlMeta} = storeToRefs(routerStore)

useMeta(() => htmlMeta.value)

const tab = ref('delivery')

</script>

<style>

</style>
