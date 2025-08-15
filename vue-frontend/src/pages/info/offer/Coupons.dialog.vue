<template>
  <StackItemModal
      :dialog="{
      }"
      title="Промокоды"
      :actionClose="true"
      v-bind="bind"
  >

    <div class="q-px-xs  q-py-md">

      <div class="q-gutter-md">

        <div class="c-item q-pa-sm">
          <div class="row">
            <div class="col-auto">
              <div class="c-name">2ГИС</div>
            </div>
            <div class="col-auto q-ml-auto">
              <div class="c-desc">ролл в подарок</div>
            </div>
          </div>
        </div>

        <div class="c-item q-pa-sm">
          <div class="row">
            <div class="col-auto">
              <div class="c-name">Начало</div>
            </div>
            <div class="col-auto q-ml-auto">
              <div class="c-desc">ролл в подарок</div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {computed, ref, toRefs} from "vue";
import {useOfferStore} from "@/modules/offer/store/offer";
import {storeToRefs} from "pinia";
import {CarouselConfig} from "vue3-carousel";

const offerStore = useOfferStore()
const {offersCommonComputed} = storeToRefs(offerStore)

const props = withDefaults(defineProps<StackComponentProps & {
  id?: number
}>(), {})

const {bind} = useStackComponent<StackItemModalState>(props)

const {id} = toRefs(props)

</script>
<style scoped>

.image {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.c-item {
  border-radius: 10px;
  box-shadow: 1px 2px 8px 0px rgba(34, 60, 80, 0.2);
}

.c-name {
  font-weight: bold;
}

.c-desc {
  color: #777777;
}

</style>
