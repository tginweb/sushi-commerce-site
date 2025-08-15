<template>
  <div class="q-slider-wrapper">
    <q-slider
        v-bind="props as any"
        :error="errorsComputed.length > 0"
        @update:model-value="onUpdate">
      <template v-for="(_, name) in $slots" #[name]="slotData">
        <slot :name="name" v-bind="slotData"/>
      </template>
      <template #error>
        <InputErrors v-bind="bindInputErrors as any"/>
      </template>
    </q-slider>
  </div>
</template>

<script setup lang="ts">
import {QSlider} from 'quasar';
import InputErrors from './UiInputErrors.vue';
import {QINPUT_EVENTS, QINPUT_PROPS_DEFAULT, useFormField} from "@/components/Form/hooks";
import {UiInputProps} from "@/components/Form/UiInput.vue";

const props = withDefaults(defineProps<UiInputProps>(), {
  ...QINPUT_PROPS_DEFAULT,
})

const emit = defineEmits([...QINPUT_EVENTS] as string[])

const {
  errorsComputed,
  onUpdate,
  bindInputErrors,
} = useFormField(props, emit)

defineOptions({
  inheritAttrs: false
})
</script>

<style lang="scss" scoped>
.q-slider-wrapper {
  width: 100%;
}
</style>
