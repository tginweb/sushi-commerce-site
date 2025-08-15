<template>
  <div>
    <div class="s-font-lg q-mb-lg">
      Интересная работа в команде влюбленных в свое дело профессионалов. Участвуйте в новых проектах SushiStudio.
      Развивайтесь и стройте успешную карьеру в ресторанном бизнесе. Присоединяйтесь к нашей команде!
    </div>
    <div class="row q-col-gutter-lg q-col-gutter-lg-xl">
      <div v-for="entity of vacancies" :key="entity.ID" class="col-24 col-md-12 col-lg-8">
        <VacancyCard :entity="entity" />
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useVacancyStore } from '@/modules/company/store/vacancy'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'
import VacancyCard from './components/VacancyCard.vue'

const router = useRouter()
const vacancyStore = useVacancyStore()
const { vacancies } = storeToRefs(vacancyStore)

</script>
<style lang="scss" scoped></style>
