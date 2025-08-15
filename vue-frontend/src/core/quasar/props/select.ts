import field from './field'

export default {
  ...field,
  hint: {},
  required: {default: false},
  options: {default: () => []},
}
