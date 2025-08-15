<!--suppress CssUnresolvedCustomProperty -->
<template>
  <BottomSheetNative
      ref="bottomSheet"
      @opened="onOpened"
      @closed="onClosed"
      v-bind="nativeProps"
  >
    <template #header v-if="headerEnable">

      <div class="c-header flex g-gutter-md no-wrap">
        <div class="c-header__title">
          {{ title }}
        </div>
        <div class="c-header__side q-ml-auto">
          <div v-if="closePosition === 'header-right'">
            <q-btn
                :icon="ICONS.close"
                dense
                outline
                @click="modelValue = false"
                size="14px"
            />
          </div>
        </div>
      </div>
    </template>
    <template #footer v-if="footerEnable">

      <div v-if="actionsComp.length" class="row q-col-gutter-sm">
        <div
            :class="[action.width ? action.width : 'col-24']"
            v-for="(action, index) in actionsComp"
            :key="action.id || index"
        >
          <q-btn
              color="primary"
              dense
              unelevated
              class="full-width"
              v-bind="menuItemToButton(action)"
          />
        </div>
      </div>

    </template>
    <slot/>
  </BottomSheetNative>

</template>

<script setup lang="ts">
import {computed, onMounted, ref, toRefs, watch} from "vue";
import BottomSheetNative from "@douxcode/vue-spring-bottom-sheet";
import {ICONS} from "@/assets/icons";
import {MenuItem} from "@/gql/gen";
import {menuItemToButton} from "@/core/util/project/menuItemToButton";
import {StackItemEmits} from "@/packages/stack-router/types";


export interface BottomSheetNativeProps {
  duration?: number | null;
  snapPoints?: number[] | null;
  defaultSnapPoint?: null;
  blocking?: boolean | null;
  canSwipeClose?: boolean | null;
  canBackdropClose?: boolean | null;
  expandOnContentDrag?: boolean | null;
}

export interface BottomSheetProps extends BottomSheetNativeProps {
  title?: string
  footer?: string
  closable?: boolean
  closePosition?: 'outside-right' | 'header-right'
  actions?: Partial<MenuItem>[] | undefined
  actionClose?: boolean
  headerHide?: boolean
  containerClass?: any
  bodyMaxHeight?: any
  width?: string
}

const props = withDefaults(defineProps<BottomSheetProps>(), {
  duration: null,
  snapPoints: null,
  defaultSnapPoint: null,
  blocking: null,
  canSwipeClose: null,
  canBackdropClose: null,
  expandOnContentDrag: null,
  closable: true,
  closePosition: 'header-right',
  actionClose: true,
  actions: () => []
})

const {closable, closePosition, actionClose} = props
const {title, footer, actions} = toRefs(props)

const modelValue = defineModel<boolean>({default: false})

const bottomSheet = ref<InstanceType<typeof BottomSheetNative>>()

const emits = defineEmits<StackItemEmits>()

const nativeProps = computed<BottomSheetNativeProps>(() => {
  let result = {
    duration: props.duration,
    snapPoints: props.snapPoints,
    defaultSnapPoint: props.defaultSnapPoint,
    blocking: props.blocking,
    canSwipeClose: props.canSwipeClose,
    canBackdropClose: props.canBackdropClose,
    expandOnContentDrag: props.expandOnContentDrag
  } as BottomSheetNativeProps

  for (const [propName, propValue] of Object.entries(result)) {
    if (typeof propValue === 'undefined' || propValue === null)
        //@ts-ignore
      delete result[propName]
  }
  return result
})

const headerEnable = computed(() =>
    !!title ||
    closable && closePosition === 'header-right'
)

const actionsComp = computed(() => {
  const result = [...actions.value]
  if (actionClose) {
    result.push({
      label: 'Закрыть',
      outline: true,
      onClick: close
    })
  }
  return result
})

const footerEnable = computed(() => footer || actionsComp.value.length)


onMounted(() => {
  if (modelValue.value) {
    open()
  }
})

watch(modelValue, () => {
  if (modelValue.value) {
    console.log('watch', modelValue.value)
    open()
  } else {
    close()
  }
})

const open = () => {
  bottomSheet.value?.open()
}

const close = () => {
  bottomSheet.value?.close()
}

const onOpened = () => {

}

const onClosed = () => {
  //console.log('onClosed onClosed')
  modelValue.value = false
}

const onClick = () => {
  console.log('onClick onClick')
}


</script>

<style>

[data-vsbs-container] {
  position: fixed;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
  z-index: 9999;
  visibility: visible;
}

[data-vsbs-backdrop] {
  background-color: var(--vsbs-backdrop-bg, rgba(0, 0, 0, 0.5));
  inset: 0;
  pointer-events: auto;
  position: fixed;
  user-select: none;
  will-change: opacity;
  z-index: 1;
}

[data-vsbs-shadow='true'] {
  box-shadow: 0 -5px 60px 0 var(--vsbs-shadow-color, rgba(89, 89, 89, 0.2));
}

[data-vsbs-sheet] {
  background-color: var(--vsbs-background, #fff);
  border-top-left-radius: var(--vsbs-border-radius, 16px);
  border-top-right-radius: var(--vsbs-border-radius, 16px);
  bottom: 0;
  display: flex;
  flex-direction: column;
  left: 0;
  margin-left: auto;
  margin-right: auto;
  max-height: inherit;
  max-width: var(--vsbs-max-width, 640px);
  pointer-events: all;
  position: fixed;
  right: 0;
  transition: v-bind(transitionVisibility);
  visibility: hidden;
  width: 100%;
  will-change: height;
  z-index: 2;
}

[data-vsbs-sheet-show='true'] {
  visibility: visible;
}

[data-vsbs-header] {
  box-shadow: 0 1px 0 var(--vsbs-border-color, rgba(46, 59, 66, 0.125));
  flex-shrink: 0;
  padding: 14px 14px 6px 14px;
  user-select: none;
  z-index: 3;
}

[data-vsbs-header]:before {
  background-color: var(--vsbs-handle-background, rgba(0, 0, 0, 0.28));
  border-radius: 2px;
  content: '';
  display: block;
  height: 4px;
  left: 50%;
  position: absolute;
  top: 8px;
  transform: translateX(-50%);
  width: 36px;
}

[data-vsbs-header]:empty {
  box-shadow: none;
  padding: 12px var(--vsbs-padding-x, 16px) 8px;
}

[data-vsbs-footer] {
  box-shadow: 0 -1px 0 var(--vsbs-border-color, rgba(46, 59, 66, 0.125));
  flex-grow: 0;
  flex-shrink: 0;
  padding: 16px var(--vsbs-padding-x, 16px);
  user-select: none;
}

[data-vsbs-footer]:empty {
  display: none;
}

[data-vsbs-scroll] {
  flex-grow: 1;
  overflow-y: auto;
  overscroll-behavior: contain;
}

[data-vsbs-content-wrapper] {
  height: 100%;
}

[data-vsbs-content] {
  display: grid;
  padding: 1vh var(--vsbs-padding-x, 16px);
  user-select: none;
}

.fade-enter-active,
.fade-leave-active {
  transition: var(--5872cb4c)
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.c-header {
  .c-header__title {
    font-weight: 500;
  }

  .c-header__side {

  }
}
</style>
