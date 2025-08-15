<template>
  <div class="autocomplete-textarea">
    <!-- Основной элемент ввода -->
    <q-input
        ref="inputRef"
        v-model="inputValue"
        v-bind="inputProps"
        @update:model-value="handleInput"
        @focus="showOptions = true"
        @keydown="handleKeyDown"
        @blur="handleBlur"
    >
      <!-- Передаем все слоты в QInput -->
      <template v-for="(_, slot) in $slots" #[slot]="scope">
        <slot :name="slot" v-bind="scope || {}" />
      </template>
    </q-input>

    <!-- Выпадающее меню с опциями -->
    <q-menu
        ref="menuRef"
        v-model="showOptions"
        :anchor1="inputRef?.$el"
        self="top left"
        fit
        no-focus
        no-refocus
        persistent
    >
      <q-list dense>
        <!-- Состояние загрузки -->
        <q-item v-if="loading">
          <q-item-section>
            <q-skeleton type="text" />
          </q-item-section>
        </q-item>

        <!-- Сообщение при отсутствии опций -->
        <q-item v-else-if="!filteredOptions.length">
          <q-item-section>
            <q-item-label class="text-grey">
              {{ noOptionsLabel }}
            </q-item-label>
          </q-item-section>
        </q-item>

        <!-- Отображение опций -->
        <q-item
            v-for="(option, index) in filteredOptions"
            v-else
            :key="index"
            clickable
            :active="index === highlightedIndex"
            @click="selectOption(option)"
            @mouseenter="highlightedIndex = index"
        >
          <slot name="option" v-bind="option">
            <q-item-section>
              <q-item-label>{{ getOptionLabel(option) }}</q-item-label>
            </q-item-section>
          </slot>
        </q-item>
      </q-list>
    </q-menu>
  </div>
</template>

<script setup lang="ts">
import {
  ref,
  computed,
  watch,
  nextTick,
  useAttrs,
  type PropType,
  type ComponentPublicInstance
} from 'vue';
import { QInput, QMenu, QList, QItem, QItemSection, QSkeleton } from 'quasar';

// Типы данных
type Option = string | Record<string, any>;
type FilterFn = (inputValue: string) => void;

// Пропсы компонента
const props = defineProps({
  modelValue: {
    type: [String, Number, Object, Array] as PropType<any>,
    default: null
  },
  options: {
    type: Array as PropType<Option[]>,
    default: () => []
  },
  optionLabel: {
    type: Function as PropType<(option: Option) => string>,
    default: (option: Option) =>
        typeof option === 'string' ? option : option.label || String(option)
  },
  optionValue: {
    type: Function as PropType<(option: Option) => any>,
    default: (option: Option) => option
  },
  loading: Boolean,
  noOptionsLabel: {
    type: String,
    default: 'Нет совпадений'
  },
  debounce: {
    type: Number,
    default: 300
  }
});

// События компонента
const emit = defineEmits([
  'update:modelValue',
  'filter',
  'selected'
]);

// Реактивные переменные
const inputValue = ref('');
const showOptions = ref(false);
const highlightedIndex = ref(-1);
const inputRef = ref<ComponentPublicInstance<typeof QInput> | null>(null);
const menuRef = ref<ComponentPublicInstance<typeof QMenu> | null>(null);
let debounceTimer: ReturnType<typeof setTimeout> | null = null;

// Получаем атрибуты для QInput
const attrs = useAttrs();
const inputProps = computed(() => ({
  ...attrs,
  // Переопределяем некоторые поведения по умолчанию
  autocomplete: 'off',
  spellcheck: false,
  // Убираем стандартное поведение Quasar для выбора
  'use-input': true,
  'hide-selected': true,
  'fill-input': false
}));

// Отфильтрованные опции
const filteredOptions = computed(() => props.options);

// Метод для получения метки опции
const getOptionLabel = (option: Option) => props.optionLabel(option);

// Обработчик ввода данных
const handleInput = (value: string) => {
  inputValue.value = value;
  showOptions.value = true;
  highlightedIndex.value = -1;

  // Дебаунс для фильтрации
  if (debounceTimer) clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    emit('filter', value);
  }, props.debounce);
};

// Выбор опции
const selectOption = (option: Option) => {
  const value = props.optionValue(option);
  const label = props.optionLabel(option);

  inputValue.value = label;
  emit('update:modelValue', value);
  emit('selected', value);
  showOptions.value = false;
};

// Обработчик потери фокуса
const handleBlur = () => {
  setTimeout(() => {
    if (!menuRef.value?.$el.contains(document.activeElement)) {
      showOptions.value = false;
    }
  }, 100);
};

// Обработчик нажатия клавиш
const handleKeyDown = (e: KeyboardEvent) => {
  if (!showOptions.value) return;

  switch (e.key) {
    case 'ArrowDown':
      e.preventDefault();
      highlightedIndex.value = Math.min(
          highlightedIndex.value + 1,
          filteredOptions.value.length - 1
      );
      scrollToHighlighted();
      break;

    case 'ArrowUp':
      e.preventDefault();
      highlightedIndex.value = Math.max(highlightedIndex.value - 1, -1);
      scrollToHighlighted();
      break;

    case 'Enter':
      if (highlightedIndex.value >= 0) {
        e.preventDefault();
        selectOption(filteredOptions.value[highlightedIndex.value]);
      }
      break;

    case 'Escape':
      showOptions.value = false;
      break;
  }
};

// Прокрутка к выделенному элементу
const scrollToHighlighted = async () => {
  await nextTick();
  const items = menuRef.value?.$el.querySelectorAll('.q-item');
  if (items && items[highlightedIndex.value]) {
    items[highlightedIndex.value].scrollIntoView({
      block: 'nearest',
      behavior: 'smooth'
    });
  }
};

// Следим за внешними изменениями modelValue
watch(() => props.modelValue, (newValue) => {
  if (!newValue) {
    inputValue.value = '';
    return;
  }

  // Находим соответствующую опцию
  const option = props.options.find(
      opt => props.optionValue(opt) === newValue
  );

  if (option) {
    inputValue.value = props.optionLabel(option);
  } else {
    inputValue.value = String(newValue);
  }
}, { immediate: true });

// Автоматически показываем опции при фокусе
watch(() => inputRef.value?.isFocused, (focused) => {
  if (focused && inputValue.value) {
    showOptions.value = true;
  }
});
</script>

<style scoped>
.autocomplete-textarea {
  position: relative;
  width: 100%;
}

.q-menu {
  z-index: 3000;
}

.q-item--active {
  background-color: #f0f0f0;
}
</style>
