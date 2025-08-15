<template>
  <Wrapper
      :rules="rulesDelivery"
      @click="onClick"
      :icon="ICONS.farCommentDots"
      label="Комментарий"
      code="comment"
  >
    <div class="c-view s-font-2xs">
      <div>
        {{ attrs.COMMENT }}
      </div>
    </div>
  </Wrapper>
</template>

<script lang="ts" setup>

import CommentDialog from "../Dialog/Comment.vue";
import {useVorderSection, VOrderSectionProps, vorderSectionPropsDefault} from "./hook";
import Wrapper from "./Wrapper.vue";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {storeToRefs} from "pinia";
import {useStackRouter} from "@/packages/stack-router/hooks/useStackRouter";
import {ICONS} from "@/assets/icons";

const vorderStore = useVorderStore()
const {rulesDelivery} = storeToRefs(vorderStore)

const {attrs} = storeToRefs(vorderStore)

const model = defineModel()

const props = withDefaults(defineProps<VOrderSectionProps>(), {
  ...vorderSectionPropsDefault
})

const {} = useVorderSection(props)

const stackRouter = useStackRouter()

const onClick = () => {
  stackRouter.getStack()?.push(CommentDialog, {role: 'vorder'})
}

</script>

<style scoped>

.c-view {
  display: table;
  table-layout: fixed;
  width: 100%;

  div {
    display: table-cell;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }
}
</style>
