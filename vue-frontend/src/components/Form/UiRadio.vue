<template>
    <div class="q-radio-wrapper">
        <q-radio
            v-bind="props as any"
            :error="errorsCount > 0"
            @update:model-value="onUpdate">
            <template v-for="(_, name) in $slots" #[name]="slotData">
                <slot :name="name" v-bind="slotData" />
            </template>
            <template #error>
                <InputErrors
                    :errors="errors"
                    :error="error"
                    :field-name="fieldName"
                    :errors-inline="errorsInline"
                    :errors-manager="errorsManager" />
            </template>
        </q-radio>
    </div>
</template>

<script setup lang="ts">
import type { ErrorInterface } from '@/gql/gen';
import { QRadio, QRadioProps } from 'quasar';
import InputErrors, { InputErrorsProps } from './UiInputErrors.vue';


defineOptions({
    inheritAttrs: false
})
</script>

<style lang="scss" scoped>
.q-radio-wrapper {
    width: 100%;
}
</style>
