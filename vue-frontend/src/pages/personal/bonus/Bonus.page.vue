<template>
  <q-page class=" ">

    <ProgressInnerLoading
        :model-value="clientCardRequest.status"
        :reserve-height="true"
    >
      <div class="q-px-md q-px-lg-none q-mt-md q-mt-lg-none relative-position" v-if="clientCard">
        <div class=" q-pb-md">
          <div
              class="c-card q-px-md q-py-md"
              style="max-width: 430px;"
              v-bind="bind"
          >
            <div>Ваш текущий бонусный ранг</div>

            <div class="q-mt-sm s-font-4xl text-weight-boldest q-pb-md">
              {{ clientCard.LEVEL_NAME || 'Ранг 1' }}
            </div>

            <div class="q-mt-sm text-right" v-if="bonusesExpireDays">
              Бонусы действительны до {{ timestampToFormat(clientCard.BONUSES_EXPIRE, 'date') }}
            </div>

            <div class="q-gutter-md">
              <div
                  class="row items-center"
                  v-for="line in lines"
                  :key="line.label"
              >
                <div class="col-24 col-md-auto text-weight-medium s-font-md flex items-center ">
                  {{ line.label }}
                </div>
                <div class="col-24 col-md-auto q-ml-md-auto text-weight-medium">
                  <span class="c-impval s-font-2xl text-bold">{{ line.value }}</span> {{ line.suffix }}
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="q-mt-lg q-mb-xl">
          <q-btn
              color="primary"
              label="подробнее о бонусной программе"
              outline
              to="/bonus/"
              unelevated
          />
        </div>
      </div>
    </ProgressInnerLoading>

  </q-page>
</template>
<script setup lang="ts">
import {computed, onMounted, ref} from "vue";
import {storeToRefs} from "pinia";
import duration from "@/core/util/date/duration";
import timestampToFormat from "@/core/util/date/timestampToFormat";
import ProgressInnerLoading from "@/components/Progress/ProgressInnerLoading.vue";
import {TaskStatus} from "@/core/types";
import {useLoalityStore} from "@/modules/shop/store/loyalty";
import {ComponentHtmlProps} from "@/core/vue/types";

const props = withDefaults(defineProps<{}>(), {})

const loalityStore = useLoalityStore()
const {clientCardRequest} = loalityStore
const {clientCard} = storeToRefs(loalityStore)

const bonusesExpireDays = computed(() => {
  return clientCard.value && clientCard.value.BONUSES_EXPIRE ? duration(Date.now(), parseInt(clientCard.value.BONUSES_EXPIRE)) : 0
})

const lines = computed(() => {
  const res = []

  if (clientCard.value) {
    res.push({
      label: 'Всего',
      value: clientCard.value.BONUSES,
      suffix: 'бонусов'
    })
    if (clientCard.value.BONUSES_PERCENT) {
      res.push({
        label: 'Можно оплатить бонусами',
        value: 'до ' + clientCard.value.BONUSES_PERCENT + '%',
      })
    }
    if (clientCard.value.DIS_SELF_PICKUP) {
      res.push({
        label: 'Скидка на самовывоз',
        value: clientCard.value.DIS_SELF_PICKUP + '%',
      })
    }
    if (clientCard.value.DIS_FIRST_ORDER) {
      res.push({
        label: 'Скидка на первый заказ',
        value: clientCard.value.DIS_FIRST_ORDER + '%',
      })
    }
  }

  return res
})

const bind = computed(() => {
  const res: ComponentHtmlProps = {
    class: {}
  }
  if (clientCard.value)
    res.class['level-' + clientCard.value.LEVEL_CODE] = true
  return res
})

const status = ref<TaskStatus | boolean>('processing')

onMounted(() => {
  console.log('onMounted')
  setTimeout(() => {
    clientCardRequest.query()
  }, 150)
})

</script>
<style>

.c-card {
  border-radius: 10px;
}

.c-card {
  background: rgb(189, 163, 24);
  background: linear-gradient(79deg, rgba(189, 163, 24, 1) 0%, rgba(218, 196, 75, 1) 100%);
  color: #fff;
}

.c-row {
  border-bottom: 3px dashed #eee;
}

.c-impval {

}

</style>
