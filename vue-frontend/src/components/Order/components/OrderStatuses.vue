<template>
  <div class="order-statuses">
    <div
        class="c-status-steps flex no-wrap justify-between"
        style="z-index: 1;position: relative;"
    >
      <template
          v-for="(status, index) of statuses"
          :key="status.ID || index"
      >
        <div
            :class="{
              'c-status-step flex items-center': true,
              '--ready': status.READY,
              '--current': status.CURRENT,
              [itemClass]: true
            }"
        >
          <div :title="status.NAME as string" class="text-center">
            <q-icon
                v-if="resolveIconFirst(status.ICON, 'order_status_' + status.ID)"
                :class="{
                  [iconClass]: true,
                }"
                :name="resolveIconFirst(status.ICON, 'order_status_' + status.ID)"
                :size="iconSize"
                class="c-icon q-mx-auto q-my-auto"
            />
            <div
                class="c-label q-px-sm q-mt-sm leading-e4"
                :class="{
                  [labelSizeClass]: true
                }"
                style="max-width:110px;"
            >
              <span v-if="showLabels === true || (showLabels === 'current' && status.CURRENT)">
                {{ status.NAME }}
              </span>
            </div>
          </div>
        </div>
        <div
            v-if="index < statuses.length - 1"
            class="col-grow flex "
            style="position: relative"
        >
          <div
              class="c-status__line "
              style="width:100%; background-color: #eee;"
          />
        </div>
      </template>
    </div>
  </div>
</template>

<script lang="ts" setup>

import {defineProps, toRefs, withDefaults} from "vue";
import {Order} from "@/gql/gen";
import {OrderViewStatus} from "@/components/Order/hooks";
import {resolveIconFirst} from "@/assets/icons";

const props = withDefaults(defineProps<{
  order: Order;
  statuses: OrderViewStatus[];
  polling?: boolean;
  iconSize?: any,
  iconClass?: any,
  showLabels?: boolean | 'current',
  itemClass?: any,
  labelSizeClass?: any,
}>(), {
  statuses: () => ([]),
  polling: false,
  iconSize: null,
  iconClass: null,
  showLabels: true,
  itemClass: 'q-px-md-sm',
  labelSizeClass: 's-font-3xs s-font-md-xs s-font-lg-xs',
})

const {order, statuses} = toRefs(props)

</script>

<style lang="scss" scoped>

.c-status__line {
  position: absolute;
  z-index: 0;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  height: 2px;
}

.c-status-step {
  .c-icon {
    stroke-width: 0 !important;
  }

  &.--current {
    .c-icon {
      stroke: #ce8714 !important;
      fill: #ce8714 !important;
    }

    .c-label {
      font-weight: bold;
      color: #ce8714 !important;
    }
  }

  &.--ready {
    .c-icon {
      stroke: #037c03;
      fill: #037c03;
    }

    .c-label {
      color: #037c03;
    }
  }

  &:not(.--ready) {
    .c-icon {
      stroke: #AAAAAA;
      fill: #AAAAAA;
    }
  }
}

.c-stepper {
  :deep() {
    .q-stepper__tab {
      padding: 12px 0;
    }

    .q-stepper__step-content {
      display: none;
    }
  }
}
</style>
