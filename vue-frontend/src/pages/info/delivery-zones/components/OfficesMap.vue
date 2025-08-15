<template>
  <div class="offices-map">
    <yandex-map
      v-model="map"
      :settings="{
        location: {
          center: [104.32109, 52.28458],
          zoom: 9,
        },
      }"
      width="100%"
      height="100%"
      @ready="onMapReady"
    >
      <yandex-map-default-scheme-layer />
      <yandex-map-default-features-layer />

      <!-- Маркеры офисов -->
      <template v-for="office in officesWithCoords" :key="`marker-${office.ID}`">
        <yandex-map-marker
          :settings="{
            coordinates: office.coordinates,
          }"
          @click="onOfficeClick(office.ID)"
        >
          <div class="office-marker" :class="{ 'active': office.ID === activeOfficeId }">
            <q-icon
              :name="getOfficeIcon(office)"
              :color="office.ID === activeOfficeId ? 'primary' : 'grey-6'"
              size="24px"
            />
            <div v-if="office.ID === activeOfficeId" class="office-label">
              {{ office.NAME }}
            </div>
          </div>
        </yandex-map-marker>
      </template>
    </yandex-map>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, nextTick } from 'vue'
import { shallowRef } from 'vue'
import type { YMap } from '@yandex/ymaps3-types'
import {
  YandexMap,
  YandexMapDefaultFeaturesLayer,
  YandexMapDefaultSchemeLayer,
  YandexMapMarker,
} from 'vue-yandex-maps'
import { CompanyOffice } from '@/gql/gen'
import { ICONS } from '@/assets/icons'

interface Props {
  offices: CompanyOffice[]
  activeOfficeId?: number | null | undefined
}

interface OfficeWithCoords extends CompanyOffice {
  coordinates: [number, number]
}

const props = defineProps<Props>()

const emit = defineEmits<{
  officeSelect: [officeId: number]
}>()

const map = shallowRef<YMap | null>(null)

const onMapReady = (mapInstance: YMap) => {
  map.value = mapInstance
}

const onOfficeClick = (officeId: number) => {
  emit('officeSelect', officeId)
}

// Получаем иконку в зависимости от роли офиса
const getOfficeIcon = (office: CompanyOffice) => {
  const roles = office.PROPERTIES?.ROLES || []
  if (roles.some(role => role?.VALUE === 'delivery')) {
    return ICONS.local_shipping
  }
  if (roles.some(role => role?.VALUE === 'bar')) {
    return ICONS.restaurant
  }
  return ICONS.map_placemark
}

// Вычисляем координаты офисов
const officesWithCoords = computed<OfficeWithCoords[]>(() => {
  return props.offices.map(office => {
    const coords = office.PROPERTIES?.COORDINATES
    let coordinates: [number, number] = [37.617644, 55.755819] // Москва по умолчанию

    if (coords) {
      coordinates = [coords.LON || 37.617644, coords.LAT || 55.755819]
    }

    return {
      ...office,
      coordinates
    }
  })
})

// Следим за изменением активного офиса и центрируем карту
watch(() => props.activeOfficeId, (newOfficeId) => {
  if (newOfficeId && map.value) {
    const office = officesWithCoords.value.find(o => o.ID === newOfficeId)
    if (office) {
      nextTick(() => {
        map.value?.setLocation({
          center: office.coordinates,
          zoom: 14
        })
      })
    }
  }
})
</script>

<style lang="scss" scoped>
.offices-map {
  width: 100%;
  height: 100%;
  min-height: 400px;

  @media (max-width: 1023px) {
    min-height: 350px;
  }

  @media (max-width: 599px) {
    min-height: 250px;
  }
}

.office-marker {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: white;
  border-radius: 50%;
  padding: 4px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;

  &.active {
    transform: scale(1.2);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  }
}

.office-label {
  position: absolute;
  top: 100%;
  left: 50%;
  transform: translateX(-50%);
  background: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  margin-top: 4px;
}
</style>
