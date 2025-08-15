<template>


  <UiInput
    v-bind="inputProps"
    v-model="maskedValue"
    :mask="mask"
    :rules="computedRules"
    ref="inputRef"
    @keydown="onKeydownHandler"
    @blur="onBlurHandler"
    @focus="onFocusHandler"
    @paste="onPaste"
    :errors="localErrors"
    :errors-manager="props.errorsManager"
    :field-name="props.fieldName"
    :required="props.required"
    :label="props.label">
    <template
      v-for="(_, name) in $slots"
      #[name]="slotProps">
      <slot :name="name" v-bind="slotProps || {}" />
    </template>
  </UiInput>
</template>

<script setup lang="ts">
import checkPhone from '@/core/util/validate/checkPhone';
import { computed, ref, useAttrs } from 'vue';
import UiInput, { UiInputProps } from './UiInput.vue';
import { QINPUT_PROPS_DEFAULT } from './hooks';

const props = withDefaults(defineProps<UiInputProps>(), {
  ...QINPUT_PROPS_DEFAULT,
})

const emit = defineEmits(['update:modelValue', 'blur', 'focus', 'keydown', 'paste']);

const attrs = useAttrs();
const filteredAttrs = computed(() => {
  const result: Record<string, any> = {};
  for (const key in attrs) {
    if (!/^on[A-Z]/.test(key)) {
      result[key] = attrs[key];
    }
  }
  return result;
});

const inputRef = ref<InstanceType<typeof UiInput> | null>(null);
const isTyping = ref(false);
const localErrors = ref<any[]>([]);


const mask = computed(() => props.mask || '+7 (###) ###-##-##');

const maskedValue = computed({
  get() {
    if (!props.modelValue || props.modelValue === '7') return '';
    const digits = String(props.modelValue).replace(/\D/g, '');
    if (digits.length < 2 || digits[0] !== '7') return '';
    return digits.slice(1);
  },
  set(val: string) {
    val = val || ''
    let digits = val.replace(/\D/g, '');
    if (!digits) {
      emit('update:modelValue', '');
    } else {
      if (digits.length === 11 && digits[0] === '8') {
        digits = '7' + digits.slice(1);
      }
      if (digits[0] === '7') {
        emit('update:modelValue', digits);
      } else if (digits.length === 10) {
        emit('update:modelValue', '7' + digits);
      } else {
        emit('update:modelValue', digits);
      }
    }
  }
});

const computedRules = computed(() => [
  (val: string) => {
    let digits = val ? val.replace(/\D/g, '') : '';
    if (isTyping.value && digits.length < 10) return true;
    if (!val) return true;
    if (!checkPhone(digits)) return 'Неверный формат телефона';
    return true;
  },
  ...(props.rules || [])
]);

function onKeydownHandler(e: Event) {
  isTyping.value = true;
  localErrors.value = [];
  inputRef.value?.resetValidation?.();
  emit('keydown', e);
}

function onBlurHandler(e: Event) {
  isTyping.value = false;
  (inputRef.value as any)?.validate?.();
  emit('blur', e);
}

function onFocusHandler(e: Event) {
  emit('focus', e);
}

const onPaste = (e: ClipboardEvent) => {
  const clipboardData = e.clipboardData || (window as any).clipboardData;
  if (!clipboardData) return;
  const pasted = clipboardData.getData('Text');
  let digits = pasted.replace(/\D/g, '');

  if (digits.length === 11 && digits[0] === '8') {
    digits = '7' + digits.slice(1);
  }
  if (digits.length === 11 && digits[0] === '7') {
    digits = digits.slice(1);
  }
  if (digits.length === 10) {
    maskedValue.value = digits;
    e.preventDefault();
  }
};

const inputProps = computed(() => {
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const { modelValue, ...rest } = props;

  const _props = {
    ...rest,
    mask: mask.value,
    rules: computedRules.value,
  };

  delete _props['onUpdate:modelValue']
  return _props
});

defineExpose({
  inputRef,
  clearExternalErrors: () => {
    inputRef.value?.resetValidation?.();
    localErrors.value = [];
  }
});
</script>
