<template>
  <div class="ui-input-address">
    <div class="controls">
      <q-input
          ref="inputRef"
          v-bind="inputProps"
          v-model="inputValue"
          :loading="loading"
          :error="errorsComputed.length > 0"
          :reactive-rules="true"
          @focus="onFocus"
          @blur="onInputBlur"
          @keydown="onInputKeydown"
          bottom-slots
          autogrow
      >
        <template v-for="(_, slot) in $slots" #[slot]="scope">
          <slot :name="slot" v-bind="scope || {}"/>
        </template>
        <template #error>
          <InputErrors v-bind="bindInputErrors as any"/>
        </template>
      </q-input>
      <q-menu
          v-if="searchedOptions.length"
          ref="menuRef"
          v-model="showOptions"
          :anchor1="inputRef?.$el"
          self="top left"
          fit
          no-parent-event
          no-focus
          no-refocus
      >
        <q-list>
          <q-item
              v-for="(option, index) in searchedOptions"
              :key="index"
              :active="index === highlightedIndex"
              clickable
              @click="onSelect(option)"
              @mouseenter="highlightedIndex = index"
              class=""
          >
            <slot name="option" v-bind="option">
              <q-item-section>
                <q-item-label>{{ option.label }}</q-item-label>
              </q-item-section>
            </slot>
          </q-item>
        </q-list>
      </q-menu>
    </div>
    <JsonViewer
        v-if="false"
        :value="{
          value: value,
          inputValue: inputValue,
          addressFields: addressFields
        }"
    />
  </div>
</template>

<script setup lang="ts">
import {type ComponentPublicInstance, computed, defineComponent, nextTick, ref, toRefs, watch} from 'vue';
import {propsFilter, QINPUT_PROPS_DEFAULT, QSELECT_EVENTS, useFormField} from './hooks';
import {DaDataAddress, DaDataAddressBounds} from "@/modules/geo/types/dadata";
import {QInput, QItem, QItemSection, QList, QMenu} from "quasar";
import {DaDataAddressRequestParams, useDaData} from "@/modules/geo/store/dadata";
import {UiInputProps} from "@/components/Form/UiInput.vue";
//@ts-ignore
import {debounce} from "lodash-es";
import InputErrors from "@/components/Form/UiInputErrors.vue";

export type UiInputAddressFields = {
  address?: string | null
  region?: string | null
  city?: string | null
  street?: string | null
  house?: string | null
}

export type UiInputAddressOption = {
  label: string
  value: string
  data: DaDataAddress
}

const {getAddressSuggestions} = useDaData()

const addressBounds = ['city', 'street', 'house']

export type UiInputAddressProps = UiInputProps & {
  location?: any
  locations?: any
  fromBound?: DaDataAddressBounds
  toBound?: DaDataAddressBounds
  optionLabel?: ((option: UiInputAddressOption) => string) | string | undefined;
  optionValue?: ((option: UiInputAddressOption) => any) | string | undefined;
  limit?: number
  debounce?: number
  count?: number
  addressFields?: UiInputAddressFields
}

const props = withDefaults(defineProps<UiInputAddressProps>(), {
  ...QINPUT_PROPS_DEFAULT,
  limit: 10,
  optionLabel: 'value',
  optionValue: 'value',
  toBound: 'house',
  debounce: 600,
  count: 10,
  addressFields: () => ({})
})

const {} = props
const {fromBound, toBound, limit, rules, addressFields} = toRefs(props)

const emit = defineEmits([...QSELECT_EVENTS, 'select', 'address-select'])

const menuRef = ref<ComponentPublicInstance<typeof QMenu> | null>(null)
const localErrors = ref<any[]>([])
const showOptions = ref(false)
const highlightedIndex = ref(-1)
const selectedHouse = ref()
const loading = ref(false)
const searchedOptions = ref<UiInputAddressOption[]>([])

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
} = useFormField(
    props,
    emit,
    computed(() => [
      //  (val: any) => (toBound.value === 'country' || toBound.value === 'city') || !!addressFields.value?.street || 'Не выбрана улица',
      (val: any) => (toBound.value !== 'house') || !!addressFields.value?.house || 'Не выбран дом',
    ])
)


const inputValue = ref(value.value)

watch(value, () => {
  if (inputValue.value !== value.value)
    inputValue.value = value.value
  onInput(value.value)
})

const inputProps = computed(() => {
  const {
    modelValue,
    debounce,
    limit,
    optionLabel,
    optionValue,
    toBound,
    count,
    ...rest
  } = propsFilter<UiInputAddressProps>(props)
  return rest
})

const onInputBlur = debounce((e: any) => {
  if (!showOptions.value) {
    inputValue.value = value.value
    onBlur(e)
    showOptions.value = false
  }
}, 200)

const onInputKeydown = debounce(() => {
  highlightedIndex.value = -1
  onSearch(inputValue.value)
}, props.debounce)

const onSearch = async (query: string) => {

  loading.value = true

  let options = []

  const requestParams: DaDataAddressRequestParams = {
    from_bound: fromBound.value,
    to_bound: toBound.value,
    count: limit.value,
  }

  let suggestions = await getAddressSuggestions(query, requestParams)

  options = suggestions.map<UiInputAddressOption>((suggestion) => {
    return {
      label: getSuggestionData(suggestion, props.optionLabel),
      value: getSuggestionData(suggestion, props.optionValue),
      data: suggestion.data
    }
  })

  searchedOptions.value = options
  loading.value = false
  showOptions.value = true
}

const onSelect = (option: UiInputAddressOption) => {

  if (!option)
    return;

  onInputBlur.cancel()

  const optionValue = option.value
  const optionLabel = option.label
  const optionData = option.data

  inputValue.value = optionValue
  value.value = optionValue

  nextTick(() => {
    showOptions.value = false
  })

  emit('update:modelValue', optionValue)
  emit('address-select', optionValue, optionData)
}

const getSuggestionData = (data: any, field: any) => {
  if (typeof field === 'string') {
    return data[field] || data.data[field]
  } else if (typeof field === 'function') {
    return field(data)
  }
}

function onFocus() {
  //showOptions.value = true
}

function setHouse(val: string) {
  selectedHouse.value = val
}

defineComponent({})

defineExpose({
  inputRef,
  setHouse,
  clearExternalErrors: () => {
    inputRef.value?.resetValidation?.();
    localErrors.value = [];
  }
});
</script>
<style lang="scss" scoped>
.q-toggle-wrapper {

}

.controls {
  position: relative;
}
</style>
