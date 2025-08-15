<template>
  <div>
    <yandex-map
        v-model="map"
        :settings="{
        location: {
          center: [37.617644, 55.755819],
          zoom: 9,
        },
      }"
        width="100%"
        height="300px"
    >
      <yandex-map-default-scheme-layer/>
      <yandex-map-default-features-layer/>
      <yandex-map-marker
          position="top-center left-center"
          :settings="{
            coordinates: [37.617644, 55.755819],

          }"
      >
        <div class="marker">
          <q-icon :name="ICONS.map_placemark" color="primary" size="50px"/>
        </div>
      </yandex-map-marker>
    </yandex-map>

  </div>
</template>

<script setup lang="ts">

import {shallowRef} from 'vue';
import type {YMap} from '@yandex/ymaps3-types';
import {
  YandexMap,
  YandexMapDefaultFeaturesLayer,
  YandexMapMarker,
  YandexMapDefaultSchemeLayer,
} from 'vue-yandex-maps';
import {ICONS} from "@/assets/icons";

//Можно использовать для различных преобразований
const map = shallowRef<null | YMap>(null)



</script>

<style>

</style>
