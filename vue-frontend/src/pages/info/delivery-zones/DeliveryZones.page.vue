<template>

  <q-page class="">

    <!-- Контент -->
    <div class="c-content">

      <!-- Табы для переключения типов -->
      <div class="tabs-container">
        <q-tabs v-model="activeTab" class="text-grey-8" active-color="primary" indicator-color="primary" align="justify"
                narrow-indicator>
          <q-tab name="zones" label="Зоны доставки"/>
          <q-tab name="offices" label="Офисы доставки"/>
          <q-tab name="bars" label="Суши-бары"/>
        </q-tabs>
      </div>

      <div v-if="$q.screen.gt.md" class="desktop-layout">

        <div class="row full-height">
          <div class="col-8 list-container full-height">
            <DeliveryZonesList v-if="activeTab === 'zones'" :zones="deliveryZones" :active-zone-id="activeZoneId"
                               @zone-select="setActiveZone"/>
            <OfficesList v-else :offices="activeOffices" :active-office-id="activeOfficeId"
                         @office-select="setActiveOffice"/>
          </div>
          <div class="col-16 map-container full-height">
            <DeliveryZonesMap v-if="activeTab === 'zones'" :zones="deliveryZones" :active-zone-id="activeZoneId"
                              @zone-select="setActiveZone"/>
            <OfficesMap v-else :offices="activeOffices" :active-office-id="activeOfficeId"
                        @office-select="setActiveOffice"/>
          </div>
        </div>

      </div>

      <div v-if="$q.screen.lt.lg" class="mobile-layout">
        <!-- Список сверху -->
        <div class="mobile-list-container">
          <DeliveryZonesList v-if="activeTab === 'zones'" :zones="deliveryZones" :active-zone-id="activeZoneId"
                             @zone-select="setActiveZone"/>
          <OfficesList v-else :offices="activeOffices" :active-office-id="activeOfficeId"
                       @office-select="setActiveOffice"/>
        </div>

        <!-- Карта снизу -->
        <div class="mobile-map-container">
          <DeliveryZonesMap v-if="activeTab === 'zones'" :zones="deliveryZones" :active-zone-id="activeZoneId"
                            @zone-select="setActiveZone"/>
          <OfficesMap v-else :offices="activeOffices" :active-office-id="activeOfficeId"
                      @office-select="setActiveOffice"/>
        </div>
      </div>

    </div>

  </q-page>

</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue'
import {useCompanyStore} from '@/modules/company/store/company'
import DeliveryZonesMap from './components/DeliveryZonesMap.vue'
import DeliveryZonesList from './components/DeliveryZonesList.vue'
import OfficesMap from './components/OfficesMap.vue'
import OfficesList from './components/OfficesList.vue'
import {storeToRefs} from 'pinia'
import {useShopStore} from "@/modules/shop/store/shop";

const shopStore = useShopStore()
const companyStore = useCompanyStore()

// Состояние
const activeTab = ref<'zones' | 'offices' | 'bars'>('zones')
const activeZoneId = ref<number | null | undefined>(null)
const activeOfficeId = ref<number | null | undefined>(null)

const {offices} = storeToRefs(companyStore)
const {deliveryZones} = storeToRefs(shopStore)


// Фильтруем офисы по ролям
const deliveryOffices = computed(() => {
  return offices.value.filter(office =>
      office.PROPERTIES?.ROLES?.some(role => role?.CODE === 'delivery')
  )
})

const sushiBars = computed(() => {
  return offices.value.filter(office =>
      office.PROPERTIES?.ROLES?.some(role => role?.CODE === 'bar')
  )
})

// Активные офисы в зависимости от таба
const activeOffices = computed(() => {
  return activeTab.value === 'offices' ? deliveryOffices.value : sushiBars.value
})

// Методы
const setActiveZone = (zoneId: number) => {
  activeZoneId.value = zoneId
}

const setActiveOffice = (officeId: number) => {
  activeOfficeId.value = officeId
}

// Инициализация
onMounted(() => {
  // Устанавливаем первую зону как активную
  if (deliveryZones.value && deliveryZones.value.length > 0) {
    activeZoneId.value = deliveryZones.value[0]?.ID
  }

  // Устанавливаем первый офис как активный
  if (activeOffices.value && activeOffices.value.length > 0) {
    activeOfficeId.value = activeOffices.value[0]?.ID
  }
})
</script>

<style lang="scss" scoped>
.tabs-container {
  background: white;
  border-bottom: 1px solid #e0e0e0;
  z-index: 10;
}

.content-container {
  flex: 1;

}

// Десктопная версия
.desktop-layout {
  height: 600px;

  .map-container {
    flex: 1;
    height: 100%;
  }
}

// Мобильная версия
.mobile-layout {
  display: flex;
  flex-direction: column;
  height: 100%;

  .mobile-list-container {
    height: 50vh;
    background: white;
    overflow-y: auto;
    border-bottom: 1px solid #e0e0e0;
  }

  .mobile-map-container {
    height: 50vh;
    flex: 1;
  }
}

// Адаптивные стили для табов
:deep(.q-tabs) {
  @media (max-width: 599px) {
    .q-tab {
      padding: 8px 12px;
      font-size: 14px;
    }
  }
}

.c-content {
  border: 1px solid #DDDDDD;
  border-radius: 10px;
  overflow: hidden;
}
</style>
