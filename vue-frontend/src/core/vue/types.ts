import {DefineProps} from "@vue/runtime-core";
import {LooseRequired} from "@vue/shared";

export type ComponentHtmlProps = {
    id?: string
    style?: any
    class?: any
}

export type ComponentProps<T> = T & ComponentHtmlProps

export type BooleanKey<T, K extends keyof T = keyof T> = K extends any ? [T[K]] extends [boolean | undefined] ? K : never : never;

export type DefinedProps<T> = DefineProps<LooseRequired<T>, BooleanKey<T>>

export type ReactiveFunction<TParam> = () => TParam
