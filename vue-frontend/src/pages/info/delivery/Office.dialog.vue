<template>
  <StackItemModal
      :actionClose="true"
      v-bind="bind"
      :title="office?.NAME"
      :dialog="{
        headerHide: true,
        closePosition: 'outside-right'
      }"
  >
    <div v-if="office" class="q-mt-sm">
      <q-list bordered class="rounded-borders">

        <q-item>
          <q-item-section avatar>
            <q-icon :name="ICONS.fasMapMarkerAlt"/>
          </q-item-section>

          <q-item-section class="">
            {{ office.NAME }}
          </q-item-section>

        </q-item>
        <q-separator inset="item"/>

        <template v-if="office.PROPERTIES.PHONES.length">
          <q-item clickable v-ripple @click="onPhoneClick()">
            <q-item-section avatar>
              <q-icon name="phone"/>
            </q-item-section>

            <q-item-section>
              <q-item-label>Телефон</q-item-label>
            </q-item-section>

            <q-item-section side top class="q-pt-xs">
              <div v-for="phone in office.PROPERTIES.PHONES.filter(Boolean)" :key="phone as string">
                <a
                    :href="'tel:' + normalizePhone(phone, 'to-call')"
                    @click.stop.prevent="onPhoneClick(phone)"
                >{{ phone }}</a>
              </div>
            </q-item-section>
          </q-item>
          <q-separator inset="item"/>
        </template>

        <q-item>
          <q-item-section avatar>
            <q-icon :name="ICONS.clock"/>
          </q-item-section>

          <q-item-section>
            <q-item-label>Время работы</q-item-label>
          </q-item-section>

          <q-item-section side top>
            {{ office.PROPERTIES.WORKTIME }}
          </q-item-section>
        </q-item>
        <q-separator inset="item"/>

        <q-item>
          <q-item-section avatar>

          </q-item-section>

          <q-item-section class="">
            <div class="flex q-gutter-md">
              <div
                  v-for="role in office.PROPERTIES.ROLES"
                  :key="role.VALUE"
                  class="text-weight-medium"
              >
                <q-icon
                    v-if="role.CODE === 'pickup'"
                    :name="ICONS.pickup"
                    color="primary"
                    size="24px"
                />
                <q-icon
                    v-if="role.CODE === 'delivery'"
                    :name="ICONS.delivery"
                    color="primary"
                    size="20px"
                />
                {{ role.VALUE }}
              </div>
            </div>
          </q-item-section>

        </q-item>
        <q-separator inset="item"/>

      </q-list>

    </div>
  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {computed, toRefs} from "vue";
import {useCompanyStore} from "@/modules/company/store/company";
import {ICONS} from "@/assets/icons";
import normalizePhone from "@/core/util/format/normalizePhone";

const props = withDefaults(defineProps<StackComponentProps & {
  id: string
}>(), {})

const {id} = toRefs(props)

const companyStore = useCompanyStore()
const {officesById} = toRefs(companyStore)

const {bind} = useStackComponent<StackItemModalState>(props)

const office = computed(() => officesById.value[id.value])

const onPhoneClick = (phone?: string | null) => {
  const targetPhone = normalizePhone(phone || office.value?.PROPERTIES.PHONES[0])
  if (targetPhone) {
    window.location.replace('tel:' + targetPhone)
  }
}

</script>
<style>

</style>
