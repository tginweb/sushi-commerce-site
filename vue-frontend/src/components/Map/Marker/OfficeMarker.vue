<template>
  <div
      class="c-marker c-marker-office"
      :class="{'--selected': marker.selected}"
      @click="onClick"
  >
    <div class="__pointer">
      <q-icon
          :name="ICONS.map_placemark"
          size="56px"
          color="primary"
          class="__icon"
      />
    </div>
    <div class="__title">
      <div class="__title-bg">
      </div>
      <div class="__title-text" v-if="marker.selected">
        {{ marker.name }}
      </div>
    </div>
    <img class="__logo" src="/logo-s-white.png">
  </div>
</template>

<script setup lang="ts">
import {ICONS} from "@/assets/icons";
import {MarkerEmits, MarkerProps, useMarker} from "@/components/Map/Marker/util";

//region PROPS
const props = withDefaults(defineProps<MarkerProps>(), {})
//endregion

const emit = defineEmits<MarkerEmits>()
const {marker, onClick} = useMarker(props, emit)

</script>

<style lang="scss" scoped>
@import 'styles';

.c-marker-office {

}
</style>
