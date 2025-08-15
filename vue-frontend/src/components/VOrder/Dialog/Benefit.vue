<template>
  <StackItemModal
      :dialog="{}"
      :sheet="{}"
      breakpoint="md"
      :actions="actions"
      v-bind="stackComponent.bind"
      v-on="listeners"
      title="Бенефит"
  >

    <ButtonsToggle
        :model-value="benefitTypeState"
        @update:model-value="onBenefitTypeUpdate"
        option-value="value"
        option-label="name"
        :props="{
              class: '--icon-same q-px-md col-grow',
              size: '15px',
              unelevated: true,
              dense: true
            }"
        :props-active="{
              color: 'secondary'
            }"
        :props-inactive="{
              outline: false,
              color: 'grey-3',
              textColor: 'black'
            }"
        :options="benefitTypesComputed"
        class="flex q-gutter-x-md justify-center no-wrap"
    />

    <q-tab-panels v-model="benefitTypeState" animated class="c-tab-panels q-mt-md q-mb-sm">

      <q-tab-panel name="discount" v-if="benefitType === 'discount' && activeCommonDiscount" class="c-tab">

        <div class="c-tab-content">

          <div class="s-font-sm q-mb-sm">
            Будет использована:
          </div>

          <div class="flex q-gutter-sm s-font-lg">
            <div>
              {{ activeCommonDiscount.NAME }}
            </div>
            <div class="text-weight-bold">
              {{ activeCommonDiscount.AMOUNT }}
            </div>
          </div>
        </div>

      </q-tab-panel>

      <q-tab-panel name="bonus" class="c-tab">
        <div class="c-tab-content q-px-md q-py-sm">
          <div v-if="useBonusesError" class="text-center s-font-sm">
            {{ useBonusesError }}
          </div>
          <template v-else>
            <div class="row q-col-gutter-md">
              <div class="col-24">
                <q-input
                    label="Использовать бонусов"
                    :model-value="bonusesState"
                    @update:model-value="onBonusUpdate"
                    mask="#"
                    reverse-fill-mask
                    input-class="s-font-xl text-weight-bold text-grey-8"
                    outlined
                    bg-color="white"
                >
                  <template #append>
                    <div class="s-font-sm text-grey-8 q-mt-sm">
                      доступно
                      <span
                          class="cursor-pointer text-weight-bold"
                          @click="onBonusUpdate(bonusesMax)"
                      >{{
                          bonusesMax
                        }}</span>
                      из {{ bonusesAvailable }}
                    </div>
                  </template>
                </q-input>
              </div>
            </div>
            <div class="swipe-disable q-px-sm">
              <q-slider
                  :model-value="bonusesState"
                  @update:model-value="onBonusUpdate"
                  :max="bonusesMax"
                  :min="0"
                  :marker-labels="bonusMarkers"
                  switch-label-side
                  class="q-mt-md"
              />
            </div>
          </template>
        </div>
      </q-tab-panel>

      <q-tab-panel name="promocode" class="c-tab">
        <div class="c-tab-content">
          <div class="s-font-sm q-mb-sm">
            Использовать промокод:
          </div>
        </div>
      </q-tab-panel>

    </q-tab-panels>

  </StackItemModal>
</template>
<script setup lang="ts">
import {useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {useVorderDialog, VOrderDialogProps, vorderDialogPropsDefault} from "./hook";
import {computed, ref} from "vue";
import {MenuItem} from "@/gql/gen";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import ButtonsToggle from "@/components/ButtonsToggle.vue";
import {ICONS} from "@/assets/icons";
import toInt from "@/core/util/toInt";
import plural from "plural-ru";
import {useVorderBasketStore} from "@/modules/shop/store/vorder/basket";
import {useVorderBenefitStore} from "@/modules/shop/store/vorder/benefit";
import {BenefitTypeCode} from "@/modules/shop/store/vorder/util/benefit";

const vorderStore = useVorderStore()
const basketStore = useVorderBasketStore()
const benefitStore = useVorderBenefitStore()

const {
  attrs,
  bonusesAvailable,
  bonuses,
  bonusesMax,
} = storeToRefs(vorderStore)

const {
  basketIsEmpty
} = storeToRefs(basketStore)


const {
  activeCommonDiscount,
  benefitType,
  benefitTypes,
} = storeToRefs(benefitStore)


const props = withDefaults(defineProps<VOrderDialogProps>(), {
  ...vorderDialogPropsDefault
})

const stackComponent = useStackComponent(props)

const {stackItem} = stackComponent

const {code} = props

const benefitTypeState = ref(benefitType.value)
const bonusesState = ref(bonuses.value)

const {
  listeners
} = useVorderDialog(props)

const bonusesPercent = computed(() => {
  return Math.round((bonusesState.value / bonusesMax.value) * 100)
})

const benefitTypesComputed = computed(() => {
  return benefitTypes.value.map(item => ({
    ...item,
    icon: ICONS[item.icon as keyof typeof ICONS]
  }))
})

const onBenefitTypeUpdate = (val: BenefitTypeCode) => {
  benefitTypeState.value = val
  attrs.value.BENEFIT_TYPE = val
}

const onBonusUpdate = (val: string | number | null) => {
  const num = toInt(val)
  bonusesState.value = num
  attrs.value.BONUSES = num
}

const useBonusesError = computed(() => {
  if (basketIsEmpty.value) {
    return 'Корзина заказа пуста'
  } else if (!bonusesMax.value) {
    return 'У вас пока нет бонусов'
  }
})

const bonusMarkers = computed(() => {
  const res: any = [
    {value: 0, label: '0'},
  ]
  if (bonusesPercent.value < 94 || true) {
    res.push({value: bonusesMax.value, label: bonusesMax.value},)
  }
  return res
})

const bonusesSuffix = computed(() => bonusesState.value ? plural(bonusesState.value, 'бонус', 'бонуса', 'бонусов') : null)

const onApply = () => {
  stackItem?.close()
}

const actions = computed(() => {
  const res: Partial<MenuItem>[] = []

  let closeLabel = 'Закрыть'

  switch (benefitTypeState.value) {
    case 'bonus':
      if (bonusesMax.value && (bonusesState.value < bonusesMax.value)) {
        res.push({
          label: 'Использовать ' + bonusesMax.value + ' бонусов',
          flat: true,
          onClick: () => onBonusUpdate(bonusesMax.value),
        })
      }

      if (!useBonusesError.value) {
        closeLabel = 'Применить'
      }
      break;
  }

  res.push({
    label: closeLabel,
    onClick: onApply
  })

  return res
})

</script>
<style scoped>

.c-tab {
  padding: 0;
}

.c-tab-content {
  background-color: rgba(48, 85, 100, 0.10);
  border-radius: 10px;
  padding: 14px;
}

</style>
