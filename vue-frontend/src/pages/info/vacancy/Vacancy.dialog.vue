<template>
  <StackItemModal :title="title" :actions="actions" v-bind="bind">

    <q-form ref="formRef" class="c-entity q-pb-lg" v-if="entity">

      <div class="q-mb-sm">
        <div v-if="entity.PROPERTIES.PAYMENT" class="text-weight-bold">
          {{ entity.PROPERTIES.PAYMENT }}
        </div>
        <div v-if="entity.PROPERTIES.WORK_EXPERIENCE">
          опыт работы {{ entity.PROPERTIES.WORK_EXPERIENCE }}
        </div>
      </div>

      <div v-if="entity.PROPERTIES.RESPONSIBILITES" class="c-resp s-font-sm">
        <div v-html="entity.PROPERTIES.RESPONSIBILITES.TEXT">
        </div>
      </div>

      <div class="q-mt-md">

        <div class="row q-col-gutter-md">

          <q-input v-model="form.fio" :rules="createRules('required')" class="col-24 col-sm-24" label="Ваше имя"
                   outlined/>

          <input-phone v-model="form.phone" :required="true" class="col-24 col-sm-12" label="Телефон" outlined/>

          <input-email v-model="form.email" class="col-24 col-sm-12" label="E-mail" outlined/>

          <q-input v-model="form.comment" :rules="createRules('required')" class="col-24 col-sm-24"
                   label="Опыт работы и квалификация" outlined rows="3" type="textarea"/>

        </div>

      </div>

    </q-form>
  </StackItemModal>
</template>
<script setup lang="ts">
import InputEmail from "@/components/Form/UiInputEmail.vue";
import InputPhone from "@/components/Form/UiInputPhone.vue";
import {MenuItem} from "@/gql/gen";
import companyVacancyOrderMutation from "@/gql/gen/mutation/companyVacancyOrderMutation";
import {useGraphql} from "@/core/graphql/service";
import createRules from "@/core/util/validate/createRules";
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {useVacancyStore} from "@/modules/company/store/vacancy";
import {toNumber} from "lodash-es";
import {storeToRefs} from "pinia";
import {QForm} from "quasar";
import {computed, reactive, ref, toRefs} from "vue";

const {mutationWrapped, responseSelection} = useGraphql()
const vacancyStore = useVacancyStore()
const {vacanciesById} = storeToRefs(vacancyStore)

const props = withDefaults(defineProps<StackComponentProps & {
  id: string
}>(), {})

const {bind, stackItem} = useStackComponent<StackItemModalState>(props)

const {id} = toRefs(props)

const entity = computed(() => vacanciesById.value[toNumber(id.value)])

const formDefault = {
  fio: '',
  phone: '',
  email: '',
  comment: '',
}

const form = reactive({...formDefault})

const formRef = ref<QForm | null>(null)

const title = computed(() => entity.value && entity.value.NAME)

const onSubmit = async () => {
  if (!(await formRef.value?.validate(true))) {
    return
  }

  const mutation = companyVacancyOrderMutation({
    payload: true,
    ...responseSelection()
  })
  const res = await mutation.mutate({
    id: 0,
    model: {
      ...form,
    }
  })
  if (res?.success) {
    stackItem?.close()
  }
}

const actions = computed(() => {
  const res: Partial<MenuItem>[] = []
  res.push({
    label: 'Откликнуться на вакансию',
    onClick: onSubmit
  })
  res.push({
    outline: true,
    label: 'Закрыть',
    onClick: () => stackItem?.close()
  })
  return res
})

</script>
<style lang="scss" scoped>
.c-resp {
  :deep() {
    ul {
      padding: 0 0 16px 14px;
    }
  }
}
</style>
