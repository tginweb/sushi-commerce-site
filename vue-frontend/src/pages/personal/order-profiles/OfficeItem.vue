<template>
  <q-item
      v-ripple
      class="c-item q-px-none q-px-xs"
      manual-focus
      tag="label"
      @click="emit('select', office)"
      :class="{
        '--selected': selected
      }"
  >
    <q-item-section class="col-auto">
      <q-radio
          :model-value="selectedId"
          @update:model-value="emit('select', office)"
          :val="office.ID"
          color="primary"
          size="xs"
      />
    </q-item-section>
    <q-item-section>
      <q-item-label class="text-dark">
        {{ office.NAME }}
      </q-item-label>
      <q-item-label caption>
        <div>
          {{ attr.WORKTIME }}
        </div>
      </q-item-label>
    </q-item-section>
    <q-item-section side>
      <q-btn
          flat
          dense
          icon="info"
          v-if="selected"
          :to="'/office/'+office.ID"
          color="primary"
      />
    </q-item-section>
  </q-item>
</template>
<script setup lang="ts">

import {computed, toRefs} from "vue";
import {CompanyOffice} from "@/gql/gen";

const emit = defineEmits<{
  'select': [office: CompanyOffice],
}>()

type OfficeItemProps = {
  office: CompanyOffice
  selectedId?: number | null | undefined
}

const props = withDefaults(defineProps<OfficeItemProps>(), {})

const {office, selectedId} = toRefs(props)

const attr = computed(() => office.value.PROPERTIES)
const selected = computed(() => selectedId.value === office.value.ID)

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
