<template>
  <nav  class="menu-container">

    <q-resize-observer @resize="updateMenuDebounced"/>

    <div
        class="menu-list"
        :class="menuClass"
        :style="{
            gap: gap ? gap + 'px' : 0
          }"
    >
      <div
          v-for="item in visibleItems"
          :key="item.id"
          class="menu-item"
          :style="{
              visibility: item.visible ? 'visible' : 'hidden'
            }"
          :ref="(el: any) => setRef(el, item)"
          @mouseenter="onItemMouseover(item)"
          @mouseleave="onItemMouseout(item)"
      >
        <slot
            name="menu-item"
            :item="item"
            :setValue="setValue"
        />
      </div>
      <div class="menu-hidden">
        <div
            ref="moreButtonRef"
            class="menu-more"
            :style="{
                visibility: moreButtonShow ? 'visible' : 'hidden'
              }"
        >
          <slot
              name="more-button"
              :items="hiddenItems"
              :activeId="modelValue"
              :setValue="setValue"
          />
        </div>
        <div
            class="menu-list"
            :class="menuClass"
            :style="{
                gap: gap ? gap + 'px' : 0
              }"
        >
          <div
              v-for="item in hiddenItems"
              :key="item.id"
              class="menu-item"
              :style="{
                  visibility: item.visible ? 'visible' : 'hidden'
                }"
              :ref="(el: any) => setRef(el, item)"
              @mouseenter="onItemMouseover(item)"
              @mouseleave="onItemMouseout(item)"
          >
            <slot
                name="menu-item"
                :item="item"
                :setValue="setValue"
            />
          </div>
        </div>
      </div>
    </div>

  </nav>
</template>

<script lang="ts" setup>
import {computed, nextTick, onBeforeUnmount, onMounted, ref, toRefs, watch} from 'vue';
import {useDebounceFn} from "@vueuse/core";

export type HMenuItem = {
  id?: string | number | null | undefined
  value?: string | number | null | undefined
  children?: HMenuItem[]
  [key: string]: any
}

type MenuItemWrap = {
  data: HMenuItem
  id: string
  visible: boolean
  active: boolean
}

type Props = {
  items: HMenuItem[]
  limit?: number
  menuClass?: string
  gap?: number
}

const props = withDefaults(defineProps<Props>(), {
  items: () => [],
  limit: 100,
  gap: 10,
})

const {limit, menuClass, gap} = props
const {items} = toRefs(props)

const itemsLimit = ref<number>(limit)

const refsById = ref<Record<string, HTMLElement>>({})
const menuRef = ref<HTMLElement | null>(null);

const modelValue = defineModel<string | null>()

const itemsComp = computed<MenuItemWrap[]>(() => items.value.map((item, index) => {
  const itemId = (item.value || item.id || index).toString()
  return {
    data: item,
    id: itemId,
    visible: index < itemsLimit.value,
    active: modelValue.value === itemId
  }
}))

const visibleItems = computed<MenuItemWrap[]>(() => itemsComp.value.filter(item => item.visible))
const hiddenItems = computed<MenuItemWrap[]>(() => itemsComp.value.filter(item => !item.visible))

const moreButtonRef = ref<HTMLElement | null>(null)
const moreButtonShow = ref<boolean>(false)

const setRef = (el: HTMLElement, item: MenuItemWrap) => {
  //console.log(item, el)
  if (el)
    refsById.value[item.id] = el
}

const hoverItem = ref<HMenuItem | null>()

const onItemMouseover = (item: MenuItemWrap) => {
  hoverItem.value = item
  //console.log('in')
}

const onItemMouseout = (item: MenuItemWrap) => {
  hoverItem.value = null
  //console.log('out')
}

const setValue = (id: string | null) => {
  modelValue.value = id
}

const updateMenu = () => {

  if (!menuRef.value) return;

  let availableWidth = menuRef.value.clientWidth

  if (moreButtonRef.value) {
    availableWidth -= moreButtonRef.value.offsetWidth + gap;
  }

  let count = 0
  let limited = false

  for (let i = 0; i < itemsComp.value.length; i++) {
    const item = itemsComp.value[i]
    if (!item)
      continue;
    count = i
    const itemEl = refsById.value[item.id]
    if (itemEl) {
      if (
          (i >= (limit - 1)) ||
          availableWidth < itemEl.offsetWidth
      ) {
        limited = true
        break
      } else {
        availableWidth -= itemEl.offsetWidth + gap
      }
    } else {
    }
  }

  if (limited) {
    moreButtonShow.value = true
    itemsLimit.value = count
  } else {
    moreButtonShow.value = false
    itemsLimit.value = limit
  }
}

const updateMenuDebounced = useDebounceFn(updateMenu, 150)

onMounted(() => {
  nextTick(() => {
    setTimeout(updateMenu, 100)
  })
})

watch(() => props.items, updateMenuDebounced, {deep: true});

</script>

<style scoped lang="scss">
.menu-container {
  display: flex;
  align-items: center;
  white-space: nowrap;
  flex-wrap: nowrap;
  width: 100%;
}

.menu-list {
  display: flex;
  overflow: hidden;
  flex-wrap: nowrap;

  .menu-item {
    flex-shrink: 0;
    position: relative;
  }
}

.menu-hidden {
  display: flex;
  overflow: hidden;
  flex-wrap: nowrap;

  .menu-item {
    flex-shrink: 0;
    position: relative;
  }
}
</style>
