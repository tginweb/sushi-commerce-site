<template>
  <div>
    <Alert
        v-bind="itemProps"
        v-for="(message, index) in items"
        :key="index"
        :message="message"
    />
  </div>
</template>

<script lang="ts" setup>

import {defineProps, toRefs, withDefaults} from "vue";
import {Message} from "@/gql/gen";
import Alert, {AlertPropsBase} from "@/components/Alert/Alert.vue";

const props = withDefaults(defineProps<{
  items: Partial<Message>[],
  itemProps?: AlertPropsBase,
}>(), {
  items: () => ([])
})

const {itemProps} = props
const {items} = toRefs(props)

</script>

<style lang="scss" scoped>

</style>
