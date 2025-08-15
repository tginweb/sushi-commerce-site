<template>
  <StackItemModal
      :dialog="{
        containerClass: '',
        footerClass: 'q-px-lg q-py-md',
        headerHide: true,
        bodyMaxHeight: '80vh',
        nav,
        onNavNext,
        onNavPrev
      }"
      :actionClose="true"
      v-bind="bind"
  >
    <div v-if="currentOffer">
      <div class="">
        <img
            :src="'https://irkutsk.sushi-studio.ru' + currentOffer.BANNER_HOR_MOBILE?.SRC"
            style="width: 100%;"
            class="image"
        />
      </div>
      <div class="q-px-lg q-py-md q-gutter-y-md">
        <div class="s-font-md s-font-md-lg s-font-lg-xl text-weight-bold" v-html="currentOffer.NAME"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
        <div class="c-content" v-if="currentOffer.DETAIL_TEXT" v-html="currentOffer.DETAIL_TEXT"></div>
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
  id: string
}>(), {})

const {bind} = useStackComponent<StackItemModalState>(props)

const {id} = toRefs(props)

const currentOfferId = ref<number>(id.value ? parseInt(id.value) : 0)
const currentOfferIndex = computed(() => offersCommonComputed.value.findIndex(offer => offer.ID === currentOfferId.value) || 0)
const currentOffer = computed(() => offersCommonComputed.value.find(offer => offer.ID === currentOfferId.value))

const carouselOptions = computed<Partial<CarouselConfig>>(() => {
  return {
    itemsToShow: 1,
    itemsToScroll: 1,
  }
})

const prevId = computed(() => {
  if (currentOfferIndex.value > 0 && offersCommonComputed.value[currentOfferIndex.value - 1]) {
    return offersCommonComputed?.value[currentOfferIndex.value - 1]?.ID || 0
  } else {
    return 0
  }
})

const nextId = computed(() => {
  if ((currentOfferIndex.value < offersCommonComputed.value.length - 1) && offersCommonComputed.value[currentOfferIndex.value + 1]) {
    return offersCommonComputed?.value[currentOfferIndex.value + 1]?.ID || 0
  } else {
    return 0
  }
})

const onNavNext = () => {
  currentOfferId.value = nextId.value
}

const onNavPrev = () => {
  currentOfferId.value = prevId.value
}

const nav = computed(() => {
  if (nextId.value && prevId.value) {
    return 'all'
  } else if (nextId.value) {
    return 'next'
  } else if (prevId.value) {
    return 'prev'
  }
})

</script>
<style>

.image {
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

</style>
