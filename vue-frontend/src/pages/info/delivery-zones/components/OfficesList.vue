<template>
  <div class="offices-list">
    <q-list separator class="offices-list-content">
      <q-item
        v-for="office in offices"
        :key="office.ID"
        :class="{ 'active': office.ID === activeOfficeId }"
        clickable
        @click="onOfficeClick(office.ID)"
      >
        <q-item-section avatar>
          <q-avatar :color="office.ID === activeOfficeId ? 'primary' : 'grey-3'" text-color="white">
            <q-icon :name="getOfficeIcon(office)" />
          </q-avatar>
        </q-item-section>

        <q-item-section>
          <q-item-label class="text-weight-medium">{{ office.NAME }}</q-item-label>
          <q-item-label caption>
            <q-icon name="place" size="14px" class="q-mr-xs" />
            {{ getOfficeAddress(office) }}
          </q-item-label>
          <q-item-label caption v-if="getOfficePhone(office)">
            <q-icon name="phone" size="14px" class="q-mr-xs" />
            {{ getOfficePhone(office) }}
          </q-item-label>
          <q-item-label caption v-if="getOfficeHours(office)">
            <q-icon name="schedule" size="14px" class="q-mr-xs" />
            {{ getOfficeHours(office) }}
          </q-item-label>
        </q-item-section>

        <q-item-section side>
          <q-chip
            :color="getOfficeRoleColor(office)"
            text-color="white"
            size="sm"
            :label="getOfficeRoleLabel(office)"
          />
        </q-item-section>
      </q-item>
    </q-list>

    <!-- Детальная информация об активном офисе -->
    <q-card v-if="activeOffice" class="q-mt-md office-details">
      <q-card-section>
        <div class="text-h6">{{ activeOffice.NAME }}</div>
        <div class="text-subtitle2 q-mt-sm">
          <q-icon name="place" size="16px" class="q-mr-xs" />
          {{ getOfficeAddress(activeOffice) }}
        </div>
        <div v-if="getOfficePhone(activeOffice)" class="text-body2 q-mt-sm">
          <q-icon name="phone" size="16px" class="q-mr-xs" />
          <a :href="`tel:${getOfficePhone(activeOffice)}`" class="text-primary">
            {{ getOfficePhone(activeOffice) }}
          </a>
        </div>
        <div v-if="getOfficeHours(activeOffice)" class="text-body2 q-mt-sm">
          <q-icon name="schedule" size="16px" class="q-mr-xs" />
          {{ getOfficeHours(activeOffice) }}
        </div>
        <div v-if="getOfficeDescription(activeOffice)" class="text-body2 q-mt-md">
          {{ getOfficeDescription(activeOffice) }}
        </div>
      </q-card-section>
    </q-card>

    <!-- Пустое состояние -->
    <div v-if="!offices.length" class="text-center q-pa-lg text-grey-6">
      <q-icon name="business" size="48px" class="q-mb-md" />
      <div>Офисы не найдены</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { CompanyOffice } from '@/gql/gen'
import { ICONS } from '@/assets/icons'

interface Props {
  offices: CompanyOffice[]
  activeOfficeId?: number | null | number
}

const props = defineProps<Props>()

const emit = defineEmits<{
  officeSelect: [officeId: number]
}>()

const onOfficeClick = (officeId: number) => {
  emit('officeSelect', officeId)
}

// Получаем активный офис
const activeOffice = computed(() => {
  return props.offices.find(office => office.ID === props.activeOfficeId) || null
})

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

// Получаем цвет роли офиса
const getOfficeRoleColor = (office: CompanyOffice) => {
  const roles = office.PROPERTIES?.ROLES || []
  if (roles.some(role => role?.VALUE === 'delivery')) {
    return 'blue'
  }
  if (roles.some(role => role?.VALUE === 'bar')) {
    return 'orange'
  }
  return 'grey'
}

// Получаем лейбл роли офиса
const getOfficeRoleLabel = (office: CompanyOffice) => {
  const roles = office.PROPERTIES?.ROLES || []
  if (roles.some(role => role?.VALUE === 'delivery')) {
    return 'Доставка'
  }
  if (roles.some(role => role?.VALUE === 'bar')) {
    return 'Суши-бар'
  }
  return 'Офис'
}

// Получаем адрес офиса
const getOfficeAddress = (office: CompanyOffice) => {
  return office.PROPERTIES?.ADDRESS?.VALUE || 'Адрес не указан'
}

// Получаем телефон офиса
const getOfficePhone = (office: CompanyOffice) => {
  return office.PROPERTIES?.PHONE?.VALUE || null
}

// Получаем часы работы офиса
const getOfficeHours = (office: CompanyOffice) => {
  return office.PROPERTIES?.WORK_HOURS?.VALUE || null
}

// Получаем описание офиса
const getOfficeDescription = (office: CompanyOffice) => {
  return office.PROPERTIES?.DESCRIPTION?.VALUE || null
}
</script>

<style lang="scss" scoped>
.offices-list {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.offices-list-content {
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

.office-details {
  margin: 8px;
  border-radius: 8px;

  @media (max-width: 599px) {
    margin: 4px;
  }
}
</style>
