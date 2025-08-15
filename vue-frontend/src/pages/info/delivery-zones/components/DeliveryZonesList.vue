<template>

  <q-list separator class="c-list">

    <q-item v-for="zone in zones" :key="zone.ID" class="c-list__item" :class="{ 'active': zone.ID === activeZoneId }"
            @click="onZoneClick(zone.ID)">
      <q-item-section avatar>

      </q-item-section>

      <q-item-section>
        <q-item-label class="text-weight-medium">{{ zone.NAME }}</q-item-label>
      </q-item-section>

      <q-item-section side>
        <div class="zone-color" v-if="zone.PROPERTIES?.COLOR">
          <div class="color-indicator" :style="{ backgroundColor: zone.PROPERTIES.COLOR }"></div>
        </div>
      </q-item-section>

      <q-item-section side>
        <q-icon name="chevron_right" class="zone-arrow"/>
      </q-item-section>

    </q-item>
  </q-list>


</template>

<script setup lang="ts">
import {DeliveryZone} from '@/gql/gen'

interface Props {
  zones: DeliveryZone[]
  activeZoneId?: number | null | undefined
}

const props = defineProps<Props>()

const emit = defineEmits<{
  zoneSelect: [zoneId: number]
}>()

const onZoneClick = (zoneId: number) => {
  emit('zoneSelect', zoneId)
}
</script>

<style lang="scss" scoped>
.delivery-zones-list {
  height: 100%;
  display: flex;
  flex-direction: column;
  background: white;
}

.list-header {
  padding: 16px 20px;
  border-bottom: 1px solid #e0e0e0;
  background: #f8f9fa;

  @media (max-width: 599px) {
    padding: 12px 16px;
  }
}

.list-title {
  margin: 0 0 4px 0;
  font-size: 18px;
  font-weight: 600;
  color: #333;

  @media (max-width: 599px) {
    font-size: 16px;
  }
}

.list-count {
  font-size: 14px;
  color: #666;

  @media (max-width: 599px) {
    font-size: 12px;
  }
}

.zones-container {
  flex: 1;
  overflow-y: auto;
}

.zone-item {
  display: flex;
  align-items: center;
  padding: 16px 20px;
  border-bottom: 1px solid #f0f0f0;
  cursor: pointer;
  transition: all 0.2s ease;

  &:hover {
    background: #f8f9fa;
  }

  &.active {
    background: #e3f2fd;
    border-left: 4px solid #1976d2;
  }

  @media (max-width: 599px) {
    padding: 12px 16px;
  }
}

.zone-info {
  flex: 1;
  min-width: 0;
}

.zone-name {
  font-size: 16px;
  font-weight: 500;
  color: #333;
  margin-bottom: 4px;

  @media (max-width: 599px) {
    font-size: 14px;
  }
}

.zone-description {
  font-size: 14px;
  color: #666;
  margin-bottom: 4px;
  line-height: 1.4;

  @media (max-width: 599px) {
    font-size: 12px;
  }
}

.zone-delivery-time,
.zone-min-order {
  font-size: 12px;
  color: #888;
  margin-bottom: 2px;

  @media (max-width: 599px) {
    font-size: 11px;
  }
}

.zone-color {
  margin: 0 12px;

  @media (max-width: 599px) {
    margin: 0 8px;
  }
}

.color-indicator {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  border: 2px solid #e0e0e0;

  @media (max-width: 599px) {
    width: 12px;
    height: 12px;
  }
}

.zone-arrow {
  color: #ccc;
  transition: all 0.2s ease;

  &.active {
    color: #1976d2;
    transform: rotate(90deg);
  }

  @media (max-width: 599px) {
    font-size: 18px;
  }
}

.empty-state {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  color: #999;

  @media (max-width: 599px) {
    padding: 20px 16px;
  }
}

.empty-icon {
  margin-bottom: 16px;
  opacity: 0.5;

  @media (max-width: 599px) {
    font-size: 36px;
    margin-bottom: 12px;
  }
}

.empty-text {
  font-size: 16px;
  text-align: center;

  @media (max-width: 599px) {
    font-size: 14px;
  }
}

.c-list {
  flex: 1;
  overflow-y: auto;

  .q-item {
    //border-radius: 8px;
    // margin-bottom: 4px;
    transition: all 0.3s ease;

    &.active {
      background-color: rgba(25, 118, 210, 0.1);
      border-left: 4px solid $primary;
    }

    &:hover {
      background-color: rgba(0, 0, 0, 0.05);
    }
  }
}
</style>
