<template>
  <UiInput
    v-bind="inputProps"
    v-model="localValue"
    ref="inputRef"
    @keydown="onKeydownHandler"
    @blur="onBlurHandler"
    @focus="onFocusHandler"
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
import { computed, ref, useAttrs } from 'vue';
import UiInput, { UiInputProps } from './UiInput.vue';
import { QINPUT_PROPS_DEFAULT } from './hooks';

const props = withDefaults(defineProps<UiInputProps>(), {
  ...QINPUT_PROPS_DEFAULT,
})

const emit = defineEmits(['update:modelValue', 'blur', 'focus', 'keydown']);

const attrs = useAttrs();


const inputRef = ref<InstanceType<typeof UiInput> | null>(null);
const isTyping = ref(false);
const localErrors = ref<any[]>([]);

const localValue = computed({
  get() {
    return props.modelValue ?? '';
  },
  set(val: string) {
    emit('update:modelValue', val);
  }
});

const emailRule = (val: string) => {
  if (!val) return true;
  // Простая email-валидация
  const re = /^([\w.!#$%&'*+/=?^_`{|}~-]+@[\w-]+\.[\w-]{2,})$/i;
  if (!re.test(val)) return 'Введите корректный email';
  return true;
};

const computedRules = computed(() => [
  emailRule,
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

const inputProps = computed(() => {
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const { modelValue, ...rest } = props;
  const _props = {
    ...rest,
    rules: computedRules.value,
    type: 'email' as const,
    autocomplete: 'email',
  };
  delete _props['onUpdate:modelValue'];
  return _props;
});

defineExpose({
  inputRef,
  clearExternalErrors: () => {
    inputRef.value?.resetValidation?.();
    localErrors.value = [];
  }
});
</script>
