<template>
  <div
      class="c-marker c-marker-profile"
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
      <div class="__delivery-time" v-if="marker.time">
        <div class="s-font-xs text-weight-bold">{{ marker.time }}</div>
        <div class="__delivery-time__label s-font-4xs">мин</div>
      </div>
      <q-icon
          :name="ICONS.evaHomeOutline"
          size="24px"
          class="__home"
          color="white"
          v-else
      />
    </div>
    <div class="__title">
      <div class="__title-bg"></div>
      <div class="__title-text" v-if="marker.selected">
        {{ marker.name }}
      </div>
    </div>
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
