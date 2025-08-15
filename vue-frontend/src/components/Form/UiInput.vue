<template>
  <div class="q-input-wrapper">
    <q-input
        ref="inputRef"
        v-bind="_props"
        v-model="value"
        :rules="rulesComputed"
        :error="errorsComputed.length > 0"
        :reactive-rules="true"
        v-on="listeners"
    >
      <template
          v-for="(_, name) in $slots"
          #[name]="slotData">
        <slot :name="name" v-bind="slotData"/>
      </template>
      <template #error>
        <InputErrors v-bind="bindInputErrors as any"/>
      </template>
    </q-input>
  </div>
</template>

<script setup lang="ts">
import {QInput, QInputProps} from 'quasar';
import InputErrors from './UiInputErrors.vue';
import {
  propsFilter,
  QINPUT_EVENTS,
  QINPUT_PROPS_DEFAULT,
  QINPUT_SCHEME,
  QInputEvent,
  QInputEventPayloadMap,
  UiFieldProps,
  useFormField,
  useQuasarListeners
} from './hooks';
import {computed} from "vue";

export type UiInputProps = Omit<QInputProps, 'error'> & UiFieldProps

const props = withDefaults(defineProps<UiInputProps>(), {
  ...QINPUT_PROPS_DEFAULT,
})

const emit = defineEmits([...QINPUT_EVENTS] as string[])

const {
  errorsComputed,
  onUpdate,
  bindInputErrors,
  rulesComputed,
  value,
  resetValidation,
  inputRef,
  onInput,
  onBlur,
  validate,
} = useFormField(props, emit)

const listeners = useQuasarListeners<QInputEvent, QInputEventPayloadMap>(
    emit,
    QINPUT_SCHEME,
    {
      "update:modelValue": (e) => {
        onUpdate()
        onInput(e)
      },
      "blur": (e) => {
        onBlur(e)
      },
    }
)

const _props = computed(() => propsFilter(props))

defineOptions({
  inheritAttrs: false
})

defineExpose({
  resetValidation,
  validate,
  inputRef
});

</script>

<style lang="scss" scoped></style>
