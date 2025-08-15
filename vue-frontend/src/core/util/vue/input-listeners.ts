import type { FocusEvent, KeyboardEvent, ClipboardEvent } from 'vue'

export function createInputListeners(emit: any) {
  return {
    blur: (e: FocusEvent) => emit('blur', e),
    focus: (e: FocusEvent) => emit('focus', e),
    keydown: (e: KeyboardEvent) => emit('keydown', e),
    keyup: (e: KeyboardEvent) => emit('keyup', e),
    paste: (e: ClipboardEvent) => emit('paste', e),
    'keydown.enter': (e: KeyboardEvent) => emit('enter', e),
  }
} 