// symbols.ts
import { InjectionKey } from 'vue';

export const VOrderValidateInject: InjectionKey<() => Promise<boolean>> = Symbol('vorder:validate');
