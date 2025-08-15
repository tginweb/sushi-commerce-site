import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { CompanyVacancy } from '@/gql/gen/schema';
import { useScopeQuery } from '@/core/store/scopeQuery';

const STORE_NAME = 'vacancy';

export const useVacancyStore = defineStore(STORE_NAME, () => {
  const { registerScopeQuery } = useScopeQuery();

  const vacancies = ref<CompanyVacancy[]>([]);
  const vacanciesById = computed(() => vacancies.value.reduce<Record<number, CompanyVacancy>>((map, item) => (map[item.ID] = item, map), {}));

  registerScopeQuery(STORE_NAME, 'app', {
    company_vacancy_list: { __fragment: 'VacancyFields' },
  }, (data) => {
    vacancies.value = data.company_vacancy_list || [];
  });

  const getVacancyById = (id: number | string) => {
    return vacanciesById.value[Number(id)];
  };

  return {
    vacancies,
    vacanciesById,
    getVacancyById,
  }
});
