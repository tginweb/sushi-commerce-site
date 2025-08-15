<template>
  <div
      class="c-quantity"
      :style="{
         border: '1px solid ' + containerBorderColor
      }"
  >

    <div v-if="disable">
      x {{ valueBlur }} {{ measureShow ? measureName : '' }}
    </div>

    <div
        v-else
        :class="controlClass"
        :style="controlStyle"
        class="flex no-wrap justify-between"
    >
      <q-btn
          :icon="ICONS.fasMinus"
          class="--icon-only"
          unelevated
          @click.stop.prevent="onBasketQuantityDown"
          v-bind="btnProps"
      />

      <div
          :class="inputClass"
          class="c-input flex no-wrap items-center"
      >
        <input
            ref="input"
            v-model="valueBlur"
            :class="inputControlClass"
            :size="(valueBlur+'').length + inputSizeAdd"
            :style="{
                fontSize: inputSize,
                border: 0,
                outline: 'none'
              }"
            class="col text-center"
            maxlength="7"
            type="text"
            @blur="onBlur"
            v-if="!inputDisable"
        />
        <div
            v-else
            :class="inputControlClass"
            :style="{
              fontSize: inputSize,
              border: 0,
            }"
        >
          {{ valueBlur }}
        </div>

        <div
            v-if="measureShow && measureName"
            :style="{
              fontSize: inputSize
            }"
            class="q-mr-xs"
            @click="$refs.input.focus()"
        >
          {{ measureName }}
        </div>
      </div>

      <q-btn
          :icon="ICONS.fasPlus"
          class="--icon-only"
          unelevated
          @click.stop.prevent="onBasketQuantityUp"
          v-bind="btnProps"
      />
    </div>

    <div v-if="error" class="q-mt-sm text-red s-font-sm">
      {{ error }}
    </div>

  </div>
</template>

<script setup lang="ts">

import {ICONS} from "@/assets/icons";
import {computed, ref, toRefs, watch} from "vue";
import {QBtnProps} from "quasar";
import {ComponentProps} from "@/core/vue/types";

type QuantityInputProps = {
  disable?: boolean
  measureRatio?: number
  measureName?: string
  measureShow?: boolean
  inputSize?: string
  inputSizeAdd?: number
  btn?: ComponentProps<QBtnProps>
  controlClass?: string
  controlStyle?: any
  inputClass?: string
  inputControlClass?: string
  inputDisable?: boolean
  color?: string
  containerBorderColor?: string
}

const props = withDefaults(defineProps<QuantityInputProps>(), {
  disable: false,
  measureRatio: 1,
  measureShow: false,
  inputSize: '16px',
  inputSizeAdd: 0,
  inputDisable: false,
  color: 'grey-7',
  containerBorderColor: '#EEEEEE',
  btn: () => ({}),
})

const {
  measureRatio,
  inputSizeAdd,
  inputDisable,
  controlStyle,
  controlClass,
  color,
  containerBorderColor
} = props

const {btn} = toRefs(props)

const btnProps: QBtnProps = {
  size: '16px',
  outline: true,
  class: 'q-px-sm',
  flat: true,
  dense: true,
  color: color,
  ...btn.value
}

const model = defineModel<number | string | null>({default: 0})

const emit = defineEmits<{
  'update:model-value': [number]
}>()

const measureRatioDights = computed(() => {
  const ratio = measureRatio || 1
  if (Math.floor(ratio) === ratio) return 0
  // @ts-ignore
  return ratio.toString().split(".")[1].length || 0
})

const normalizeValue = (val: string | number) => {
  return Number((typeof val === 'string' ? parseFloat(val) : val).toFixed(measureRatioDights.value))
}

const val = normalizeValue(model.value || measureRatio || 0)

const valueState = ref<number>(val)
const valueBlur = ref<string>(val.toString())
const error = ref<any>()

const valueInput = computed({
  get: () => {
    return valueState.value ? normalizeValue(valueState.value) : 0
  },
  set: (val: string | number) => {
    valueState.value = normalizeValue(val)
    model.value = valueState.value
  },
})

const onBlur = () => {
  if (parseFloat(valueBlur.value) === 0) {
    error.value = null
    valueInput.value = 0
    return
  }
  if (!error.value)
    valueInput.value = valueBlur.value
}

const onBasketQuantityDown = () => {
  let newValue = valueState.value - measureRatio
  if (newValue < 0) {
    newValue = 0
  }
  emit('update:model-value', newValue)
}

const onBasketQuantityUp = () => {
  const newValue = valueState.value + measureRatio
  emit('update:model-value', newValue)
}

watch(valueBlur, () => {
  error.value = ''
})

watch(model, () => {
  const val = normalizeValue(model.value || 0)
  valueState.value = val
  valueBlur.value = val.toString()
})

</script>

<style lang="scss" scoped>

.c-quantity {
  border-radius: 18px;
  overflow: hidden;
}

.c-input {
  min-height: 100%;

  .bordered {
    border-top: 1px solid #2C2C75FF;
    border-bottom: 1px solid #2C2C75FF;
    margin: 0 -2px 0 -2px;
    padding: 0 3px;
  }

  input {
    padding: 0;
  }
}

.c-action-tooltip {
  max-width: 150px;
}


</style>
