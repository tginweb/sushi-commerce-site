<template>
  <div
      class="profile-widget"
      v-bind="bind"
      @click="onClick"
  >
    <div v-bind="bindInner">

      <div class="c-main">

        <div class="c-header row no-wrap items-center q-col-gutter-md q-col-gutter-md-lg ">

          <div
              v-if="title || slots.title"
              :class="{

              }"
              class="c-title col-shrink q-mr-auto"
          >

            <slot v-if="slots.title" name="title"/>

            <template v-else>

              <div
                  v-if="title"
                  :class="{
                   'cursor-pointer': headerLink
                  }"
                  class="c-title s-font-md s-font-md-lg s-font-lg-lg  text-weight-bold"
                  @click="onHeaderClick"
              >
                {{ title }}
              </div>

              <div v-if="subtitle" class="c-subtitle s-font-sm s-font-md-md -text-weight-bold">
                <router-link v-if="subtitleLink" :to="subtitleLink" class="s-link">{{ subtitle }}</router-link>
                <span v-else>{{ subtitle }}</span>
              </div>

            </template>

          </div>

          <div
              v-if="mediaTextExist || mediaIcon || slots.icon"
              :class="{
                'order-first': mediaLeft
              }"
              class="c-media col-auto"
          >

            <slot v-if="slots.media" name="media"/>

            <component
                :is="$q.screen.gt.md ? 'q-circular-progress' : 'div'"
                v-else
                :color="mediaBorderColor"
                :size="mediaSizeComp"
                :thickness="mediaBorderThickness"
                :value="100"
                show-value
                track-color="primary"
            >
              <div
                  v-if="mediaTextExist"
                  :class="{
                    [mediaTextClass]: true,
                    ['text-'+mediaColor]: true
                  }"
              >
                {{ mediaText }}
              </div>

              <q-icon
                  v-if="mediaIcon"
                  :color="mediaColor"
                  :name="mediaIcon"
                  :size="mediaSizeComp"
              />

            </component>

          </div>

          <div v-if="headerLink">
            <q-btn v-bind="headerLinkBind"/>
          </div>

        </div>

        <div v-if="content || $slots.content" class="c-content q-mt-sm">

          <slot v-if="slots.content" name="content"/>

          <div v-else class="s-font-md">
            {{ content }}
          </div>

        </div>

      </div>

      <div v-if="fields || $slots.bottomText || bottomText" class="c-bottom q-mt-auto q-pt-sm">

        <DataFields
            v-if="fields"
            :value="fields"
            breakpoint="sm"
            label-width="63px"
        />

        <slot v-if="slots.bottomText" name="bottom"/>

        <div v-else-if="bottomText" class="c-bottom-text s-font-lg text-grey-7">
          {{ bottomText }}
        </div>

      </div>

    </div>

  </div>
</template>
<script setup lang="ts">
import {toRefs, useSlots} from "vue";
import {useQuasar} from "quasar";
import {
  PersonalWidgetProps,
  personalWidgetPropsDefault,
  usePersonalProfile
} from "@/pages/personal/dashboard/widgets/hooks";

const props = withDefaults(defineProps<PersonalWidgetProps>(), personalWidgetPropsDefault)

const {
  colClass,
  theme,
  link,
  headerLink,
  headerLinkLabel,
  fullHeight,
  layoutClass,
  subtitleLink,
  mediaTextClass,
  mediaSize,
  mediaBorderColor,
  mediaBorderThickness,
  mediaLeft,
} = props

const {
  title,
  subtitle,
  mediaText,
  fields,
  content
} = toRefs(props)

const slots = useSlots()
const $q = useQuasar()

const {
  bind,
  bindInner,
  headerLinkBind,
  mediaTextExist,
  mediaSizeComp,
  onClick,
  onHeaderClick,
} = usePersonalProfile(props)

</script>
<style lang="scss" scoped>


</style>
