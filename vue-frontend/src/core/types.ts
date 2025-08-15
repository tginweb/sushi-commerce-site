import {MenuItem, Message} from "@/gql/gen";

export type TScalar = number | string


// System
export type AnyObj = Record<string, unknown>
export type PureFunc = () => void
export type PureFuncAsync = () => Promise<void>
export type PureFuncArg<T> = (value?: T) => void
export type Nullable<T> = T | null | undefined | void
export type Maybe<T> = T | null | undefined | void
export type Optional<T> = T | null | undefined

export type Arrayable<T> = T[] | T

export type PartialRecord<K extends keyof any, T> = {
    [P in K]?: T;
}

export type Redirect = {
    type?: 'router' | 'native'
    path: any
}

export type PropsWithKey<P = unknown> = P & { key?: any }

export type PartialWithKey<P = unknown> = Partial<P & { key?: any }>

export type LocalMessage = Message & {
    onPress?: ({id, hide}: { id: any, hide: () => void }) => void
}


export type ValidateMode = 'all' | 'first' | undefined | null

export type ValidateRuleResult = boolean | string | null | undefined | ValidateError

export type ValidateRule = ((v: any, ctx?: any) => ValidateRuleResult) | Validator

export type ValidateRules = ValidateRule[]

export type ValidateError = Message

export type ValidateErrors = ValidateError[]

export type ValidateResult = ValidateErrors | true | null

export type Validator = ValidatorEmail | ValidatorPhone | ValidatorNumber | ValidatorRequired | ValidatorString

export type TValidatorCommon = {
    message?: string
}

export type ValidatorEmail = TValidatorCommon & {
    type: 'email'
}

export type ValidatorPhone = TValidatorCommon & {
    type: 'phone'
}

export type ValidatorNumber = TValidatorCommon & {
    type: 'number'
    min?: number
    max?: number
}

export type ValidatorRequired = TValidatorCommon & {
    type: 'required'
}

export type ValidatorString = TValidatorCommon & {
    type: 'string'
    min?: number
    max?: number
    regexp?: string
}

export type ID = string | number

export type TaskStatus = 'idle' | 'processing' | 'resolved' | 'rejected' | 'failed'

export type StoreConfig = {
    callback: () => any
    store?: any
    refs?: any
    name?: string
    boot?: boolean
    rootGetters?: boolean
}

// Утилита для генерации комбинаторного типа
export type GenerateCombinedType<
    T extends Record<string, string>,
    Separator extends string = '.'
> = {
    [K in keyof T]: `${K & string}${Separator}${T[K] & string}`
}[keyof T]

