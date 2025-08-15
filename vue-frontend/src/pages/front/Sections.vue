<template>
  <div class="sections row q-col-gutter-x-sm q-col-gutter-y-sm q-col-gutter-lg-lg">
    <div
        v-for="item of sections"
        :key="item.ID"
        class="c-item col-8 col-md-4"
    >
      <div
          class="c-item__inner bg-white q-py-md text-center full-height cursor-pointer"
          @click="onClick(item)"
      >
        <q-img
            v-if="item.PICTURE?.SRC"
            :ratio="16/9"
            :src="imageResolve(item.PICTURE?.SRC, 'm-1')"
            fit="contain"
        />
        <div class="c-item__label text-weight-bolder s-font-xs s-font-md-md q-mt-md q-px-sm">{{ item.NAME }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

import {useCatalogStore} from "@/modules/shop/store/catalog";
import {storeToRefs} from "pinia";
import {computed} from "vue";
import {useImager} from "@/core/store/imager";
import {useRouter} from "vue-router";
import {ProductSection} from "@/gql/gen";

const router = useRouter()
const {imageResolve} = useImager()

const catalogStore = useCatalogStore()

const {sectionsTree} = storeToRefs(catalogStore)

const sections = computed(() => sectionsTree.value.filter(section => section.PICTURE))

const onClick = (item: ProductSection) => {
  router.push({path: '/', hash: '#' + item.CODE})
}

</script>

<style lang="scss" scoped>

.c-item {

}

.c-item__inner {
  border-radius: 20px;
  box-shadow: 0px 5px 15px -5px rgba(34, 60, 80, 0.2);
  border: 1px solid #eee;
}

.c-item__label {
  line-height: 1.1;
}


</style>
