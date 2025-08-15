<template>
  <q-page class=" ">

    <PushSubscribeButton/>

    <div
        class="q-mx-md q-mx-md-none"
        id="sections"
        v-intersection="scrollNav.intersectDirectiveConnect('sections')"
    >
      <Sections
          class="q-pb-xl"
      />
    </div>

    <div class="q-gutter-xl" ref="scrollContainer">
      <div
          v-for="section in sectionsTree"
          :key="section.ID"
          v-intersection="scrollNav.intersectDirectiveConnect(section.CODE || '')"
          :id="section.CODE || ''"
      >
        <ProductSectionView
            :section="section"
            @filtered="onSectionFiltered"
        />
      </div>
    </div>

  </q-page>
</template>

<script setup lang="ts">
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {storeToRefs} from "pinia";
import ProductSectionView from "./Section.vue";
import {usePageFrontStore} from "@/stores/page-front";
import Sections from "./Sections.vue";
import PushSubscribeButton from "@/components/PushSubscribeButton.vue";
import {ProductSection} from "@/gql/gen";

const catalogStore = useCatalogStore()
const {sectionsTree} = storeToRefs(catalogStore)

const pageFrontStore = usePageFrontStore()

const {scrollNav} = pageFrontStore

const onSectionFiltered = (section: ProductSection) => {
  scrollNav.scrollTo(section.CODE)
}

</script>

<style>

</style>
