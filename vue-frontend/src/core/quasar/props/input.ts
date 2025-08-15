import field from './field'

export default {
  ...field,
  hint: {},
  required: {default: false},
  unmaskedValue: {default: false},
  fillMask: {default: false},
  autofocus: {default: false},
  inputClass: {default: null},
  noErrorIcon: {default: false},
  hideBottomSpace: {default: false},
}
