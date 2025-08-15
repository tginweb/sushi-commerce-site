import {CompanyVacancyGenqlSelection} from '@/gql/gen';

export const VacancyFields: CompanyVacancyGenqlSelection = {
  ID: true,
  NAME: true,
  PREVIEW_TEXT: true,
  DETAIL_TEXT: true,
  ACTIVE: true,
  PROPERTIES: {
    PAYMENT: true,
    RESPONSIBILITES: {
      __fragment: 'ElementPropValueHtml'
    },
    WORK_EXPERIENCE: true
  },
}; 