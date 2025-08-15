<template>
  <div>

    <div class="q-gutter-xs">
      <div class="c-title" v-if="title" v-html="title"></div>

      <ul class="c-messages" v-if="messages && messages.length">
        <li
          class="c-messages-item"
          v-for="(message, index) in messages"
          :key="index">
          <div class="c-message" v-html="message"></div>
        </li>
      </ul>
      <div class="c-message" v-else v-html="message"></div>

    </div>

    <div v-if="actions.length" class="q-mt-sm">
      <div class="flex q-gutter-sm">
        <div
          class="c-action s-font-sm"
          v-for="(action, index) in actions"
          :key="action.id || index"
          @click.stop.prevent="onActionClick(action)">
          {{ action.label }}
        </div>
      </div>
    </div>

  </div>
</template>
<script setup lang="ts">

import { MenuItem } from "@/gql/gen";
import { useAction } from "@/core/store/action";
import { toRefs } from "vue";

const { runAction } = useAction()

const props = withDefaults(defineProps<{
  title?: string,
  message?: string,
  messages?: string[],
  actions?: Partial<MenuItem>[]
}>(), {
  actions: () => []
})

const {
  title,
  message,
  messages,
  actions
} = toRefs(props)

const emits = defineEmits<{
  closeToast: []
}>()

const onActionClick = (action: Partial<MenuItem>) => {
  runAction(action)
  emits('closeToast')
}

</script>
<style lang="scss" scoped>
.c-title {
  font-weight: 600;
}

.c-action {
  padding: 3px 6px;
  border: 1px solid #FFFFFF;
  border-radius: 6px;
  cursor: pointer;
}

.c-messages {
  margin-bottom: 0;
  padding-bottom: 0;
}

.c-messages-item {}
</style>
