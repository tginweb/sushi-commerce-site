<template>
  <StackItemModal
      :dialog="{
      }"
      :actions="actions"
      title="Уведомления"
      v-bind="bind"
  >
    <div class="text-right" style="position: sticky; top: -3px; z-index: 200;">
      <q-checkbox
          v-if="haveUnreaded"
          label="только непрочитанные"
          v-model="filterUnreaded"
          size="xs"
      />
    </div>

    <template v-if="noticesGrouped.length">
      <div
          :key="group.title"
          v-for="group of noticesGrouped"
          class="q-mb-md"
      >
        <div style="position: sticky; top: 0px;z-index: 100" class="bg-white q-px-none q-pb-xs row items-center">
          <div class="text-weight-medium text-secondary s-font-md">
            {{ group.title }}
          </div>
        </div>
        <div class="q-mt-md">
          <NoticeTeaser
              v-for="notice of group.items"
              :key="notice.id"
              :notice="notice"
              class="q-mb-md"
          />
        </div>
      </div>
    </template>
    <div v-else class="text-center">
      у вас нет уведомлений
    </div>


  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {computed, ref} from "vue";
import {useNoticeStore} from "@/modules/notice/store/notice";
import {storeToRefs} from "pinia";
import {useQuasar} from "quasar";
import {MenuItem} from "@/gql/gen";
import timestampToFormat from "@/core/util/date/timestampToFormat";
import NoticeTeaser from "./components/NoticeTeaser.vue";

const $q = useQuasar()
const noticeStore = useNoticeStore()
const {notices, readedIds, lastShownId} = storeToRefs(noticeStore)

const props = withDefaults(defineProps<StackComponentProps & {}>(), {})

const {bind} = useStackComponent<StackItemModalState>(props)

const filterUnreaded = ref(false)

const haveUnreaded = computed(() => {
  return !!notices.value.find(notice => !notice.isReaded)
})

const actions = computed(() => {
  const res: Partial<MenuItem>[] = []
  if (haveUnreaded.value) {
    res.push({
      label: 'пометить все как прочитанные',
      flat: true,
      dense: true,
      color: 'primary',
      onClick: () => {

      }
    })
  }
  if ($q.screen.lt.md) {
    res.push({
      label: 'Закрыть',
      outline: true,
      color: 'primary',
      onClick: () => {

      }
    })
  }
  return res
})

const noticesGrouped = computed(() => {
  return Object.values(
      notices.value
          .filter((item) => {
            return !filterUnreaded.value || !item.isReaded
          })
          .reduce<Record<string, {
            title: string,
            timestamp: any,
            items: any[]
          }>>((map, item) => {
            const itemDate = timestampToFormat(item.createdAt, 'DD MMMM YYYY')
            if (itemDate) {
              if (!map[itemDate]) {
                map[itemDate] = {
                  title: itemDate,
                  timestamp: item.createdAt,
                  items: []
                }
              }
              map[itemDate]?.items.unshift(item)
            }
            return map
          }, {})
  ).map(item => ({
    ...item,
    items: item.items.sort((a, b) => b.id - a.id)
  })).sort((a, b) => b.timestamp - a.timestamp)
})

</script>
<style>

</style>
