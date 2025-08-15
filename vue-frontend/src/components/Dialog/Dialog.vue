<template>
  <q-dialog
      v-bind="nativeProps"
      v-model="model"
      no-route-dismiss>
    <div
        :style="innerStyle"
        :class="containerClass"
        class="c-container">


      <template v-if="!slots.content">
        <q-btn
            :icon="ICONS.close"
            dense
            text-color="gray-8"
            round
            color="white"
            @click="close"
            size="14px"
            v-if="closePosition === 'outside-right'"
            class="c-close-outside --right"/>
        <Header
            v-if="headerEnable"
            :title="title"
            :closable="closable"
            :onClose="close"
            :onBack="onHeaderBack"/>

        <q-btn
            style="position: absolute; left: -100px; top: calc(50% - 9px)"
            :icon="ICONS.chevronLeft"
            color="primary"
            size="18px"
            v-if="nav === 'prev' || nav === 'all'"
            @click="onNavPrev"/>

        <q-btn
            style="position: absolute; right: -100px; top: calc(50% - 9px)"
            :icon="ICONS.chevronRight"
            color="primary"
            size="18px"
            v-if="nav === 'next' || nav === 'all'"
            @click="onNavNext"/>

        <Body :maxHeight="bodyMaxHeightComputed">
        <slot/>
        </Body>
        <Footer
            v-if="footerEnable"
            :actions="actionsComp"
            :container-class="footerClass"/>
      </template>
      <template v-else>
        <slot
            name="content"
            v-bind="bindProps"
            :headerListeners="headerEnable"
        />
      </template>

    </div>
  </q-dialog>
</template>

<script setup lang="ts">
import {ICONS} from "@/assets/icons";
import {MenuItem} from "@/gql/gen";
import {QDialogProps} from "quasar";
import {computed, provide, toRefs, useSlots} from "vue";
import Body from "./Body.vue";
import Footer from "./Footer.vue";
import Header from "./Header.vue";
import useDialog, {DialogElementName, DialogElementState} from "@/components/Dialog/hooks";

const slots = useSlots()

type DialogNativeProps = Omit<QDialogProps, 'modelValue'>

export interface DialogProps extends DialogNativeProps {
  title?: string
  footer?: string
  onHeaderBack?: () => void
  closable?: boolean
  closePosition?: 'outside-right' | 'header-right'
  actions?: Partial<MenuItem>[] | undefined
  actionClose?: boolean
  width?: string
  containerClass?: any
  headerHide?: boolean
  maxHeight?: any
  bodyMaxHeight?: any
  footerClass?: any
  nav?: 'all' | 'prev' | 'next',
  onNavPrev?: () => void,
  onNavNext?: () => void,
}

const props = withDefaults(defineProps<DialogProps>(), {
  width: '520px',
  closable: true,
  closePosition: 'header-right',
  actionClose: true,
  containerClass: 'q-py-md q-px-md',
  headerHide: false,
  maxHeight: '90vh',
  actions: () => [],
})

const {
  closable,
  closePosition,
  actionClose,
  width,
  headerHide,
  maxHeight,
  bodyMaxHeight,
  footerClass,
  onNavPrev,
  onNavNext
} = props

const {
  title,
  footer,
  actions,
  onHeaderBack,
  nav
} = toRefs(props)

const model = defineModel<boolean>({default: false})

const nativeProps = computed<DialogNativeProps>(() => {
  const {
    title,
    footer,
    closePosition,
    closable,
    actions,
    actionClose,
    ...result
  } = props
  for (const [propName, propValue] of Object.entries(result)) {
    if (typeof propValue === 'undefined' || propValue === null)
        //@ts-ignore
      delete result[propName]
  }
  return result as DialogNativeProps
})


const actionsComp = computed(() => {
  const result = [...actions.value]
  if (actionClose) {
    if (actions.value.length) {
      result.push({
        label: 'Закрыть',
        outline: true,
        onClick: close
      })
    } else {
      result.push({
        label: 'Закрыть',
        onClick: close
      })
    }
  }
  return result
})

const headerEnable = computed(() => !headerHide && (!!title || closable || onHeaderBack || closePosition === 'header-right'))
const footerEnable = computed(() => actionsComp.value?.length)

const innerStyle = computed(() => {
  return {
    width: width,
    maxWidth: '80vw',
    maxHeight: maxHeight
  }
})

const close = () => {
  model.value = false
}


const {updateElement, elements} = useDialog()

const bodyMaxHeightComputed = computed(() => {
  if (bodyMaxHeight) {
    return bodyMaxHeight
  } else {
    const usedHeight = elements.header.height + elements.footer.height + 32
    return 'calc(' + maxHeight + ' - ' + usedHeight + 'px)'
  }
})

const bindPropsCommon = computed(() => {
  return {
    closable,
    title,
    onClose: close,
    actions: actionsComp,
    onBack: onHeaderBack.value,
  }
})

const bindProps = computed(() => {
  return {
    header: {
      ...bindPropsCommon.value,
    },
    body: {
      ...bindPropsCommon.value,
      maxHeight: bodyMaxHeightComputed.value,
      elements
    },
    footer: {
      ...bindPropsCommon.value,
    },
  }
})

provide('dialogElementUpdate', (element: DialogElementName, info: DialogElementState) => {
  updateElement(element, info)
})

</script>

<style scoped lang="scss">
.c-container {
  overflow: visible;
  background-color: #FFFFFF;
  position: relative;
}

.c-close-outside {
  &.--right {
    right: -35px;
    top: -35px;
    position: absolute;
    z-index: 1;
  }
}
</style>
