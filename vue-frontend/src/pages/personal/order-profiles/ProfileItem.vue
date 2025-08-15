<template>
  <q-item
      v-ripple
      class="c-item q-px-none q-px-xs"
      manual-focus
      tag="label"
      @click="emit('select', profile)"
      :class="{
        '--selected': selected
      }"
  >
    <q-item-section class="col-auto">
      <q-radio
          :model-value="selectedId"
          @update:model-value="emit('select', profile)"
          :val="profile.ID"
          color="primary"
          size="xs"
      />
    </q-item-section>
    <q-item-section>
      <q-item-label class="text-dark">
        {{ profileModel.name }}
      </q-item-label>
      <q-item-label caption>
        <div v-if="false">
          {{ profileModel.caption }}
        </div>
        <div class="q-mt-xs" v-if="profileModel.deliveryTimeMinutes.value">
          <span class="text-grey-9">Время доставки</span> ~ <b>{{ profileModel.deliveryTimeMinutes }} мин</b>
        </div>
      </q-item-label>
    </q-item-section>
    <q-item-section top class="q-ml-auto col-auto" v-if="profile.ID === selectedId">
      <q-item-label class="text-right flex no-wrap">
        <q-btn
            :icon="ICONS.delivery"
            dense
            flat
            no-wrap
            size="md"
            color="primary"
            @click.stop.prevent="emit('calc-delivery', profile)"
        />
        <q-btn
            :icon="ICONS.pencil"
            dense
            flat
            no-wrap
            size="md"
            color="primary"
            @click.stop.prevent="emit('edit', profile)"
        />
      </q-item-label>
    </q-item-section>
  </q-item>
</template>
<script setup lang="ts">


import {computed, toRefs} from "vue";
import {ICONS} from "@/assets/icons";
import {OrderProfile} from "@/gql/gen";
import {OrderProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";

export type ProfileItemProps = {
  profileModel: OrderProfileModel
  selectedId?: number | null | undefined
}

const props = withDefaults(defineProps<ProfileItemProps>(), {})

const {profileModel, selectedId} = toRefs(props)
const profile = computed(() => profileModel.value.source)
const attr = computed(() => profile.value.ATTR)

const emit = defineEmits<{
  select: [OrderProfile],
  edit: [OrderProfile],
  'calc-delivery': [OrderProfile],
}>()

const selected = computed(() => selectedId.value === profileModel.value.id)

</script>
<style lang="scss" scoped>

.c-item {
  border: 1px solid #DDDDDD;
  border-radius: 12px;
}

.--selected {
  border: 2px solid $primary;
}

</style>
