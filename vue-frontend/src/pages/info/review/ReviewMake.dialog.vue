<template>
  <StackItemModal :dialog="{}" :sheet="{}" :actions="actions" v-bind="stackComponent.bind" title="Оставить отзыв">

    <q-form ref="form" class="q-gutter-md q-pb-lg">

      <div class="">
        С какой вероятностью вы бы порекомендовали нас своим друзьям?
      </div>

      <q-rating v-model="formData.RATING" class="c-reviews__rating__stars" color="primary-brown-gray-1"
                color-selected="actions-yellow" icon="star" icon-selected="star" max="5" size="44px"/>

      <q-input v-model="formData.AUTHOR_NAME" label="Ваше имя" outlined maxlength="70"/>

      <input-phone v-model="formData.AUTHOR_PHONE" :required="true" unmasked-value label="Ваш телефон" outlined/>

      <q-input v-model="formData.TEXT" label="Ваш комментарий" outlined type="textarea" maxlength="3000"
               :rules="createRules('required')" lazy-rules="ondemand"/>

    </q-form>

  </StackItemModal>
</template>
<script setup lang="ts">
import InputPhone from "@/components/Form/UiInputPhone.vue";
import {MenuItem} from "@/gql/gen";
import {useGraphql} from "@/core/graphql/service";
import createRules from "@/core/util/validate/createRules";
import {useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {QForm} from "quasar";
import {computed, reactive, ref} from "vue";
import reviewServiceReviewMutation from "@/gql/gen/mutation/reviewServiceReviewMutation";
import quasarFormValidate from "@/core/quasar/quasarFormValidate";

const props = withDefaults(defineProps(), {})

const stackComponent = useStackComponent(props)

const {stackItem} = stackComponent

const form = ref<QForm | null>(null)

const formData = reactive({
  RATING: 0,
  AUTHOR_NAME: '',
  AUTHOR_PHONE: '',
  TEXT: ''
})

const {useMutation, responseSelection} = useGraphql()

const mutation = useMutation(reviewServiceReviewMutation({
  payload: {
    __fragment: 'ReviewFields'
  },
  ...responseSelection()
}))

const onSubmit = async () => {
  if (!(await quasarFormValidate(form.value)))
    return;
  const res = await mutation.mutate({
    model: formData
  })
  if (res.success) {
    stackItem?.close()
  }
}

const actions = computed(() => {
  const res: Partial<MenuItem>[] = []
  res.push({
    label: 'Отправить отзыв',
    onClick: onSubmit
  })
  return res
})

</script>
<style scoped></style>
