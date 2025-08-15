<template>
  <StackItemModal
      :dialog="{
        onHeaderBack,
        width: '470px'
      }"
      :sheet="{}"
      :actions="actions"
      v-bind="stackComponent.bind"
      :title="title">

    <q-form ref="form" class="q-pt-md q-mb-sm">

      <UiInputAddress
          outlined
          clearable
          v-model="selected"
          :searchable="true"
          :search="search"
          :rules="createRules('required')"
          @address-select="onSelect"
          emit-value
      />

      <template v-if="step === 'start'">

        <div class="q-gutter-md q-mb-lg">

          <UiInputPhone
              v-if="false"
              v-model="phone"
              class="q-mb-md"
              label="Телефон"
              field-name="phone"
              @enter="loginStart"
              :required="true"
              outlined
              :loading="clientCardApplyByPhoneQuery.loading"
              :readonly="step !== 'start'"
              :bottom-slots="true"
              :errors-manager="mutationLoginStart.errorManager"/>

        </div>

        <div v-if="phoneIsValid && clientCard && clientCard.ID" class="c-benefits">
          <div class="q-px-md q-py-md">
            <div class="s-font-xs text-primary- text-weight-bold">Вам доступны:</div>
            <q-list class="c-list q-mt-sm" dense>
              <template v-for="item of clientCard.DISCOUNTS">
                <q-item
                    :key="item.NAME || item.ID"
                    v-if="item.PROPERTIES.ACTION_DISCOUNT_PERCENT"
                >
                  <q-item-section>
                    <q-item-label class="s-font-sm">{{ item.NAME }}</q-item-label>
                    <q-item-label caption v-if="item.PREVIEW_TEXT">{{ item.PREVIEW_TEXT }}</q-item-label>
                  </q-item-section>
                  <q-item-section side top>
                    <q-item-label class="">
                      <q-chip color="primary" outline dense class="text-weight-bold s-font-md q-ma-none">
                        {{ item.PROPERTIES.ACTION_DISCOUNT_PERCENT }}%
                      </q-chip>
                    </q-item-label>
                  </q-item-section>
                </q-item>
              </template>
            </q-list>
          </div>
        </div>

      </template>
      <template v-else-if="step === 'request'">

        <div class="q-pb-md s-font-sm">
          Чтобы подтведить свой номер телефона
          <span class="text-weight-bold">{{ phone }}</span>
          используйте один из вариантов ниже:
        </div>

        <div class="q-gutter-y-md ">

          <q-card
              v-for="mode of confirmModes"
              :key="mode.CODE"
              flat
              bordered
              class="cursor-pointer"
              @click="onSelectConfirmMode(mode)">
            <q-card-section>
              <div class="row no-wrap q-gutter-sm">
                <div class="col-3 text-left">
                  <template v-if="mode.ICON">
                    <img src="/telegram.png" v-if="mode.ICON === 'telegram'" style="width: 40px;"/>
                    <q-icon v-else
                            :name="ICONS[mode.ICON as keyof typeof ICONS]"
                            :color="mode.COLOR || 'primary'"
                            size="35px"
                            :style="{ 'fill': mode.COLOR }"/>
                  </template>
                </div>
                <div class="col-shrink">
                  <div class="s-font-lg text-weight-bold">{{ mode.LIST_NAME }}</div>
                  <div class="s-font-sm leading-e4 q-mt-xs">{{ mode.LIST_CAPTION }}</div>
                  <q-btn v-bind="excludeEmptyFields<any>(mode.LIST_BUTTON_WEB)"
                         :style="{ 'background-color': mode.COLOR }"
                         class="text-white full-width q-mt-sm" size="14px" unelevated :disable="processing"
                         :loading="processing && confirmMode && confirmMode.CODE === mode.CODE"/>
                </div>
              </div>
            </q-card-section>
          </q-card>
        </div>
      </template>
      <template v-else-if="step === 'confirm'">

        <div v-if="confirmMode">
          <RuntimeContent
              :content="confirmMode.CONFIRM_CONTENT_WEB"
              :contentProps="templateProps"/>
        </div>

        <Transition name="collapse">
          <div class="q-mt-md text-center">
            <div style="display: inline-block;">
              <div class="text-weight-medium q-mb-xs">
                введите код:
              </div>
              <input-pin
                  v-model="code"
                  :digits="4"
                  :gap="10"
                  :have-error-ss="codeInputError"
                  :is-disabled="processing"
                  @input="onCodeInput"
                  @complete="loginConfirm"/>
            </div>
          </div>
        </Transition>

        <RateLimiter text="Превышен лимит попыток. Запросить код снова можно через {t}" v-model="rateTimeout"
                     class="q-mt-md q-mb-sm text-center s-font-sm"/>

      </template>

    </q-form>

  </StackItemModal>
</template>

<script setup lang="ts">

import {ICONS} from '@/assets/icons';
import UiInputPhone from '@/components/Form/UiInputPhone.vue';
import InputPin from '@/components/Form/UiInputPin.vue';
import RateLimiter from '@/components/RateLimiter.vue';
import {MenuItem, UserAuthConfirm} from '@/gql/gen';
import userAuthLoginConfirmMutation from '@/gql/gen/mutation/userAuthLoginConfirmMutation';
import userAuthLoginRequestMutation from '@/gql/gen/mutation/userAuthLoginRequestMutation';
import userAuthLoginStartMutation from '@/gql/gen/mutation/userAuthLoginStartMutation';
import useRequestsGroup from '@/core/graphql/hooks/useRequestsGroup';
import {useGraphql} from '@/core/graphql/service';
import quasarFormValidate from '@/core/quasar/quasarFormValidate';
import excludeEmptyFields from '@/core/util/excludeEmptyFields';
import checkPhone from '@/core/util/validate/checkPhone';
import {createAlertsChannel} from '@/core/composable/createAlertsChannel';
import {createRequestContext} from '@/core/composable/createRequestContext';
import StackItemModal from '@/packages/stack-router/components/StackItemModal.vue';
import {StackComponentProps, useStackComponent} from '@/packages/stack-router/hooks/useStackComponent';
import {useAlerts} from '@/core/store/alerts';
import {useUserStore} from '@/modules/user/store/user';
import {storeToRefs} from 'pinia';
import {QForm} from 'quasar';
import {computed, nextTick, onBeforeUnmount, reactive, ref, watch} from 'vue';
import createRules from "@/core/util/validate/createRules";
import UiInputAddress from "@/components/Form/UiInputAddress.vue";
import {useLoalityStore} from "@/modules/shop/store/loyalty";

const filled = ref(false)

const onBlur = (e: any) => {
  console.log('onBlur', e)
}

const onFocus = (e: any) => {
  console.log('onFocus', e)
}

type Step = 'start' | 'request' | 'confirm'
const steps: Step[] = ['start', 'request', 'confirm']

const props = withDefaults(defineProps<StackComponentProps & {}>(), {});

const stackComponent = useStackComponent(props)
const {stackItem} = stackComponent

const resendTimer = ref(0);
let resendInterval: NodeJS.Timeout | null = null;

const layaltyStore = useLoalityStore()

const {clientCardApplyByPhoneQuery} = layaltyStore
const {clientCard} = storeToRefs(layaltyStore)

const userStore = useUserStore();
const {showAlert, showFlash} = useAlerts();

const graphql = useGraphql()
const {mutationWrapped, useMutation, useLazyQuery, responseSelection} = graphql
const {processing} = storeToRefs(graphql)

// Состояние диалога
const alertsChannel = createAlertsChannel()
const requestContext = createRequestContext()

///requestContext.setAlertsChannel(alertsChannel)

const selected = ref('Рябикова 31б')

const onSelect = (address: string, data: any) => {
  console.log('ADDRESS SELECTED', address, data)
}

const search = (query: any) => {
  console.log('query', query)
  return [
    {
      value: 'ivan',
      label: 'Иван',
    },
    {
      value: 'petr',
      label: 'Петр',
    }
  ]
}

const form = ref<QForm>()
const step = ref<Step>('start');
const phone = ref('79501102996');
const phones = reactive({})

const email = ref('');
const code = ref('');
const codeInputError = ref<boolean>()


const rateTimeout = ref(0)
const requestName = ref()

const title = ref('Авторизация')
const context = ref('')

const phoneIsValid = computed(() => {
  return !!(phone.value && checkPhone(phone.value))
})

const templateProps = computed(() => {
  return {
    phone: phone.value,
  }
})

const confirmModes = ref<UserAuthConfirm[]>([])
const confirmMode = ref<UserAuthConfirm | null>(null)

const onHeaderBack = computed(() => {
  if (step.value !== 'start') {
    return () => {
      goStepPrev()
    }
  }
})

const onSelectConfirmMode = (mode: UserAuthConfirm) => {
  console.log('onSelectConfirmMode')
  if (processing.value)
    return;
  confirmMode.value = mode
  if (mode.LIST_BUTTON_WEB?.url) {
    //window.open(mode.LIST_BUTTON_WEB.url, "_blank");
  }
  loginRequest()
}

const mutationLoginStart = useMutation(userAuthLoginStartMutation({
  payload: {
    confirmModes: {
      __fragment: 'UserAuthConfirm'
    }
  },
  ...responseSelection(true)
}), {
  errorManager: {
    onFieldChangeClear: true,
    groupsSchema: {
      fieldName: 'fieldName'
    }
  }
}, requestContext)


const mutationLoginRequest = useMutation(userAuthLoginRequestMutation({
  payload: {
    id: true
  },
  ...responseSelection(),
}), {}, requestContext)

const mutationLoginConfirm = useMutation(userAuthLoginConfirmMutation({
  payload: {
    appClient: {
      __fragment: 'AppClient'
    },
    redirect: true,
    sessionId: true,
    userId: true
  },
  ...responseSelection()
}), {}, requestContext)

const requests = useRequestsGroup([mutationLoginStart, mutationLoginRequest, mutationLoginConfirm])

const resetCodeState = () => {
  code.value = ''
  codeInputError.value = false
}

const onCodeInput = () => {
  codeInputError.value = false
}

const loginStart = async () => {

  if (!(await quasarFormValidate(form.value,)))
    return;

  const res = await mutationLoginStart.mutate({
    phone: phone.value
  })

  if (res.success) {
    confirmModes.value = res.payload?.confirmModes || []
    goStep('request')
    if (confirmModes.value.length === 1 && confirmModes.value[0]) {
      onSelectConfirmMode(confirmModes.value[0])
    }
  }
}

const loginRequest = async () => {

  if (!confirmMode.value)
    return;

  const res = await mutationLoginRequest.mutate({
    phone: phone.value,
    confirmMode: confirmMode.value.CODE,
  })

  if (res.success) {
    goStep('confirm')
  }
}

const loginConfirm = async () => {
  console.log('loginConfirm', code.value)

  if (!confirmMode.value)
    return;

  const res = await mutationLoginConfirm.mutate({
    phone: phone.value,
    confirmMode: confirmMode.value.CODE,
    code: code.value
  })

  if (res.error?.__typename === 'FormError') {
    if (res.error.name === 'access_not_authorized') {

    }
  }

  if (res.success) {
    //goStep('ok')
  } else {

  }
}

const navGuest = () => {
}

const goStepPrev = () => {
  let index = steps.indexOf(step.value)
  if (index > 0) {
    index--
    const prevStep = steps[index]
    prevStep && goStep(prevStep)
  }
}

const goStep = (name: Step) => {
  if (step.value === name)
    return
  resetCodeState()
  step.value = name
  alertsChannel.clearMessages()
}

const codeIsValid = computed(() => {
  return code.value.length === 4
})

const actions = computed(() => {
  const result: Partial<MenuItem>[] = []

  if (step.value === 'start') {

    if (context.value === 'vorder') {
      if (phoneIsValid.value) {
        result.push({
          label: 'Войти',
          color: 'primary',
          onClick: loginStart,
          loading: processing.value,
          disable: !phoneIsValid.value
        })
        result.push({
          label: 'Продолжить как гость',
          color: 'primary',
          outline: true,
          onClick: navGuest,
          disable: !phoneIsValid.value
        })
      }
    } else {
      result.push({
        label: 'Войти',
        color: 'primary',
        onClick: loginStart,
        loading: processing.value,
        // disable: !phoneIsValid.value
      })
    }

  } else if (step.value === 'request') {

  } else if (step.value === 'confirm') {

    if (codeIsValid.value) {
      result.push({
        label: 'Войти',
        onClick: loginConfirm,
        loading: processing.value,
        //icon: 'refresh',
      })
    }

    const _confirmMode = confirmMode.value

    if (_confirmMode) {
      result.push({
        label: _confirmMode.RESEND_TITLE,
        dense: true,
        onClick: loginRequest,
        disable: processing.value,
        flat: true,
        loading: processing.value,
        icon: 'refresh',
      })
    }
  }

  return result
})


watch(phone, () => {
  console.log('phone changed', phone.value)
  nextTick(() => {
    if (phoneIsValid.value) {
      clientCardApplyByPhoneQuery.query({
        phone: phone.value
      })
    }
  })
})

onBeforeUnmount(() => {
  if (resendInterval) {
    clearInterval(resendInterval);
  }
})

</script>

<style scoped lang="scss">
.auth-dialog {
  min-width: 320px;
  max-width: 400px;
}

.auth-step {
  padding: 20px 0;
}

.confirm-mode-card {
  transition: all 0.2s ease;

  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
}

.pulse i {
  color: #fff
}

.pulse {
  height: 40px;
  width: 40px;
  background-color: orange;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative
}

.pulse::before {
  content: "";
  position: absolute;
  border: 1px solid orange;
  width: calc(100% + 20px);
  height: calc(100% + 20px);
  border-radius: 50%;
  animation: pulse 1s linear infinite
}

.pulse::after {
  content: "";
  position: absolute;
  border: 1px solid ORANGE;
  width: calc(100% + 20px);
  height: calc(100% + 20px);
  border-radius: 50%;
  animation: pulse 1s linear infinite;
  animation-delay: 0.3s
}

.c-benefits {
  border-radius: 16px;
  border: 1px solid #CCCCCC;

  .c-list {
    :deep() {
      .q-item {
        padding: 6px 0 6px 0;
      }
    }
  }
}
</style>
