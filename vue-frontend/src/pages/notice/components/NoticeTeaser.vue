<template>
  <div
      class="notice-teaser q-pa-md bg-grey-3 cursor-pointer"
      :class="{
        '--new': !notice.isReaded
      }"
      @click="onClick"
  >

    <div class="flex no-wrap">

      <div
          class="q-mr-sm"
          :class="{
            'text-weight-bold': !notice.isReaded
          }"
      >
        <div>
          {{ notice.title || notice.message }}
        </div>

        <div v-if="actions.length" class="flex q-gutter-sm">
          <div v-for="(action,index) in actions">
            <q-btn
                :key="index"
                :label="action.label"
                flat
                color="primary"
                size="14px"
                dense
                @click.stop="onActionClick(action)"
            />
          </div>
        </div>
      </div>

      <div class="q-ml-auto">

        <div
            class="text-grey-8 s-font-xs s-font-md-sm text-weight-bold"
            v-if="!notice.isReaded"
        >
          {{ date }}
        </div>
        <div v-else class="text-grey-8 s-font-xs s-font-md-sm">
          {{ date }}
        </div>

      </div>

    </div>

  </div>
</template>

<script setup lang="ts">

import {ClientNotice} from "@/gql/gen";
import {computed, toRefs} from "vue";
import timestampToFormat from "@/core/util/date/timestampToFormat";
import toArray from "@/core/util/toArray";
import {useNoticeStore} from "@/modules/notice/store/notice";

const {showNoticeDialog} = useNoticeStore()

const props = withDefaults(defineProps<{
  notice: ClientNotice
}>(), {})

const {notice} = toRefs(props)

const date = computed(() => timestampToFormat(notice.value.createdAt, 'time'))

const actions = computed(() => {
  const res = notice.value.actionItems.filter(item => {
    return toArray(item.roles).includes('list')
  })
  return res
})

const onActionClick = (item) => {

}

const onClick = () => {
  showNoticeDialog({id: notice.value.id})
}

</script>

<style lang="scss" scoped>

.notice-teaser {
  border-radius: 16px;

  &.--new {

  }
}

</style>
