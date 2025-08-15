<template>
  <router-link event="" :to="`/vacancy/${entity.ID}`" class="c-entity q-px-md q-py-md full-height flex column no-wrap">

    <h2 class="q-mb-md q-mt-none q-mb-md s-font-3xl text-weight-bold">
      {{ entity.NAME }}
    </h2>

    <div class="q-mb-md">
      <div v-if="entity.PROPERTIES.PAYMENT" class="text-weight-bold">
        {{ entity.PROPERTIES.PAYMENT }}
      </div>
      <div v-if="entity.PROPERTIES.WORK_EXPERIENCE">
        опыт работы {{ entity.PROPERTIES.WORK_EXPERIENCE }}
      </div>
    </div>

    <div class="c-resp s-font-sm" v-if="entity.PROPERTIES.RESPONSIBILITES?.TEXT">
      <div v-html="entity.PROPERTIES.RESPONSIBILITES?.TEXT"/>
    </div>

    <div class="q-mt-auto">
      <q-btn label="откликнуться" color="primary" outline />
    </div>

  </router-link>
</template>
<script setup lang="ts">
import { CompanyVacancy } from '@/gql/gen/schema';
import { toRefs } from 'vue';

const props = withDefaults(defineProps<{
  entity: CompanyVacancy
}>(), {})

const { entity } = toRefs(props)

</script>
<style scoped>
.c-entity {
  border: 1px solid #ddd;
  border-radius: 10px;
}

.c-resp {
  :deep() {
    ul {
      padding: 0 0 16px 14px;
    }
  }
}
</style>