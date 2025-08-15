<template>
  <div>
    <div class="row q-col-gutter-lg q-pb-xl">
      <div v-for="entity in bonusLevels" :key="entity.ID" class="c-item-col col-24 col-md-12 col-lg-12 col-xl-6">

        <div class="c-item shadow-1 q-pa-md">
          <div class="s-font-lg text-primary text-bold q-pb-md">
            {{ entity.NAME }}
          </div>
          <div class="q-gutter-md">

            <div class="q-gutter-y-xs">
              <div class="text-weight-medium s-font-md">
                {{ entity.PROPERTIES.ORDERS_SUMM }}
              </div>
            </div>

            <div class="q-gutter-y-xs">
              <div class="text-weight-medium s-font-md">
                Накопления
              </div>
              <div class="flex items-center s-font-md text-weight-medium text-primary">
                <q-icon :name="ICONS.bonus" class="q-pr-sm"/>
                {{ entity.PROPERTIES.ACCUMULATION_PERCENT }}%
              </div>
            </div>

            <div class="q-gutter-y-xs">
              <div class="text-weight-medium s-font-md">
                Можно оплатить
              </div>
              <div class=" flex items-center s-font-md text-weight-medium text-primary">
                <q-icon :name="ICONS.fasPercent" class="q-pr-sm"/>
                до {{ entity.PROPERTIES.MAX_USE_PERCENT }}%
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import {ICONS} from '@/assets/icons';
import {storeToRefs} from 'pinia';
import {useLoalityStore} from "@/modules/shop/store/loyalty";

const loalityStore = useLoalityStore()
const {bonusLevels} = storeToRefs(loalityStore)
</script>
<style lang="scss" scoped>
@media (min-width: $breakpoint-xl-min) {
  .c-item-col {
    width: 20%;
  }
}
.c-item {
  border-radius: 16px;
  height: 100%;
}
</style>
