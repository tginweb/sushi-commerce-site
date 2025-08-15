<template>
  <div>
    <div class="s-font-lg q-mb-lg">
      Интересная работа в команде влюбленных в свое дело профессионалов. Участвуйте в новых проектах SushiStudio.
      Развивайтесь и стройте успешную карьеру в ресторанном бизнесе. Присоединяйтесь к нашей команде!
    </div>
    <div v-if="isPending" class="q-my-xl flex flex-center">
      <q-spinner size="40px" color="primary" />
    </div>
    <div v-else-if="error" class="q-my-xl text-negative">
      Произошла ошибка при загрузке вакансий
    </div>
    <div v-else class="row q-col-gutter-lg q-col-gutter-lg-xl">
      <div v-for="entity of vacancies" :key="entity.ID" class="col-24 col-md-12 col-lg-8">
        <VacancyCard :entity="entity" />
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, computed } from 'vue'
import VacancyCard from './components/VacancyCard.vue'
import { companyVacancyRecordsetQuery } from '@/gql/gen/query/companyVacancyRecordsetQuery'
import { VacancyFields } from '@/gql/fragments/VacancyFields'
import { useTask } from '@/core/hooks/useTask'

// SSR-загрузка вакансий через recordset
const { result, isPending, error } = useTask(async () => {
  const res = await companyVacancyRecordsetQuery({
    nodes: VacancyFields,
    info: { __fragment: 'QueryInfo' },
  }, { nav: { page: 1, limit: 30 } })
  return res?.company_vacancy_recordset || { nodes: [], info: {} }
})

const vacancies = computed(() => result.value?.nodes || [])
</script>
<style lang="scss" scoped></style>
