<template>
  <q-banner
      v-bind="bindContainer"
      class="alert"
      inline-actions
  >
    <template v-slot:avatar>
      <q-icon
          v-bind="bindIcon"
          size="20px"
      />
    </template>
    <template v-if="Array.isArray(message.messages) && message.messages.length">
      <ul class="message-rows q-ma-none">
        <li
            v-for="(message, index) of message.message"
            :key="index"
            class="q-mb-sm"
        >
          {{ message }}
        </li>
      </ul>
    </template>
    <template v-else>
      <div class="s-font-sm">
        {{ message.message }}
      </div>
    </template>

    <template v-slot:action v-if="actions.length">
      <q-btn
          v-bind="action"
          unelevated
          size="md"
          v-for="(action, index) of actions"
          :key="index"
      />
    </template>

  </q-banner>
</template>

<script lang="ts" setup>

import {ICONS} from '@/assets/icons';
import {computed, defineProps, toRefs, withDefaults} from "vue";
import {Message, MessageTypeEnum} from "@/gql/gen";
import {QBannerProps, QBtnProps, QIcon, QIconProps} from "quasar";
import {ComponentProps} from "@/core/vue/types";
import {menuItemToButton} from "@/core/util/project/menuItemToButton";

const schema: Record<MessageTypeEnum, {
  color: string,
  class: string,
  classOutline: string,
  icon: any,
  iconColor: string,
  iconColorOutline: string
}> = {
  info: {
    color: 'green',
    class: 'text-white bg-green',
    classOutline: 'text-green',
    icon: ICONS.fasInfoCircle,
    iconColor: 'white',
    iconColorOutline: 'green',
  },
  error: {
    color: 'red',
    class: 'text-white bg-red',
    classOutline: 'text-red',
    icon: ICONS.fasExclamationTriangle,
    iconColor: 'white',
    iconColorOutline: 'red',
  },
  success: {
    color: 'green',
    class: 'text-white bg-green',
    classOutline: 'text-green',
    icon: ICONS.farCheckCircle,
    iconColor: 'white',
    iconColorOutline: 'green',
  },
  warning: {
    color: 'yellow-8',
    class: 'text-white bg-yellow-8',
    classOutline: 'text-yellow-8',
    icon: ICONS.fasInfoCircle,
    iconColor: 'white',
    iconColorOutline: 'yellow-8',
  }
}

export type AlertPropsBase = QBannerProps & {
  message: Partial<Message>,
  outlined?: boolean,
  flat?: boolean,
  duration?: number | null,
  actionProps?: QBtnProps
}

export type AlertProps = AlertPropsBase & {
  message: Partial<Message>,
}

const props = withDefaults(defineProps<AlertProps>(), {
  actionProps: () => ({})
})

const {flat, outlined, actionProps} = props
const {message} = toRefs(props)

const typeInfo = computed(() => {
  return schema[message.value?.type || 'info']
})

const actions = computed(() => {
  return (message.value.actions || []).map(action => {
    return {
      dense: true,
      class: 'q-px-md',
      ...((res: QBtnProps) => {
        if (outlined) {
          res.color = 'primary-light'
        } else {
          res.color = "white"
          res.outline = true
        }
        return res
      })({}),
      ...actionProps,
      ...menuItemToButton(action),
    }
  })
})

const bindContainer = computed(() => {
  let res: ComponentProps<QBannerProps> = {
    class: {}
  }
  if (flat) {
    res.class[typeInfo.value.classOutline] = true
  } else if (outlined) {
    res.class['--outlined'] = true
    res.class[typeInfo.value.classOutline] = true
  } else {
    res.class['--outlined'] = true
    res.class[typeInfo.value.class] = true
  }
  return res
})

const bindIcon = computed(() => {
  let res: QIconProps = {
    name: typeInfo.value.icon,
    color: outlined || flat ? typeInfo.value.iconColorOutline : undefined,
  }
  return res
})

</script>

<style lang="scss" scoped>
.alert {

  border-radius: 10px;
  min-height: auto;

  :deep() {
    .q-banner__avatar {
      align-self: center;
    }

    .q-banner__content {
      font-size: 17px;
    }
  }

  &.--outlined {
    border: 1px solid currentColor;
  }

  .message-rows {
    li:last-child {
      margin-bottom: 0;
    }
  }
}
</style>
