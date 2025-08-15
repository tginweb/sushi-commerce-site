<template>
  <StackItemModal
      v-bind="bind"
      :title="viewTitle"
      :actions="viewActions"
      :onHeaderBack="onBack"
      :dialog="{
        width: '1000px',
      }"
      @hide="onHide"
  >
    <template #content="bind" v-if="stackItem?.state.mode === 'dialog'">

      <div class="row q-col-gutter-md">
        <div class="col-11">
          <div class="column full-height">
            <DialogHeader v-bind="bind.header">
              <template #bottom>
                <div class="q-mt-md">
                  <ButtonsToggle
                      :model-value="viewTab"
                      @update:model-value="gotoView"
                      option-value="value"
                      option-label="label"
                      :props="{
                        class: 'q-px-md col-grow',
                        size: '15px',
                        unelevated: true,
                        dense: true
                      }"
                      :props-active="{
                        color: 'secondary'
                      }"
                      :props-inactive="{
                        outline: false,
                        color: 'grey-3',
                        textColor: 'black'
                      }"
                      :options="tabsOptions"
                      class="flex q-gutter-x-md justify-center no-wrap"
                  />
                </div>
              </template>
            </DialogHeader>
            <div class="col-grow">
              <DialogBody v-bind="bind.body">
                <div class="q-pb-md q-mt-xs">

                  <template v-if="viewTab === 'profile'">

                    <template v-if="viewMode === 'list'">

                      <div v-if="!profilesModels.length">
                        <div class="s-font-sm q-mt-lg  text-center">
                          У вас пока нет адресов
                        </div>
                      </div>
                      <div v-else>
                        <div class="s-font-sm q-mt-sm q-mb-lg">
                          Выберите ваш адрес:
                        </div>
                        <q-list class="q-gutter-y-md">
                          <ProfileItem
                              v-for="profile in profilesModels"
                              :key="profile.id || ''"
                              :profile-model="profile"
                              @select="onProfileSelect(profile)"
                              @edit="onProfileEdit(profile)"
                              @calc-delivery="onProfileCalcDelivery(profile)"
                              :selectedId="profileId"
                              :ref="el => profileList.setElementRef(el, profile.id)"
                          />
                        </q-list>
                      </div>

                      <q-btn
                          label="Добавить новый адрес"
                          color="grey-3"
                          unelevated
                          text-color="dark"
                          :icon="ICONS.plus"
                          class="full-width q-mt-lg"
                          @click="onProfileAdd"
                      />

                    </template>
                    <template v-else-if="viewMode === 'edit'">

                      <ProfileEdit
                          v-if="profileModel"
                          :profile-model="profileModel"
                          @address-changed="onInputAddressChanged"
                      />

                    </template>

                  </template>

                  <template v-if="viewTab === 'pickup'">

                    <div class="s-font-sm q-pb-sm">
                      Выберите подразделение самовывоза:
                    </div>
                    <q-list class="q-gutter-y-sm">
                      <OfficeItem
                          v-for="office in saleOffices"
                          :key="office.ID || ''"
                          :office="office"
                          @select="onOfficeSelect(office)"
                          :selectedId="officeId"
                          :ref="el => officeList.setElementRef(el, office.ID)"
                      />
                    </q-list>

                  </template>

                </div>
                <template v-if="profileModel">

                </template>

                <JsonViewer :value="profileModel" v-if="false"/>

              </DialogBody>
            </div>
            <DialogFooter v-bind="bind.footer"/>
          </div>
        </div>
        <div class="col-13">
          <Map
              ref="mapRef"
              :profileModel="profileModel"
              :office="office"
              :viewName="viewName"
              :maxHeight="bind.body?.maxHeight"
              @geocoder-resolved="onMapAddressChanged"
              @office-select="onOfficeSelect"
              @profile-select="onProfileSelect"
          />
        </div>
      </div>
    </template>
    <template v-if="stackItem?.state.mode === 'sheet'">

      <q-list class="q-gutter-y-sm">
        <ProfileItem
            v-for="profile in profilesModels"
            :key="profile.id || ''"
            :profile-model="profile"
            @select="onProfileSelect(profile)"
            @edit="onProfileEdit(profile)"
            :selectedId="profileId"
        />
      </q-list>

    </template>
  </StackItemModal>
</template>
<script setup lang="ts">
//region IMPORTS
import ProfileItem from "./ProfileItem.vue";
import ProfileEdit from "./ProfileEdit.vue";
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {type ComponentPublicInstance, computed, ref, toRaw, toRefs, watch} from "vue";
import ButtonsToggle from "@/components/ButtonsToggle.vue";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {CompanyOffice, OrderProfile, OrderProfileAttributesValue} from "@/gql/gen";
import {Maybe} from "@/core/types";
//@ts-ignore
import {cloneDeep} from "lodash-es";
import {OrderProfileModel, useProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";
import Map, {DeliverySelectMapPublicApi, GeocoderResult} from "@/pages/personal/order-profiles/Map.vue";
import {useQuasar} from "quasar";
import {ICONS} from "@/assets/icons";
import {DaDataAddress} from "@/modules/geo/types/dadata";
import {GeoCoordinates} from "@/modules/geo/class/GeoCoordinates";
import {DeliverySelectContextKey, DeliverySelectViewModeMap} from "@/pages/personal/order-profiles/util";
import OfficeItem from "@/pages/personal/order-profiles/OfficeItem.vue";
import {createStackCloseAction} from "@/components/StackRouter/util";
import {TabbedStructure, useTabbedViews} from "@/core/hooks/useTabbedViews";
import {useList} from "@/core/hooks/useList";
import {useOrderProfileStore} from "@/modules/shop/store/order-profile";
import {useDeliveryCalculateStore} from "@/modules/shop/store/delivery-calculate";
import {useShopStore} from "@/modules/shop/store/shop";
//endregion

//region TYPES
type ProfileTarget = OrderProfileModel | number
type OfficeTarget = CompanyOffice | number
//endregion


//region STORES & SERVICES
const $q = useQuasar()
const vorderStore = useVorderStore()
const shopStore = useShopStore()
const orderProfileStore = useOrderProfileStore()
const deliveryCalculateStore = useDeliveryCalculateStore()

const {
  profileDeliveryCalculate,
  profileDeliveryCalculateRequest,
} = deliveryCalculateStore

const {
  profileSave,
  profileDelete,
  profileSaveRequest,
} = orderProfileStore

const {
  profilesModels,
  profilesModelsById,
  profileById,
} = storeToRefs(orderProfileStore)

const {
  saleOffices
} = storeToRefs(shopStore)

const {applyDeliveryProfile} = vorderStore
const {
  deliveries,
  deliveryById,
} = storeToRefs(vorderStore)
//endregion

//region PROPS
const props = withDefaults(defineProps<StackComponentProps & {
  role?: 'vorder' | 'personal';
  profileId?: number;
}>(), {})
const {role} = toRefs(props)
//endregion

//region MODAL
const {bind, stackItem} = useStackComponent<StackItemModalState>(props)

//endregion

//region STATE & COMPUTED
const officeId = ref(0)
const profileId = ref(0)
const mapRef = ref<ComponentPublicInstance<typeof Map> & DeliverySelectMapPublicApi | null>(null)
const editingProfile = ref<OrderProfile | null>()
const changedProfileIds = ref<Record<string, number>>({})

const views: TabbedStructure<DeliverySelectViewModeMap> = {
  profile: {
    title: 'Доставка курьером',
    views: {
      list: {
        actions: computed(() => [
          !!profileId.value && createStackCloseAction('Готово', true, stackItem?.close)
        ]),
      },
      edit: {
        title: 'Редактирование адреса',
        backView: 'list',
        backCallback() {

        },
        actions: computed(() => [
          {
            label: 'Сохранить',
            onClick: onProfileSave,
            loading: profileSaveRequest.loading,
            width: !editingProfile.value?.ID ? 'col-24' : 'col-19'
          },
          !!editingProfile.value?.ID && {
            icon: ICONS.trush,
            outline: true,
            color: 'red',
            onClick: onProfileDelete,
            loading: profileSaveRequest.loading,
            width: 'col-5'
          },
        ])
      },
    }
  },
  pickup: {
    title: 'Самовывоз',
    actions: computed(() => [
      !!officeId.value && createStackCloseAction('Готово', true, stackItem?.close)
    ]),
    views: {
      list: {},
    }
  }
}

const {tabs, viewTab, viewMode, viewName, viewTitle, viewActions, onBack, gotoView} = useTabbedViews({
  contextKey: DeliverySelectContextKey,
  viewTabInitial: 'profile',
  viewModeInitial: 'list',
  tabs: views
})

const tabsOptions = computed(() => {
  return Object.entries(tabs.value)
      .map(([key, tab]) => ({
        value: key,
        label: tab.title,
        icon: tab.icon,
        order: tab.order || 0
      }))
      .sort((a, b) => a.order - b.order);
});

const profileModel = computed(() => {
  if (viewName.value === 'profile.edit') {
    return editingProfile.value ? useProfileModel(editingProfile.value) : null
  } else if (profileId.value) {
    return profilesModelsById.value[profileId.value]
  }
})

const office = computed(() => resolveOffice(officeId.value))

const officeList = useList({
  elementId: officeId
})

const profileList = useList({
  elementId: profileId
})

//endregion

//region METHODS


const resolveProfileModel = (target: ProfileTarget) => {
  let foundProfile: Maybe<OrderProfileModel> = null
  if (typeof target === 'number') {
    foundProfile = profilesModelsById.value[target]
  } else {
    foundProfile = target
  }
  return foundProfile
}

function resolveOffice(target: OfficeTarget) {
  return typeof target === 'number' ? saleOffices.value.find(item => item.ID === target) : target
}

//endregion

//region EVENTS
function onOfficeSelect(target: CompanyOffice) {
  let model = resolveOffice(target)
  if (model) {
    officeId.value = model.ID
  }
}

function onProfileSelect(target: ProfileTarget) {
  let model = resolveProfileModel(target)
  if (model) {
    profileId.value = model.id || 0
    mapRef.value?.navTo({type: 'profile', model: model})
    if (viewMode.value === 'edit') {
      onProfileEdit(model)
    } else {
      gotoView('profile', 'list')
    }
  }
}

function onProfileEdit(target: ProfileTarget) {
  let model = resolveProfileModel(target)
  if (model) {
    const clonedProfile = cloneDeep(toRaw(model.source))
    profileId.value = clonedProfile.ID || 0
    editingProfile.value = clonedProfile
    gotoView('profile', 'edit')
    mapRef.value?.navTo({type: 'profile', model})
  }
}

async function onProfileCalcDelivery(target: ProfileTarget) {
  let model = resolveProfileModel(target)
  if (model) {
    model.deliveryCalculateNearest?.mutate({})
  }
}

function onProfileAdd() {
  editingProfile.value = {
    ID: 0,
    ATTR: {},
    ATTRS: []
  } as unknown as OrderProfile
  profileId.value = 0
  gotoView('profile', 'edit')
}

function onProfileDelete() {
  $q.dialog({
    title: 'Подтверждение',
    message: 'Вы действительно хотите удалить профиль?',
    cancel: 'Отмена',
  }).onOk(() => {
    if (profileId.value)
      profileDelete(profileId.value)
  })
}

async function onProfileSave() {
  const model = profileModel.value
  if (model) {
    const profile = model.source
    model.beforeSave()
    const savedProfile = await profileSave(profile, false)
    if (savedProfile) {
      profileId.value = savedProfile.ID
      changedProfileIds.value[profile.ID] = profile.ID
      gotoView('profile', 'list')
    }
  }
}

function onMapAddressChanged(targetProfile: OrderProfileModel, geocoderResult: GeocoderResult) {

  const editingModel = profileModel.value
  if (editingModel && editingModel.id === targetProfile.id) {
    const data = geocoderResult.data
    const coords = geocoderResult.coords.getPositionObject(false, true)

    const attrs = {
      ADDRESS_SOURCE: 'map',
      ADDRESS: geocoderResult.address_short,
      CITY: data.city_format,
      CITY_FIAS_ID: data.city_fias_id,
      STREET_PATH: data.street_path_short,
      STREET: data.street_format,
      STREET_FIAS_ID: data.street_fias_id,
      STREET_COORDS: coords,
      HOUSE: data.house,
      HOUSE_FIAS_ID: data.house_fias_id,
      HOUSE_COORDS: coords,
    } as OrderProfileAttributesValue

    editingModel.setAttrs(attrs)
  }
}

function onInputAddressChanged(targetProfile: OrderProfileModel, address: string, data: DaDataAddress) {

  const editingModel = profileModel.value

  if (editingModel && editingModel.id === targetProfile.id) {

    const attrs = {} as OrderProfileAttributesValue

    attrs['ADDRESS_SOURCE'] = 'dadata'

    const coords = data.geo_lon && data.geo_lat ? new GeoCoordinates({
      LAT: parseFloat(data.geo_lat),
      LON: parseFloat(data.geo_lon)
    }) : null

    const coordsValue = coords ? coords.getPositionObject(false, true) : null

    attrs['ADDRESS'] = address

    if (data.city) {
      attrs['CITY'] = data.city
      attrs['CITY_FIAS_ID'] = data.city_fias_id
    } else if (data.settlement) {
      attrs['CITY'] = data.settlement
      attrs['CITY_FIAS_ID'] = data.settlement_fias_id
    } else {
      attrs['CITY'] = null
      attrs['CITY_FIAS_ID'] = null
    }

    if (data.street) {
      attrs['STREET'] = data.street
      attrs['STREET_FIAS_ID'] = data.street_fias_id
      attrs['STREET_COORDS'] = coordsValue
    } else {
      attrs['STREET'] = null
      attrs['STREET_FIAS_ID'] = null
      attrs['STREET_COORDS'] = null
    }

    if (data.house) {
      attrs['HOUSE'] = data.house
      attrs['HOUSE_FIAS_ID'] = data.house_fias_id
      attrs['HOUSE_COORDS'] = coordsValue
    } else {
      attrs['HOUSE'] = null
      attrs['HOUSE_FIAS_ID'] = null
      attrs['HOUSE_COORDS'] = null
    }

    editingModel.setAttrs(attrs)

    mapRef.value?.navTo({type: 'profile', model: editingModel})
  }
}

//endregion


//region WATCHERS
watch(viewTab, () => {
  if (viewTab.value === 'pickup' && officeId.value) {
    officeList.scrollTo(officeId.value)
  } else if (viewTab.value === 'profile' && profileId.value) {
    profileList.scrollTo(officeId.value)
  }
})
//endregion

//region BOOT
if (profilesModels.value[0]) {
  // onProfileSelect(profilesModels.value[0])
}
//endregion

const onHide = () => {

  const _profileId = profileId.value

  let changed = false

  if (_profileId) {
    if (changedProfileIds.value[_profileId]) {
      changed = true
    }
  } else {
    if (Object.values(changedProfileIds).length) {
      changed = true
    }
  }

  //applyDeliveryProfile(profileModel.value, changed)
}

</script>
<style>

</style>
