<template>
  <div
      class="profile-widget"
      v-bind="bind"
      @click="onClick"
  >
    <div v-bind="bindInner">

      <div class="c-main full-width" @click="onClick">

        <div class="c-header row no-wrap items-center1 q-col-gutter-md q-col-gutter-md-lg ">
          <div class="col-grow">

            <div class="flex q-mb-sm items-center">
              <div class="c-title s-font-md s-font-md-lg s-font-lg-lg  text-weight-bold q-mr-auto">
                Профиль
              </div>
              <div>
                <q-btn label="изменить" v-bind="headerLinkBind"/>
              </div>
            </div>

            <div class="flex no-wrap -items-center">
              <div class="c-media col-auto q-mr-md">
                <q-circular-progress
                    :size="mediaSizeComp"
                    :thickness="mediaBorderThickness"
                    :value="100"
                    color="grey-7"
                    show-value
                >
                  <q-icon
                      :name="ICONS.person"
                      color="grey-7"
                      size="30px"
                  />
                </q-circular-progress>
              </div>
              <div class="col-shrink">
                <div class="q-mb-sm">
                  <div class="text-weight-bold">
                    <span>Алексей</span>
                    <span
                        class="s-link-wrapper gt-md q-ml-sm"
                        @click.stop="router.push({name: 'user:logout'})"
                    >
                        <q-icon :name="ICONS.logout"/>
                    </span>
                  </div>
                </div>
                <div class="q-gutter-xs q-gutter-md-xs">
                  <div
                      v-for="line of lines"
                      :key="line.value"
                      class="c-field s-font-xs s-font-md-sm"
                  >
                        <span>
                          <span v-if="line.label" class="text-grey-6 text-weight-medium">
                            {{ line.label }}:
                          </span>
                          <span>
                            {{ line.value }}
                          </span>
                        </span>
                  </div>
                </div>
              </div>
            </div>

            <template v-if="user && !user.PROFILE_GIFT_USED">

              <div
                  class="q-mt-md s-font-sm"
                  v-if="user.PROFILE_FILLED"
              >
                <q-btn
                    color="primary"
                    size="md"
                    @click.stop="onGiftApply"
                    :loading="requestState.process"
                    class="full-width leading-e6"
                >
                  Получить подарок &nbsp;<b>300 бонусов</b>&nbsp; за заполнение профиля
                </q-btn>
              </div>
              <div
                  class="q-mt-sm s-font-2xs s-font-md-xs s-font-lg-sm text-weight-bold leading-e5 flex q-gutter-x-md no-wrap items-center"
                  style="color: #14678c;"
                  v-else
              >
                <div class="col-shrink">
                  Заполните личный кабинет и получите в подарок
                  <span class="text-weight-bold" style="white-space: nowrap;">300 бонусов!</span>
                </div>
                <div class="col-auto">
                  <q-icon size="30px" :name="ICONS.bonus"/>
                </div>
              </div>
            </template>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">

import {computed, toRefs, useSlots} from "vue";
import {useQuasar} from "quasar";
import {
  PersonalWidgetProps,
  personalWidgetPropsDefault,
  usePersonalProfile
} from "@/pages/personal/dashboard/widgets/hooks";
import {ICONS} from "@/assets/icons";
import {useRouter} from "vue-router";

const props = withDefaults(defineProps<PersonalWidgetProps>(), personalWidgetPropsDefault)

const {
  mediaBorderThickness,
} = props

const {
  title,
  subtitle,
  fields,
  content
} = toRefs(props)

const slots = useSlots()
const $q = useQuasar()
const router = useRouter()

const {
  bind,
  bindInner,
  mediaSizeComp,
  onClick,
  headerLinkBind
} = usePersonalProfile(props)

const valuePhone = computed(() => {
  return '79501102996'
})

const valueEmail = computed(() => {
  return 'xanderweb@gmail.com'
})

const valueBirthday = computed(() => {
  return '23.03.1984'
})

const lines = computed(() => {

  const res = []

  if (valuePhone.value)
    res.push({value: valuePhone.value})

  if (valueEmail.value)
    res.push({label: 'E-mail', value: valueEmail.value})

  if (valueBirthday.value)
    res.push({label: 'День рождения', value: valueBirthday.value})

  return res
})

const onGiftApply = () => {

}

const requestState = {
  process: false
}

const user = {
  PROFILE_GIFT_USED: false,
  PROFILE_FILLED: false
}

</script>
<style lang="scss" scoped>

.c-field {
  max-width: 100%;
  :deep() {
    > span {
      display: block;
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
    }
  }
}

</style>
