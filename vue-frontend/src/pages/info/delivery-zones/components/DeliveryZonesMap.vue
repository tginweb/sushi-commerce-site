<template>
  <div class="delivery-zones-map">
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

      <!-- Полигоны зон -->
      <template v-for="zone in zonesWithPolygons" :key="`polygon-${zone.ID}`">
        <yandex-map-feature
          :settings="{
            geometry: {
              type: 'Polygon',
              coordinates: zone.polygonCoordinates
            },
            properties: {
              zoneId: zone.ID
            },
            style: {
              fill: zone.PROPERTIES?.COLOR || '#1976d2',
              fillOpacity: zone.ID === activeZoneId ? 0.6 : 0.3
            }
          }"
          @click="onZoneClick(zone.ID)"
        />
      </template>

      <!-- Маркеры центров зон -->
      <template v-for="zone in zonesWithCenters" :key="`marker-${zone.ID}`">
        <yandex-map-marker
          :settings="{
            coordinates: zone.centerCoordinates,
          }"
          @click="onZoneClick(zone.ID)"
        >
          <div class="zone-marker" :class="{ 'active': zone.ID === activeZoneId }">
            <q-icon
              name="map_placemark"
              :color="zone.ID === activeZoneId ? 'primary' : 'grey-6'"
              size="20px"
            />
            <div v-if="zone.ID === activeZoneId" class="zone-label">
              {{ zone.NAME }}
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
  YandexMapFeature,
} from 'vue-yandex-maps'
import { DeliveryZone } from '@/gql/gen'

interface Props {
  zones: DeliveryZone[]
  activeZoneId?: number | null | undefined
}

interface ZoneWithPolygon extends DeliveryZone {
  polygonCoordinates: [number, number][][]
}

interface ZoneWithCenter extends DeliveryZone {
  centerCoordinates: [number, number]
}

const props = defineProps<Props>()

const emit = defineEmits<{
  zoneSelect: [zoneId: number]
}>()

const map = shallowRef<YMap | null>(null)

const onMapReady = (mapInstance: YMap) => {
  map.value = mapInstance
}

const onZoneClick = (zoneId: number) => {
  emit('zoneSelect', zoneId)
}

// Вычисляем полигоны зон
const zonesWithPolygons = computed<ZoneWithPolygon[]>(() => {
  return props.zones
    .filter(zone => zone.PROPERTIES?.GEOJSON)
    .map(zone => {
      let polygonCoordinates: [number, number][][] = []

      try {
        const geojson = zone.PROPERTIES?.GEOJSON || {}
        if (geojson.type === 'Polygon' && geojson.coordinates) {
          polygonCoordinates = geojson.coordinates.map((ring: number[][]) =>
            ring.map(point => [point[0], point[1]] as [number, number])
          )
        } else if (geojson.type === 'MultiPolygon' && geojson.coordinates) {
          polygonCoordinates = (geojson.coordinates[0] || []).map((ring: number[][]) =>
            ring.map(point => [point[0], point[1]] as [number, number])
          )
        }
      } catch (e) {
        console.error('Error parsing GeoJSON for zone:', zone.ID, e)
      }

      return {
        ...zone,
        polygonCoordinates
      }
    })
    .filter(zone => zone.polygonCoordinates.length > 0)
})

// Вычисляем центры зон для маркеров
const zonesWithCenters = computed<ZoneWithCenter[]>(() => {
  return zonesWithPolygons.value.map(zone => {
    // Вычисляем центр полигона
    let centerLat = 0
    let centerLon = 0
    let pointCount = 0

    zone.polygonCoordinates.forEach(ring => {
      ring.forEach(point => {
        if (point && point.length >= 2) {
          centerLon += point[0]
          centerLat += point[1]
          pointCount++
        }
      })
    })

    const centerCoordinates: [number, number] = pointCount > 0
      ? [centerLon / pointCount, centerLat / pointCount]
      : [37.617644, 55.755819] // Москва по умолчанию

    return {
      ...zone,
      centerCoordinates
    }
  })
})

// Следим за изменением активной зоны и центрируем карту
watch(() => props.activeZoneId, (newZoneId) => {
  if (newZoneId && map.value) {
    const zone = zonesWithCenters.value.find(z => z.ID === newZoneId)
    if (zone) {
      nextTick(() => {
        map.value?.setLocation({
          center: zone.centerCoordinates,
          zoom: 12
        })
      })
    }
  }
})
</script>

<style lang="scss" scoped>
.delivery-zones-map {
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

.zone-marker {
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

.zone-label {
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
