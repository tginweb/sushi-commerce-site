<template>
  <div>
    <q-form class="q-pr-md">
      <div class="row q-col-gutter-md q-col-gutter-xl-md q-pt-sm q-pb-sm">

        <div class="col-24">
          <UiInputAddress
              label="Введите адрес до дома"
              v-model="attr.ADDRESS"
              outlined
              emit-value
              @address-select="onAddressChanged"
              :address-fields="profileModel.textInputFields.value"
          />
        </div>

        <template v-if="!attr.PRIVATE_HOUSE">

          <div class="col-12 col-sm-9 col-lg-9">
            <UiInput
                :lazy-rules="true"
                v-model="attr.FLAT"
                label="Квартира"
                outlined
            />
          </div>

          <div class="col-12 col-sm-7 col-lg-6">
            <UiInput
                v-model="attr.FLOOR"
                outlined
                label="Этаж"
            />
          </div>

          <div class="col-12 col-md-10 col-lg-13">
            <q-toggle
                v-model="attr.PRIVATE_HOUSE"
                outlined
                label="Чаcтный дом"
            />
          </div>

          <div class="col-12 col-md-10 col-lg-9">
            <q-toggle
                v-model="attr.LIFT"
                outlined
                label="Лифт"
            />
          </div>

        </template>
        <template v-else>

          <div class="col-12 col-md-10 col-lg-13">
            <q-toggle
                v-model="attr.PRIVATE_HOUSE"
                label="Частный дом"
            />
          </div>

        </template>

        <div class="col-24">
          <UiInput
              v-model="attr.PROFILE_COMMENT"
              label="Комментарий к адресу"
              type="textarea"
              outlined
              autogrow
          />
        </div>

      </div>
    </q-form>
  </div>
</template>
<script setup lang="ts">

import {computed, toRefs} from "vue";
import {OrderProfile} from "@/gql/gen";
import UiInputAddress from "@/components/Form/UiInputAddress.vue";
import UiInput from "@/components/Form/UiInput.vue";
import {DaDataAddress} from "@/modules/geo/types/dadata";
import {OrderProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";

const emit = defineEmits<{
  select: [OrderProfile],
  edit: [OrderProfile],
  'address-changed': [
    profileModel: OrderProfileModel,
    address: string,
    data: DaDataAddress,
  ]
}>()

const props = withDefaults(defineProps<{
  profileModel: OrderProfileModel
}>(), {})

const {profileModel} = toRefs(props)

const profile = computed(() => profileModel.value.source)
const attr = computed(() => profile.value.ATTR)

const onAddressChanged = (address: string, dadata: DaDataAddress) => {
  console.log('onAddressChanged', {
    address,
    dadata
  })
  emit('address-changed', profileModel.value, address, dadata)
}


</script>
<style>

</style>
