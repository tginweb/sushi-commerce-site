<template>
  <StackItemModal
      :dialog="{
      }"
      :actionClose="true"
      title="Уведомление"
      v-bind="bind"
  >
    <template #default v-if="notice">
      <div class="q-gutter-md">

        <div class="s-font-xs text-grey-6 text-weight-medium text-right">
          {{ timestampToFormat(notice.createdAt, 'DD MMMM YYYY в HH:mm') }}
        </div>

        <div v-if="notice.image" class="text-center q-mb-md">
          <q-icon
              :name="imageResolve(notice.image)"
              size="75px"
              color="primary"
          />
        </div>

        <div class="s-font-md text-weight-bold">
          {{ notice.title }}
        </div>

        <div v-if="notice.body">
          {{ notice.body }}
        </div>

      </div>
    </template>
  </StackItemModal>
</template>
<script setup lang="ts">
import {StackComponentProps, useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {StackItemModalState} from "@/packages/stack-router/types";
import {computed, onMounted, toRefs} from "vue";
import {useNoticeStore} from "@/modules/notice/store/notice";
import {storeToRefs} from "pinia";
import timestampToFormat from "@/core/util/date/timestampToFormat";
import {useImager} from "@/core/store/imager";

const noticeStore = useNoticeStore()

const {readNotice} = noticeStore
const {noticeById} = storeToRefs(noticeStore)

const {imageResolve} = useImager()

const props = withDefaults(defineProps<StackComponentProps & {
  id: string
}>(), {})

const {bind} = useStackComponent<StackItemModalState>(props)

const {id} = toRefs(props)

const notice = computed(() => noticeById.value[id.value])

onMounted(() => {
  readNotice(notice.value)
})

</script>
<style>

</style>
