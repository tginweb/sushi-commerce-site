<template>

  <div
      class="c-section q-validation-component"
      @mouseenter="hover=true"
      @mouseleave="hover=false"
      @click="onClick"
      v-ripple
      :class="{
          ['section-' + code]: true
        }"
  >
    <q-field
        ref="field"
        v-model="model"
        :error="haveErrors"
        :loading="loading"
        class="c-field"

        hide-bottom-space
        lazy-rules="ondemand"
        no-error-icon
        stack-label
        borderless
        standout="dd"
    >
      <template v-if="icon" v-slot:prepend>
        <div class="c-col flex" :class="colClass" :style="colStyle">
          <q-icon
              :name="icon"
              class="c-icon"
              size="20px"
              style=""
              :color="haveErrors ? 'red' : 'primary'"
          />
        </div>
      </template>

      <template v-slot:control>

        <div class="c-col full-width" :class="colClass" :style="colStyle">

          <div class="flex no-wrap" style="padding-bottom: 3px" v-if="!hideLabel">
            <div class="flex no-wrap -items-center q-pr-sm">
              <div class="q-mr-auto s-font-xs text-grey-8 c-label">{{ label }}</div>
            </div>
            <div
                v-if="!loading"
                class="flex items-center q-ml-auto"
            >
              <slot name="label-side" v-if="$slots['label-side']"/>
              <q-icon
                  :name="ICONS.chevronRight"
                  class="q-ml-sm gt-sm"
                  size="14px"
                  v-else-if="!loading"
                  style="color:#9B9895;"
              />
            </div>
          </div>

          <div class="self-center full-width flex no-wrap-md q-gutter-y-sm items-center">

            <div class="q-mr-auto s-font-xs">

              <slot v-bind="{validated, haveErrors}"/>

            </div>

            <slot
                v-if="!loading && $slots.side"
                class="s-font-sm"
                name="side"
            />

          </div>

          <div v-if="hint" class="s-font-xs text-grey-6 q-mt-xs">
            {{ hint }}
          </div>

          <div v-if="haveErrors && !loading" class="flex q-gutter-sm q-pt-sm">
            <div class="s-font-2xs text-red-7 q-mr-auto">
              {{ errorMessage }}
            </div>
            <slot
                v-if="$slots.errorSide"
                name="errorSide"
            />
          </div>

        </div>

      </template>
      <template v-slot:append>
        <slot
            v-if="$slots.append"
            name="append"
        />
      </template>
      <template v-slot:loading>
        <q-spinner class="q-mt-sm q-pt-xs"/>
      </template>
    </q-field>
  </div>
</template>

<script setup lang="ts">

import {ICONS} from "@/assets/icons";
import {computed, nextTick, ref, toRefs} from "vue";
import validateRulesFirst from "@/core/util/validate/validateRulesFirst";
import haveErrorsNative from "@/core/util/validate/haveErrors";
import errorToString from "@/core/util/validate/errorToString";
import {VOrderSectionProps} from "@/components/VOrder/Section/hook";
import {useFormChild} from "quasar";

const model = defineModel<any>({required: false})

const props = withDefaults(defineProps<VOrderSectionProps>(), {
  rules: () => [],
  loading: false,
})

const emit = defineEmits<{
  click: []
}>()

const {code, icon, colClass, colStyle} = props

const {rules} = toRefs(props)

const onClick = () => {
  emit('click')
}

const rulesComp = computed(() => rules.value)
const errorMessage = ref<string | null>()
const validated = ref<boolean | null>()
const haveErrors = computed(() => !!errorMessage.value)
const hover = ref(false)

const getErrorMessage = () => {
  const error = validateRulesFirst(model.value, rulesComp.value)
  return haveErrorsNative(error) ? errorToString(error) : null
}

const validate = async () => {
  errorMessage.value = getErrorMessage()

  console.log('errorMessage.value', errorMessage.value)
  await nextTick()
  validated.value = true
  return !errorMessage.value
}

const validateRun = async (onlyFilled = false, revalidate = false) => {

  if (!onlyFilled || model.value) {

    if (revalidate) {
      //this.$refs.field.resetValidation()
      errorMessage.value = null
    }

    await nextTick()
    errorMessage.value = getErrorMessage()
    await nextTick()

    //this.$refs.field.validate()
    validated.value = true

  } else {
    if (onlyFilled) {
      errorMessage.value = null
      //this.$refs.field.resetValidation()
    }
  }
}

const resetValidation = () => {
  errorMessage.value = null
}


useFormChild({
  validate,
  resetValidation,
})

</script>
<style lang="scss" scoped>

.c-section {
  position: relative;
}

.c-field {
  height: 100%;
  cursor: pointer;

  :deep() {

    .q-field__marginal {
      min-height: auto;
      height: auto;
      align-items: flex-start;
    }

    .q-field__control {
      padding-left: 10px;
      padding-right: 10px;
      //height: 100%;
      min-height: auto;
      background-color: transparent;
    }

    .q-field__native,
    .q-field__prefix,
    .q-field__suffix {
      padding-top: 1px;
      padding-bottom: 2px;
      min-height: auto;
    }

    .q-field__append {
      padding-left: 0;
    }

    .q-field__native {
      align-items: start;
      color: #000000;
      line-height: 1.1 !important;
      font-size: 15px !important;
    }

    .title {
      display: inline-block;
      color: #888;
    }

  }

  .c-icon {
    color: $primary;
  }

  .c-label {
    font-weight: 600;
  }
}


.c-col {
  min-height: 40px;
  padding-top: 12px;
  padding-bottom: 10px;
}
</style>



