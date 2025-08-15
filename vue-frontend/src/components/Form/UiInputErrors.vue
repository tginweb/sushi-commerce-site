<template>

    <div v-if="errorsComp.length" class="form-errors">
        <ul class="form-errors-list" v-if="!errorsInline">
            <li v-for="(error, index) in errorsComp" :key="index" class="form-error-item">
                {{ error.message }}
            </li>
        </ul>
        <div v-else class="form-error-item">
            {{errorsComp.map(error => error.message).join(', ')}}
        </div>
    </div>
</template>

<script setup lang="ts">
import { ErrorInterface } from '@/gql/gen';
import { UseErrorManager } from '@/core/error/useErrorManager';
import { computed, toRefs } from 'vue';

export type InputErrorItem = Partial<ErrorInterface> | string

type ComputedError = {
    type: string,
    message: string
}

export type InputErrorsProps = {
    fieldName?: string
    errorsInline?: boolean
    errors?: Partial<ErrorInterface>[]
    error?: Partial<ErrorInterface> | boolean
    errorsManager?: UseErrorManager
}

const props = withDefaults(defineProps<InputErrorsProps>(), {
    errorsInline: false
})

const { errorsInline, fieldName } = props
const { errors, error, errorsManager } = toRefs(props)

const prepareError = (value: InputErrorItem | boolean): ComputedError | undefined => {
    if (value) {
        if (typeof value === 'string') {
            return {
                type: 'error',
                message: value
            }
        } else if (typeof value === 'object') {
            const message = value.fieldMessage || value.message
            if (message) {
                return {
                    type: value.type || 'error',
                    message,
                }
            }
        }
    }
}

const errorsComp = computed<ComputedError[]>(() => {
    let result: ComputedError[] = []
    if (errorsManager.value && fieldName) {
        result = errorsManager.value.groups.value.fieldName?.[fieldName]?.map((item => prepareError(item))).filter(item => !!item) || []
    } else if (errors.value && errors.value.length) {
        result = errors.value.map((item => prepareError(item))).filter(item => !!item)
    } else if (error.value) {
        const errorPrepared = prepareError(error.value)
        if (errorPrepared) {
            result.push(errorPrepared)
        }
    }
    return result
})

</script>

<style lang="scss" scoped>
.form-errors {
    margin: 4px 0 0 0;
}

.form-errors-list {
    list-style: none;
    padding: 0;
    margin: 0 0 0 0;
}

.form-error-item {
    color: var(--q-negative);
    font-size: 12px;
    line-height: 1.2;
    margin-bottom: 2px;

    &:last-child {
        margin-bottom: 0;
    }
}
</style>
