import type {ErrorInterface} from "@/gql/gen/schema";
import {ValidateRule} from "@/core/types";
import createRules from "@/core/util/validate/createRules";
import errorToString from "@/core/util/validate/errorToString";
import haveErrors from "@/core/util/validate/haveErrors";
import validateRulesFirst from "@/core/util/validate/validateRulesFirst";
import {QFieldProps, QInput} from "quasar";
import {computed, Ref, ref, toRefs, watch} from "vue";
import type {InputErrorsProps} from "./UiInputErrors.vue";

export type UiFieldProps = Omit<QFieldProps, "rules"> &
    InputErrorsProps & {
    rules?: (ValidateRule | string)[];
    fieldName?: string;
    required?: boolean;
    modelValue?: any;
    lazyRules?: boolean;
};

export function useFormField<T extends any = any>(
    props: UiFieldProps,
    emit?: (event: string, ...args: any[]) => void,
    rulesAddition?: Ref<any[]>
) {
    const {
        errors,
        error,
        errorsManager,
        rules,
        required,
        modelValue
    } = toRefs(props)

    const {fieldName, errorsInline} = props

    const inputRef = ref<QInput | null>(null)

    const value = computed<T>({
        get: () => modelValue?.value,
        set: (val: T) => {
            if (emit) {
                emit("update:modelValue", val);
                emit('input', val);
            }
        },
    })

    const rulesComputed = computed(() => {
        const res = [
            ...(rules?.value || []),
            ...(rulesAddition?.value || [])
        ];
        if (required?.value) {
            res.push("required");
        }
        return createRules.apply(null, res);
    })

    // --- LAZY RULES SUPPORT ---
    const rulesResult = ref<true | string>(true)

    function runRules(val: any) {
        const error = validateRulesFirst(val, rulesComputed.value);
        rulesResult.value = haveErrors(error)
            ? (errorToString(error) as string)
            : true;
    }

    function onInput(val: any) {
        if (errorsManager?.value && fieldName)
            errorsManager.value?.onFieldChange(fieldName);

        if (!props.lazyRules) {
            runRules(val);
        } else if (props.lazyRules === true) {
            rulesResult.value = true;
            inputRef.value?.resetValidation();
            //console.log('dd', inputRef.value)
        }
    }

    function onBlur(val: any) {
        if (props.lazyRules === true) runRules(modelValue?.value);
    }

    function validate(val: any = value.value) {
        runRules(val);
    }

    // Автоматически валидируем при изменении rules/modelValue если lazyRules=false
    watch(
        value,
        (val) => {
            if (!props.lazyRules) runRules(val);
        },
        {
            //immediate: true
        }
    );

    // --- END LAZY RULES ---

    const errorsComputed = computed(() => {
        if (rulesResult.value !== true) {
            return [
                {
                    message: rulesResult.value as string,
                },
            ] as Partial<ErrorInterface>[];
        } else if (errorsManager?.value && fieldName) {
            return errorsManager.value.groups.value.fieldName?.[fieldName] || [];
        } else if (errors?.value && errors.value.length) {
            return errors.value;
        } else if (error?.value) {
            return [error?.value];
        }
        return [];
    });

    const errorsCount = computed(() => errorsComputed.value.length);

    const onUpdate = () => {

    };

    const bindInputErrors = computed(() => {
        return {
            errors: errorsComputed?.value,
            error: error?.value,
            errorsInline,
            fieldName,
        };
    });

    // Метод для сброса ошибок QInput и errorsManager
    function resetValidation() {
        // Сбросить ошибки QInput
        inputRef.value?.resetValidation?.();
        // Сбросить ошибки errorsManager
        if (errorsManager?.value && fieldName) {
            errorsManager.value.clearByGroup?.("fieldName", fieldName);
        }
    }

    return {
        onUpdate,
        bindInputErrors,
        rulesComputed,
        rulesResult,
        value,
        fieldName,
        resetValidation,
        inputRef,
        onInput,
        onBlur,
        validate,
        errorsComputed,
    };
}

// --- Базовые события для всех полей (QFIELD) ---
export const QFIELD_EVENTS = [
    "update:modelValue", // camelCase!
    "focus",
    "blur",
    "clear",
    "click",
    "input",
] as const;

export type QFieldEvent = (typeof QFIELD_EVENTS)[number];

export interface QFieldEventPayloadMap {
    "update:modelValue": [any];
    input: [any];
    focus: [FocusEvent];
    blur: [FocusEvent];
    click: [FocusEvent];
    clear: [];
}

export const QFIELD_SCHEME = {
    events: QFIELD_EVENTS,
    eventMap: {} as QFieldEventPayloadMap,
};

// --- QInput Events ---
export const QINPUT_EXTRA_EVENTS = [
    "keyup",
    "keydown",
    "keypress",
    "paste",
] as const;

export type QInputExtraEvent = (typeof QINPUT_EXTRA_EVENTS)[number];

export interface QInputExtraEventPayloadMap {
    keyup: [KeyboardEvent];
    keydown: [KeyboardEvent];
    keypress: [KeyboardEvent];
    paste: [ClipboardEvent];
    click: [MouseEvent];
    clear: [];
}

export type QInputEvent = QFieldEvent | QInputExtraEvent;
export type QInputEventPayloadMap = QFieldEventPayloadMap &
    QInputExtraEventPayloadMap;

export const QINPUT_EVENTS = [
    ...QFIELD_EVENTS,
    ...QINPUT_EXTRA_EVENTS,
] as const;

export const QINPUT_SCHEME = {
    events: QINPUT_EVENTS,
    eventMap: {} as QInputEventPayloadMap,
};

// --- QSelect Events ---
export const QSELECT_EXTRA_EVENTS = [
    "input-value",
    "popup-show",
    "popup-hide",
    "add",
    "remove",
    "new-value",
    "keyup",
    "keydown",
    "keypress",
    "paste",
] as const;

export type QSelectExtraEvent = (typeof QSELECT_EXTRA_EVENTS)[number];

export interface QSelectExtraEventPayloadMap {
    "input-value": [any];
    filter: [string, any];
    "filter-abort": [];
    "popup-show": [];
    "popup-hide": [];
    add: [any];
    remove: [any];
    "new-value": [any];
    keyup: [KeyboardEvent];
    keydown: [KeyboardEvent];
    keypress: [KeyboardEvent];
    paste: [ClipboardEvent];
}

export type QSelectEvent = QFieldEvent | QSelectExtraEvent;
export type QSelectEventPayloadMap = QFieldEventPayloadMap &
    QSelectExtraEventPayloadMap;

export const QSELECT_EVENTS = [
    ...QFIELD_EVENTS,
    ...QSELECT_EXTRA_EVENTS,
] as const;
export const QSELECT_SCHEME = {
    events: QSELECT_EVENTS,
    eventMap: {} as QSelectEventPayloadMap,
};

// --- Универсальный useQuasarListeners ---
export function useQuasarListeners<
    E extends string,
    M extends Record<E, any[]>
>(
    emit: (event: E, ...args: M[E]) => void,
    scheme: { events: readonly E[] },
    middleware?: Partial<{ [K in E]: (...args: M[K]) => void }>
) {
    const listeners = Object.fromEntries(
        scheme.events.map((event) => [
            event,
            (...args: M[typeof event]) => {
                (middleware?.[event] as any)?.(...args);

                if (event !== "update:modelValue") {
                    emit(event, ...args);
                }
            },
        ])
    );
    return listeners as Record<E, (...args: M[E]) => void>;
}

export function propsFilter<T extends Record<string, any>>(props: Partial<T>): Partial<T> {
    //@ts-ignore
    return Object.fromEntries(Object.entries(props).filter((item) => {
        const [propName] = item
        if (!propName.match(/^on[A-Z]/)) {
            return true
        }
        return false
    }))
}

export const QINPUT_PROPS_DEFAULT = {
    hideBottomSpace: true,
};
