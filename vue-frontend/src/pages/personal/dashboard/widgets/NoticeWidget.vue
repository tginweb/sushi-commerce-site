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

            <div class="q-gutter-y-sm q-pt-xs">

              <div
                  v-for="notice in userNotices"
                  :key="notice.id"
                  class="flex no-wrap q-gutter-x-md cursor-pointer"
                  @click.stop="onItemClick(notice)"
              >
                <div>
                  <q-icon :name="ICONS.circle" size="8px" color="primary" style="margin-top: -8px;"/>
                </div>
                <div class="col-shrink">
                  <div
                      class="s-font-xs"
                      :class="{
                        'text-weight-bold': !notice.isReaded
                      }"
                  >
                    {{ notice.title || notice.message }}
                  </div>
                </div>
                <div class="col-auto q-ml-auto text-right">
                  <div class="s-font-3xs text-grey-8 text-weight-bold" style="white-space: nowrap;">
                    {{ timestampToFormat(notice.createdAt, 'DD MMM HH:mm') }}
                  </div>
                </div>
              </div>

            </div>

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
import {useRouter} from "vue-router";
import {ICONS} from "@/assets/icons";
import {timestampToFormat} from "@/core/util/date";


const props = withDefaults(defineProps<PersonalWidgetProps>(), personalWidgetPropsDefault)

const {} = props

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

const userNotices = computed(() => [])

const onItemClick = (notice) => {


}

</script>
<style lang="scss" scoped>

</style>
