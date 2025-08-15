<template>
  <div class="c-banners q-my-lg">
    <q-no-ssr>
      <Carousel
          v-bind="optionsDesktop"
          @slide-start="handleSlideStart"
          @slide-end="handleSlideEnd"
          @drag="handleDrag"
          v-if="$q.screen.gt.sm"
      >
        <CarouselSlide
            v-for="offer in offersCommonComputed"
            :key="offer.ID"
            @click="onSlideClick(offer)"
            class="c-slide-desktop"
        >
          <div
              class="c-slide__inner"
              :style="{
              backgroundImage: 'url(' + 'https://irkutsk.sushi-studio.ru' + offer.BANNER_HOR_DESKTOP?.SRC + ')'
            }"
          >
          </div>
        </CarouselSlide>
        <template #addons>
          <CarouselNavigation/>
          <CarouselPagination/>
        </template>
      </Carousel>
      <Carousel
          v-bind="optionsMobile"
          @slide-start="handleSlideStart"
          @slide-end="handleSlideEnd"
          @drag="handleDrag"
          v-else
      >
        <CarouselSlide
            v-for="offer in offersCommonComputed"
            :key="offer.ID"
            @click="onSlideClick(offer)"
            class="c-slide-mobile"
        >
          <div
              class="c-slide__inner"
              :style="{
              backgroundImage: 'url(' + 'https://irkutsk.sushi-studio.ru' + offer.BANNER_HOR_MOBILE?.SRC + ')'
            }"
          >
          </div>
        </CarouselSlide>
        <template #addons>
          <CarouselNavigation/>
          <CarouselPagination/>
        </template>
      </Carousel>
    </q-no-ssr>
  </div>
</template>
<script setup lang="ts">

import {OfferModel, useOfferStore} from "@/modules/offer/store/offer";
import {storeToRefs} from "pinia";
import {computed, ref} from "vue";
import {CarouselConfig} from "vue3-carousel";
import {useQuasar} from "quasar";
import {useRouter} from "vue-router";
import {Offer} from "@/gql/gen";
import {useTimeoutFn} from "@vueuse/core";

const $q = useQuasar()

const router = useRouter()
const offerStore = useOfferStore()
const {offersCommonComputed} = storeToRefs(offerStore)

const optionsDesktop = computed<Partial<CarouselConfig>>(() => {
  return {
    itemsToShow: 1.9,
    itemsToScroll: 1,
    wrapAround: true,
    snapAlign: 'center',
    gap: 20,
    preventExcessiveDragging: false
  }
})

const optionsMobile = computed<Partial<CarouselConfig>>(() => {
  return {
    itemsToShow: 1,
    itemsToScroll: 1,
    wrapAround: false,
    snapAlign: 'center',
    gap: 20
  }
})

const justSlideEnd = ref(false)
const isSliding = ref(false)
const currentOffer = ref<OfferModel>()

const openSlide = () => {
  if (currentOffer.value)
    router.push('/offer/' + currentOffer.value.ID)
}

const openSlideTimeout = useTimeoutFn(openSlide, 100)

const onSlideClick = (item: Offer) => {
  if (justSlideEnd.value || isSliding.value)
    return;
  currentOffer.value = item
  openSlideTimeout.start()
}

const handleSlideStart = () => {

}

const handleSlideEnd = () => {
  justSlideEnd.value = true
  isSliding.value = false
  setTimeout(() => {
    justSlideEnd.value = false
  }, 100)
}

const handleDrag = (e: any) => {
  if (Math.abs(e.deltaX) > 5) {
    isSliding.value = true
    openSlideTimeout.stop()
  }
}

</script>
<style lang="scss" scoped>

.c-slide-desktop {
  cursor: pointer;
  transition: all 0.2s ease;

  &:not(.carousel__slide--active) {
    transform: scale(1, 0.9);
  }

  .c-slide__inner {
    width: 100%;
    border-radius: 15px;
    background-repeat: no-repeat;
    height: 20vw;
    background-position: center center;
    background-size: cover;
  }

  img {
    border-radius: 15px;
    height: 20vw;
    width: auto;
  }
}

.c-slide-mobile {
  transition: all 0.2s ease;

  &:not(.carousel__slide--active) {
    transform: scale(1, 0.9);
  }

  .c-slide__inner {
    width: 100%;
    border-radius: 15px;
    background-repeat: no-repeat;
    height: 40vw;
    background-position: center center;
    background-size: cover;
  }

  img {
    border-radius: 0px;
    height: 40vw;
    width: auto;
  }
}

</style>
