<template>
  <div class="c-map">

    <div style="position: absolute;z-index: 100">
      <q-btn @click="showAllMarkers"/>
    </div>

    <yandex-map
        ref="mapRef"
        v-model="map"
        :settings="settings"
        width="100%"
        height="100%"
        @ready="mapEvents.onReady"
    >
      <yandex-map-controls :settings="{ position: 'top right' }">
        <yandex-map-geolocation-control/>
      </yandex-map-controls>
      <yandex-map-controls :settings="{ position: 'right' }">
        <yandex-map-zoom-control/>
      </yandex-map-controls>

      <yandex-map-default-scheme-layer/>
      <yandex-map-default-features-layer/>


      <yandex-map-marker
          v-for="marker in profileMarkers"
          :key="marker.entityId"
          position="top left-center"
          :settings="{
            coordinates: marker.coords,
            zIndex: marker.selected ? 100 : 1
          }"
      >
        <HomeMarker :marker="marker" @marker-click="onProfileClick(marker)"/>
      </yandex-map-marker>

      <yandex-map-marker
          v-for="marker in pickupMarkers"
          :key="marker.entityId"
          position="top left-center"
          :settings="{
            coordinates: marker.coords,
            zIndex: marker.selected ? 100 : 1
          }"
      >
        <OfficeMarker :marker="marker" @marker-click="onOfficeClick(marker)"/>
      </yandex-map-marker>

      <yandex-map-listener
          :settings="mapEvents"
      />

    </yandex-map>

    <div class="c-balloon q-px-sm q-py-sm" v-if="edit && geocoderState">
      <div class="__address s-font-xs text-weight-medium">{{ geocoderState.address_short }}</div>
    </div>

    <div
        class="c-placemark"
        v-if="edit"
        :class="{ lifted: isDragging }"
    >

      <q-icon color="primary" size="66px" :name="ICONS.map_placemark"/>

      <div v-if="geocoder.isLoading.value" class="__loading">
        <q-spinner color="white" size="25px"/>
      </div>

      <template v-else-if="geocoderState">
        <div class="__ok">
          <q-icon color="white" size="23px" :name="ICONS.check"/>
        </div>
      </template>
      <img v-else class="__logo" src="/logo-s-white.png">

      <div class="marker-pin"></div>
      <div :class="['shadow', { lifted: isDragging }]"></div>

    </div>

  </div>
</template>
<script setup lang="ts">

//region IMPORTS
import {computed, ref, shallowRef, toRefs, watch} from "vue";
import type {BehaviorMapEventHandler, LngLat, YMap, YMapCameraRequest, YMapLocationRequest} from '@yandex/ymaps3-types';
import {
  getBoundsFromCoords,
  getLocationFromBounds,
  YandexMap,
  YandexMapControls,
  YandexMapDefaultFeaturesLayer,
  YandexMapDefaultSchemeLayer,
  YandexMapGeolocationControl,
  YandexMapListener,
  YandexMapMarker,
  YandexMapSettings,
  YandexMapZoomControl,
} from 'vue-yandex-maps'
import {storeToRefs} from "pinia";
import {OrderProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";
import {ICONS} from "@/assets/icons";
import {useGeoStore} from "@/modules/geo/store/geo";
import {GeoCoordinates} from "@/modules/geo/class/GeoCoordinates";
import {useAsyncStateful} from "@/core/hooks/useAsyncStateful";
import {CompanyOffice, GeoObject} from "@/gql/gen";
import {DeliverySelectContextKey} from "@/pages/personal/order-profiles/util";
import {useTabbedViewContext} from "@/core/hooks/useTabbedViews";
import HomeMarker from "@/components/Map/Marker/HomeMarker.vue";
import OfficeMarker from "@/components/Map/Marker/OfficeMarker.vue";
import {Marker, MarkerOffice, MarkerProfile} from "@/components/Map/Marker/util";
import {debounce} from "lodash-es";
import {useOrderProfileStore} from "@/modules/shop/store/order-profile";
import {useShopStore} from "@/modules/shop/store/shop";
//endregion

//region TYPES
export type GeocoderResult = {
  coords: GeoCoordinates
  address_full: string
  address_short: string
  data: GeoObject
}

type NavigationNavTarget =
    { type: 'office'; model: CompanyOffice } |
    { type: 'profile'; model: OrderProfileModel } |
    { type: 'all'; model?: null }

export type DeliverySelectMapPublicApi = {
  nav: (position: YMapLocationRequest) => void,
  navTo: (target: NavigationNavTarget, zoom?: number) => void,
}
//endregion


//region STORES
const shopStore = useShopStore()
const orderProfileStore = useOrderProfileStore()
const {getLocationByCoords} = useGeoStore()

const {saleOffices} = storeToRefs(shopStore)
const {profilesModels} = storeToRefs(orderProfileStore)
//endregion

//region PROPS + EMITS
const emit = defineEmits<{
  'geocoder-resolved': [profileModel: OrderProfileModel, geocoderResult: GeocoderResult],
  'office-select': [office: CompanyOffice],
  'profile-select': [profile: OrderProfileModel]
}>()

const props = withDefaults(defineProps<{
  profileModel?: OrderProfileModel | null,
  office?: CompanyOffice | null,
  maxHeight?: string
}>(), {
  profiles: () => [],
  maxHeight: '100%'
})

const {profileModel, office} = toRefs(props)

const {
  viewName,
  viewTab,
  viewMode
} = useTabbedViewContext(DeliverySelectContextKey);
//endregion


//region STATE
const mapRef = ref(null);
const mapPosition = ref<YMapLocationRequest>({
  center: [104.32109, 52.28458], // starting position [lng, lat]
  zoom: 16, // starting zoom
})
const map = shallowRef<YMap | null>(null)
const camera = ref<YMapCameraRequest>({duration: 2500})
const isDragging = ref(false)
const geocoderState = ref<GeocoderResult | null>(null)
//endregion


//region COMPUTED
const settings = computed<YandexMapSettings>(() => {
  return {
    camera: camera.value,
    behaviors: ['drag', 'scrollZoom', 'dblClick', 'mouseRotate', 'mouseTilt'],
    location: mapPosition.value,
  }
})

const profileId = computed(() => profileModel.value?.id)
const edit = computed(() => viewName.value === 'profile.edit')

const profileMarkers = computed(() => {
  let result: MarkerProfile[] = []
  if (viewTab.value === 'profile' && viewMode.value !== 'edit') {
    result.push(...profilesModels.value.map<MarkerProfile>((item) => {
      return {
        type: 'profile',
        profile: item,
        entityId: item.id,
        coords: item.coords.value?.getYandexData() as any,
        name: item.address.value || '',
        selected: item.id === profileModel.value?.id,
        time: item.deliveryTimeMinutes.value
      }
    }).filter(item => !!item.coords))
  }
  return result
})

const pickupMarkers = computed(() => {
  let result: MarkerOffice[] = []
  if (viewTab.value === 'pickup') {
    result.push(...saleOffices.value.map<MarkerOffice>((item => {
      let coords: LngLat | null = null
      if (item.PROPERTIES.COORDINATES) {
        coords = GeoCoordinates.toObject(item.PROPERTIES.COORDINATES).getYandexData()
      }
      return {
        type: 'office',
        office: item,
        entityId: item.ID,
        coords: coords as any,
        name: item.NAME || '',
        selected: item.ID === office.value?.ID
      }
    })))
  }
  return result
})

const allMarkers = computed(() => {
  const result: Marker[] = []
  result.push(...profileMarkers.value)
  result.push(...pickupMarkers.value)
  return result
})

//endregion


//region TASKS
const geocoder = useAsyncStateful<GeocoderResult | null>(async (coords: LngLat) => {
  let result: GeocoderResult | null = null
  const _coords = GeoCoordinates.toObject(coords)
  const geoObject = await getLocationByCoords(_coords)
  if (geoObject && geoObject.address_full && geoObject.house) {
    result = {
      coords: _coords,
      address_full: geoObject.address_full || '',
      address_short: geoObject.address_short || '',
      data: geoObject,
    }
    if (profileModel.value)
      emit('geocoder-resolved', profileModel.value, result)
  }
  return result
}, geocoderState, {immediate: false, debounced: 1000})
//endregion


//region METHODS
const setNav = (position: YMapLocationRequest) => {
  mapPosition.value = {
    ...mapPosition.value,
    ...position
  }
}

const nav = (position: YMapLocationRequest) => {
  map.value?.setLocation({
    ...position,
    duration: 500,
    easing: 'linear'
  })
}

const getCoordinatesBoundsLocation = async (coordinates: any) => {
  return getLocationFromBounds({
    bounds: getBoundsFromCoords(coordinates),
    map: map.value!,
    roundZoom: true,
    comfortZoomLevel: true,
  })
}

const showAllMarkers = async () => {
  const coordinates = allMarkers.value.map(marker => marker.coords);
  map.value?.setLocation({
    ...await getCoordinatesBoundsLocation(coordinates),
    duration: 300,
  });
}
//endregion


//region EVENTS
const mapEvents = {
  onReady: () => {


  },
  onActionStart: ((e) => {
    if (e.type === 'drag') {
      isDragging.value = true
      if (edit.value && profileModel.value) {
        geocoder.state.value = null
        console.log('EDIT DRAG START', e)
      }
    }
  }) as BehaviorMapEventHandler,

  onActionEnd: ((e) => {
    if (e.type === 'drag') {
      isDragging.value = false
      if (edit.value && profileModel.value) {
        geocoder?.execute(e.location.center)
        isDragging.value = false
        console.log('EDIT DRAG END', e)
      }
    }
  }) as BehaviorMapEventHandler,

  onMarkerClick: (marker: Marker) => {

  }
}

const onOfficeClick = (marker: MarkerOffice) => {
  emit('office-select', marker.office)
}

const onProfileClick = (marker: MarkerProfile) => {
  emit('profile-select', marker.profile)
}

//endregion


//region WATCHERS

const navToInternal = (
    target: NavigationNavTarget,
    zoom = 16
) => {
  const {type, model} = target
  switch (type) {
    case "profile":
      if (model?.coords) {
        nav({
          center: model.coords.value?.getYandexData(),
          zoom
        })
      }
      break;
    case "office":
      if (model?.PROPERTIES.COORDINATES) {
        nav({
          center: GeoCoordinates.toObject(model.PROPERTIES.COORDINATES).getYandexData(),
          zoom
        })
      }
      break;
    case "all":
      showAllMarkers()
  }
}

const navTo = debounce(navToInternal, 100)

watch(viewTab, () => {
  console.log('WATCH TAB', viewTab.value)

  if (viewTab.value === 'pickup' && office.value) {
    navTo({type: 'office', model: office.value})
  } else if (viewTab.value === 'profile' && profileModel.value) {
    navTo({type: 'profile', model: profileModel.value})
  } else {
    navTo({type: 'all'})
  }
})

watch(() => office.value?.ID, () => {
  if (office.value)
    navTo({type: 'office', model: office.value})
  else {
    navTo({type: 'all'})
  }
})

watch(profilesModels, () => {
  if (profilesModels.value.length) {
    //setTimeout(() => showAllMarkers(), 200)
  }
}, {immediate: true, once: true})

watch(viewName, () => {
  if (viewName.value === 'profile.edit') {
    geocoder.state.value = {
      coords: profileModel.value?.coords.value,
      address_short: profileModel.value?.address.value || ''
    } as GeocoderResult
  } else {
    geocoder.state.value = null
  }
})

//endregion

defineExpose<DeliverySelectMapPublicApi>({
  nav,
  navTo
})

</script>
<style scoped lang="scss">

.c-map {
  height: calc(90vh - 32px);
  position: relative;
}

.map {
  height: 100%;
  width: 100%;
}

.c-balloon {
  pointer-events: none;
  position: absolute;
  max-width: 70%;
  bottom: calc(50% + 69px);
  left: 50%;
  z-index: 1000;
  background-color: #FFFFFF;
  border: 1px solid #CCCCCC;
  border-radius: 10px;
  transform: translate(-50%, 0);
}

.c-placemark {
  position: absolute;
  top: calc(50% - 33px);
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 1000;
  transition: .3s;

  .__logo {
    top: 8px;
    position: absolute;
    left: 50%;
    transform: translate(-50%, 0%);
    width: 18px;
  }

  .__loading {
    top: 6px;
    position: absolute;
    left: 50%;
    transform: translate(-50%, 0%);
  }


  .__ok {
    top: 6px;
    position: absolute;
    left: 50%;
    transform: translate(-50%, 0%);
  }

  .marker-pin {
    position: absolute;
    width: 40px;
    height: 40px;
    border-radius: 50% 50% 50% 0;
    transform: rotate(-45deg);
    left: 50%;
    top: 0;
    margin-left: -20px;
    transition: all 0.3s ease;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16);
  }

  .shadow {
    position: absolute;
    width: 20px;
    height: 8px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 50%;
    bottom: 0;
    left: 50%;
    margin-left: -10px;
    transition: all 0.3s ease;
  }

  &.lifted {
    transform: translate(-50%, -70%);
  }

  &.lifted .marker-pin {
    transform: rotate(-45deg) scale(1.1);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  }

  &.lifted .marker-shadow {
    transform: scale(0.7);
    opacity: 0.7;
  }
}


</style>
