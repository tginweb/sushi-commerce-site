<template>
  <StackItemModal
      :dialog="{}"
      :sheet="{
        blocking: true,
        canSwipeClose: false,
        expandOnContentDrag: false
      }"
      breakpoint="md"
      :actions="actions"
      :actionClose="true"
      v-bind="stackComponent.bind"
      v-on="listeners"
      title="Время"
  >

    <br>
    {{datetime && datetime.format('DD.MM.YYYY HH:mm')}}
    <div class="row q-col-gutter-md items-center1">
      <div class="col-15 col-sm-12">
        <q-select
            v-model="dateValue"
            :options="dateOptions"
            emit-value
            label="Дата"
            outlined
        />
      </div>
      <div class="col-9 col-sm-12">
        <q-input
            v-model="timeValue"
            type="time"
            label="Время"
            no-error-icon
            outlined
        />
      </div>
    </div>

  </StackItemModal>
</template>
<script setup lang="ts">
import {useStackComponent} from "@/packages/stack-router/hooks/useStackComponent";
import {useVorderDialog, VOrderDialogProps, vorderDialogPropsDefault} from "./hook";
import {computed, ref} from "vue";
import {MenuItem} from "@/gql/gen";
import dayjs from "dayjs";
import {useVorderStore} from "@/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import parseTime from "@/core/util/date/parseTime";

const vorderStore = useVorderStore()
const {attrs} = storeToRefs(vorderStore)

const props = withDefaults(defineProps<VOrderDialogProps>(), {
  ...vorderDialogPropsDefault
})

const stackComponent = useStackComponent(props)

const {code} = props

const {
  listeners
} = useVorderDialog(props)

const actions = computed<Partial<MenuItem>[]>(() => {
  return [
    {
      label: 'Find time'
    }
  ]
})

const dateValue = ref(attrs.value.DATE || dayjs().format('DD.MM.YYYY'))
const timeValue = ref(attrs.value.TIME || dayjs().format('HH:mm'))

const dateOptions = ref([
  '30.07.2025',
  '01.08.2025',
  '02.08.2025',
  '03.08.2025',
  '04.08.2025',
])

const datetimeStr = computed(() => {
  if (dateValue.value && timeValue.value) {
    return dateValue.value + ' ' + timeValue.value
  }
})

const datetime = computed(() => {
  if (datetimeStr.value) {
    return parseTime(datetimeStr.value, 'datetime', 'dayjs')
  }
})

const rules = computed(() => [
  (v) => dateValue.value
])



</script>
<style>

</style>
