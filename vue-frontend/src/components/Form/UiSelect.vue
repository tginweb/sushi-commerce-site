<template>
  <div>
    <QSelect
        ref="inputRef"
        v-bind="propsComputed as any"
        v-model="value"
        :rules="rulesComputed"
        :error="errorsComputed.length > 0"
        :options="options"
        v-on="listeners"
        :bottom-slots="true"
    >
      <template
          v-for="(_, name) in $slots"
          #[name]="slotData">
        <slot :name="name" v-bind="slotData"/>
      </template>
      <template #error>
        <InputErrors v-bind="bindInputErrors"/>
      </template>
    </QSelect>
  </div>
</template>

<script setup lang="ts">
import {QSelect, QSelectProps} from 'quasar';
import InputErrors from './UiInputErrors.vue';
import {
  QINPUT_PROPS_DEFAULT,
  QSELECT_EVENTS,
  QSELECT_SCHEME,
  QSelectEvent,
  QSelectEventPayloadMap,
  UiFieldProps,
  useFormField,
  useQuasarListeners
} from './hooks';
import {computed, ref} from "vue";

export type UiSelectOption = {
  label?: string
  value?: string
  data?: any
}

export type UiSelectProps = Omit<QSelectProps, 'error'> & UiFieldProps & {
  searchable?: boolean
  search?: (query: string) => Promise<UiSelectOption[]>
}

const props = withDefaults(defineProps<UiSelectProps>(), {
  ...QINPUT_PROPS_DEFAULT,
})

const searchedOptions = ref<UiSelectOption[]>([])

const options = computed(() => {
  return [
    ...searchedOptions.value,
    ...(props.options || [])
  ]
})

const filterFn = async (query, update, abort) => {
  if (!query || !props.search) {
    abort()
    return
  }
  let suggestions = await props.search(query)
  update(() => {
    searchedOptions.value = suggestions
  })
}

const propsAdditional = computed(() => {
  let _props = {} as QSelectProps
  if (props.searchable) {
    _props = {
      ..._props,
      useInput: true,
      hideSelected: true,
      fillInput: true,
      hideDropdownIcon: true
    }
    _props.onFilter = filterFn
  }
  return _props
});

const propsComputed = computed(() => {
  const _props = {
    ...props,
    ...propsAdditional.value
  };
  delete _props['onUpdate:modelValue'];
  return _props
});

const emit = defineEmits([...QSELECT_EVENTS, 'select'] as string[])

const {
  errorsComputed,
  onUpdate,
  bindInputErrors,
  rulesComputed,
  value,
  fieldName,
  resetValidation,
  inputRef,
  onInput,
  onBlur,
  validate,
} = useFormField(props, emit)

const listeners = useQuasarListeners<QSelectEvent, QSelectEventPayloadMap>(
    emit,
    QSELECT_SCHEME,
    {
      "update:modelValue": (e) => {
        const option = findValueInOptions(e)
        emit('select', e, option)
        onUpdate()
        onInput(e)
      },
      "blur": (e) => {
        onBlur(e)
      },
    }
)

const getOptionField = (option, field) => {
  if (typeof field === 'string') {
    return option[field] || option[field]
  } else if (typeof field === 'function') {
    return field(option)
  }
}

const findValueInOptions = (value: string) => {
  return options.value.find(option => getOptionField(option, props.optionValue) === value)
}

defineOptions({
  inheritAttrs: false,
})

defineExpose({
  resetValidation,
  validate,
  inputRef
});


</script>

<style lang="scss" scoped></style>
