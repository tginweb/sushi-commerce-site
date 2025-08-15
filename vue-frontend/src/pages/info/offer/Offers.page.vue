<template>
  <q-page class="">
    <div class="c-items row q-col-gutter-xl">
      <div
          v-for="offer in offersCommonComputed"
          :key="offer.ID as number"
          class="c-entity cursor-pointer col-24 col-md-8"
      >
        <router-link
            class="c-item"
            :to="'/offer/' + offer.ID"
        >

          <div class="c-image leading-zero" v-if="offer.BANNER_SQUARE?.SRC">
            <img
                :src="'https://irkutsk.sushi-studio.ru' + offer.BANNER_SQUARE?.SRC"
                class="full-width"
            />
          </div>

          <h3 class="q-mb-none q-mt-md text-weight-bolder text-primary s-font-2xl">
            {{ offer.NAME }}
          </h3>

        </router-link>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">

import {storeToRefs} from "pinia";
import {useOfferStore} from "@/modules/offer/store/offer";
import {useImager} from "@/core/store/imager";

const {imageResolve} = useImager()
const offerStore = useOfferStore()
const {offersCommonComputed} = storeToRefs(offerStore)

</script>

<style>

</style>
