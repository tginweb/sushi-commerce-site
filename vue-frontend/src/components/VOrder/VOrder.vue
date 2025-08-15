<template>
  <q-form
      v-if="isMounted"
      ref="form"
      class="c-vorder-desktop"
      :style="{
        top: sideColumnTopOffsetCss,
        height: windowFreeHeightCss,
      }"
      v-sticky-state="{
        debugId: 'vorder',
        activeClass: 'stacked',
        top: sideColumnTopOffsetCss,
      }"
  >

    <q-resize-observer @resize="onResizeContainer"/>

    <div class="c-vorder-desktop__inner bg-white">
      <div class="c-header">

        <q-tabs
            v-model="tab"
            class="c-tabs"
            dense
            indicator-color="primary"
            narrow-indicator
            active-color="primary"
            align="left"
            style="border-bottom: 1px solid #dddddd;"
        >
          <q-tab
              name="basket"
              class=""
              label="Корзина"
          />
          <q-tab
              name="form"
              class=""
              label="Заказ"
          />
        </q-tabs>
        <q-resize-observer @resize="onResizeHeader"/>
      </div>
      <div
          class="c-body scroll"
          :style="{
          maxHeight: freeSizeCss
        }"
      >

        <q-tab-panels
            v-model="tab"
            animated
        >
          <q-tab-panel name="basket" class="q-py-md">
            <Basket/>
          </q-tab-panel>
          <q-tab-panel name="form" class="q-py-sm">
            <Form/>
          </q-tab-panel>
        </q-tab-panels>

        <q-input outlined label="address" v-model="attrs.ADDRESS" v-if="false"/>
        <q-input outlined label="house" v-model="attrs.HOUSE" v-if="false"/>

        <JsonViewer :value="discountDebug" v-if="false"/>

      </div>

      <div class="c-footer q-py-md q-px-md" style="border-top: 1px solid #dddddd">

        <Summary/>

        <template v-if="!basketEmpty">
          <q-btn
              v-if="tab === 'basket'"
              color="primary"
              label="Перейти к заказу"
              class="full-width q-mt-md"
              @click="onChangeTabForm"
          />
          <q-btn
              v-else
              color="primary"
              label="Оформить заказ"
              class="full-width q-mt-md"
              @click="onOrderSubmit"
          />
        </template>


        <q-resize-observer @resize="onResizeFooter"/>
      </div>

    </div>

  </q-form>

</template>

<script setup lang="ts">

import Basket from "./Basket.vue";
import Form from "./Form.vue";
import Summary from "./Summary.vue";

import {storeToRefs} from "pinia";
import {computed, provide} from "vue";
import {QForm, useQuasar} from "quasar";
import {VOrderValidateInject} from "./constants";
import {useLayout} from "@/core/hooks/useLayout";
import {useLayoutStore} from "@/stores/layout";
import {useVorderComponent} from "@/components/VOrder/hooks";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {useMounted} from "@vueuse/core";

const q = useQuasar()

const layoutStore = useLayoutStore()
const {sideColumnTopOffsetCss, sideColumnTopOffset} = storeToRefs(layoutStore)

const layout = useLayout()
const {onResizeContainer, onResizeHeader, onResizeFooter} = layout
const {freeSizeCss} = layout

const vorderComponent = useVorderComponent()
const {validate, onMutate, attrs} = vorderComponent

provide(VOrderValidateInject, validate)

const windowFreeHeightCss = computed(() => {
  return (q.screen.height - sideColumnTopOffset.value) + 'px'
})

const vorderStore = useVorderStore()
const {
  basketRulesTree,
  currentProfile,
  tab,
  basketEmpty,
  basketRulesActive,
  basketDiscountActive,
  benefitType,
  basketRulesActiveSummary
} = storeToRefs(vorderStore)

const discountDebug = computed(() => {
  return {
    basketRulesTree,
    basketRulesActive: basketRulesActive.value,
    currentProfile
  }
})

const isMounted = useMounted()


const onChangeTabForm = () => {
  tab.value = 'form'
}

const onOrderSubmit = () => {
  console.log('submit')
}

</script>

<style scoped lang="scss">

.c-vorder-desktop {
  transition: all 0.3s ease-in-out;
  position: sticky;

  &:not(.stacked) {

  }

  .c-vorder-desktop__inner {
    box-shadow: 0 5px 15px -5px rgba(34, 60, 80, 0.2);
    border-radius: 8px;
  }
}

.c-body {
  transition: all 0.3s ease-in-out;
  //border: 1px solid#333333;
}

.c-header {
  //border: 1px solid #AAAAAA;
  //background-color: #eeeeee;
}

.c-footer {
  z-index: 100;
  //background-color: #eeeeee;
  //position: fixed;
}

</style>
