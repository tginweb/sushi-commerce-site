<template>
  <div class="q-otp-input">
    <div
      class="otp-wrapper row items-center justify-center"
      :class="wrapperClass"
      :style="wrapperStyle"
      :id="id">

      <q-input

        v-for="(digit, index) in digits"
        :key="id + index"
        :ref="el => setDigitInputRef(el, index)"
        v-model="inputValue[index]"
        mask="#"
        maxlength="1"
        class="otp-input"
        :class="[
          inputClass,
          activeInput === index ? activeInputClass : '',
        ]"
        :error="haveError"
        no-error-icon
        error-color="red"
        :placeholder="placeholder"
        :disable="isDisabled"
        :type="type"
        @focus="() => { console.log('[InputPin] focus', index); onFocus(index) }"
        @blur="onBlur"
        @paste="onPaste"
        @update:model-value="(val) => { console.log('[InputPin] update:model-value', index, val); onInput(index, val) }"
        @keydown="onKeydown(index, $event)"
        :style="inputStyle"
        dense
        outlined
        hide-bottom-space />
    </div>
    <div v-if="haveError" :class="errorClass">
      <slot name="errorMessage" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { QInput } from 'quasar';
import { defineOptions, nextTick, onBeforeUnmount, onBeforeUpdate, onMounted, onUpdated, ref, toRefs, useAttrs, watch } from 'vue';

console.log('[InputPin] component loaded')

defineOptions({ name: 'InputPin' })

const props = withDefaults(defineProps<{
  modelValue?: string
  digits?: number
  id?: string
  type?: 'text' | 'number' | 'password'
  placeholder?: string
  isDisabled?: boolean
  haveError?: boolean // было isValid
  autoFocus?: boolean
  inputClass?: string
  activeInputClass?: string
  wrapperClass?: string
  errorClass?: string
}>(), {
  modelValue: '',
  digits: 5,
  id: 'otp',
  type: 'text',
  placeholder: '-',
  isDisabled: false,
  haveError: false, // по умолчанию нет ошибки
  autoFocus: true,
  inputClass: '',
  activeInputClass: '',
  wrapperClass: '',
  errorClass: 'text-negative text-bold q-mt-xs',
})

const emit = defineEmits([
  'update:modelValue',
  'blur',
  'focus',
  'paste',
  'input',
  'complete'
])

const attrs = useAttrs()
const {
  digits,
  id,
  type,
  placeholder,
  isDisabled,
  haveError,
  autoFocus,
  inputClass,
  activeInputClass,
  wrapperClass,
  errorClass,
} = toRefs(props)

const inputStyle = {}
const wrapperStyle = {}

const inputValue = ref<string[]>([])
const activeInput = ref(-1)
const digitInput = ref<Array<InstanceType<typeof QInput> | null>>([])

// Сброс refs перед каждым рендером
onBeforeUpdate(() => {
  digitInput.value = []
})

function setDigitInputRef(el: InstanceType<typeof QInput> | null, index: number) {
  digitInput.value[index] = el
}

watch(
  () => props.modelValue,
  (v) => {
    inputValue.value = (v || '').split('').slice(0, digits.value)
    while (inputValue.value.length < digits.value) inputValue.value.push('')
  },
  { immediate: true }
)

watch(
  () => inputValue.value,
  (arr) => {
    const joined = arr.join('')
    console.log('[InputPin] inputValue changed:', arr, '->', joined)
    emit('update:modelValue', joined)
    emit('input', joined)
    if (joined.length === digits.value && !arr.includes('')) {
      emit('complete', joined)
    }
  }
)

function onFocus(index: number) {
  activeInput.value = index
  emit('focus')
}

function onBlur() {
  activeInput.value = -1
  emit('blur')
}

function onKeydown(index: number, e: KeyboardEvent) {
  const isDigit = /^[0-9a-zA-Z]$/.test(e.key)
  if (isDigit) {
    inputValue.value[index] = e.key
    emit('update:modelValue', inputValue.value.join(''))
    if (index < digits.value - 1) {
      nextTick(() => digitInput.value[index + 1]?.focus())
    }
    e.preventDefault()
  } else if (e.key === 'Backspace') {
    inputValue.value[index] = ''
    emit('update:modelValue', inputValue.value.join(''))
    if (index > 0) {
      nextTick(() => digitInput.value[index - 1]?.focus())
    }
    e.preventDefault()
  } else if (e.key === 'ArrowLeft' && index > 0) {
    nextTick(() => digitInput.value[index - 1]?.focus())
    e.preventDefault()
  } else if (e.key === 'ArrowRight' && index < digits.value - 1) {
    nextTick(() => digitInput.value[index + 1]?.focus())
    e.preventDefault()
  }
  // Tab и Shift+Tab — стандартное поведение
}

// onInput теперь только для синхронизации inputValue, не управляет фокусом
function onInput(index: number, val: string | number | null | undefined) {
  let str = (val ?? '').toString()
  let cleanVal = str.replace(/[^0-9a-zA-Z]/g, '')
  if (cleanVal.length > 1) cleanVal = cleanVal[0]
  inputValue.value[index] = cleanVal
  emit('update:modelValue', inputValue.value.join(''))
}

function onPaste(e: ClipboardEvent) {
  const pasted = (e.clipboardData?.getData('text') || '').replace(/[^0-9a-zA-Z]/g, '')
  console.log('[InputPin] onPaste:', pasted)
  if (pasted.length) {
    for (let i = 0; i < digits.value; i++) {
      inputValue.value[i] = pasted[i] || ''
    }
    emit('update:modelValue', inputValue.value.join(''))
    nextTick(() => {
      // Фокус на первую пустую или последнюю ячейку
      let focusIndex = inputValue.value.findIndex(v => !v)
      if (focusIndex === -1) focusIndex = digits.value - 1
      digitInput.value[focusIndex]?.focus()
    })
    e.preventDefault()
    emit('paste', e)
  }
}

function handleNativePaste(e: ClipboardEvent) {
  const pasted = (e.clipboardData?.getData('text') || '').replace(/[^0-9a-zA-Z]/g, '')
  console.log('[InputPin] native paste:', pasted)
  if (pasted.length) {
    for (let i = 0; i < digits.value; i++) {
      inputValue.value[i] = pasted[i] || ''
    }
    emit('update:modelValue', inputValue.value.join(''))
    nextTick(() => {
      let focusIndex = inputValue.value.findIndex(v => !v)
      if (focusIndex === -1) focusIndex = digits.value - 1
      digitInput.value[focusIndex]?.focus()
    })
    e.preventDefault()
    emit('paste', e)
  }
}

function attachPasteListeners() {
  digitInput.value.forEach((q) => {
    const inputEl = q?.$el?.querySelector('input') as HTMLInputElement | null
    if (inputEl && !inputEl._pinPasteListener) {
      inputEl.addEventListener('paste', handleNativePaste)
      inputEl._pinPasteListener = true
    }
  })
}

function detachPasteListeners() {
  digitInput.value.forEach((q) => {
    const inputEl = q?.$el?.querySelector('input') as HTMLInputElement | null
    if (inputEl && inputEl._pinPasteListener) {
      inputEl.removeEventListener('paste', handleNativePaste)
      delete inputEl._pinPasteListener
    }
  })
}

onMounted(attachPasteListeners)
onUpdated(attachPasteListeners)
onBeforeUnmount(detachPasteListeners)

onMounted(() => {
  if (autoFocus.value && !isDisabled.value) {
    nextTick(() => digitInput.value[0]?.focus())
  }
})

onBeforeUnmount(() => {
  digitInput.value.forEach((q) => {
    const inputEl = q?.$el?.querySelector('input') as HTMLInputElement | null
    if (inputEl) {
      inputEl.removeEventListener('paste', handleNativePaste)
    }
  })
})

defineExpose({ focus: () => digitInput.value[0]?.focus() })
</script>

<style scoped lang="scss">
.q-otp-input {
  .otp-wrapper {
    gap: 10px;
  }

  .otp-input {
    width: 3.5rem;
    text-align: center;
    font-size: 1.8rem;
  }

  :deep() {
    input {
      text-align: center;
    }

    .q-field__control {
      height: 55px;
    }
  }
}
</style>
