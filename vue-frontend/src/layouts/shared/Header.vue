<template>

  <q-header class="bg-white text-dark">

    <q-resize-observer @resize="onResize" :debounce="300"/>
    <q-scroll-observer @scroll="onScroll" :debounce="100"/>

    <div
        class="c-desktop text-dark gt-md"
        :class="{
          '--collapsed': headerCollapsed
        }"
    >
      <div
          :class="{
              'q-py-lg-sm': headerCollapsed,
              'q-py-lg-md': !headerCollapsed,
            }"
          class="c-desktop__inner"
      >
        <div class="container">
          <div class="row no-wrap items-center q-col-gutter-lg">
            <div
                class="col-auto"
            >
              <router-link
                  class="c-logo flex no-wrap items-center"
                  to="/"
              >
                <img alt="Суши студио" class="__left" src="/logo-s.png">
                <img alt="Суши студио" class="__right q-ml-md" src="/logo-p-right.png">
              </router-link>
            </div>
            <div class="col-shrink">

              <HMenu
                  :items="catalogMenuItems"
                  :gap="10"
                  v-model="activeSection"
              >
                <template #menu-item="{ item, setValue }">
                  <q-btn
                      :label="item.data.label"
                      :color="item.active ? 'primary':''"
                      :class="[item.active && 'text-weight-bold']"
                      flat
                      dense
                      @click="menuItemClick(item)"
                  />
                </template>
                <template #more-button="{ items, setValue }">
                  <q-btn
                      color="primary"
                      label="Ещё"
                      flat
                      dense
                  >
                    <q-menu>
                      <q-list style="min-width: 100px">
                        <q-item
                            clickable
                            v-close-popup
                            v-for="item in items"
                            :key="item.id"
                            @click="menuItemClick(item)"
                        >
                          <q-item-section>{{ item.data.label }}</q-item-section>
                        </q-item>
                      </q-list>
                    </q-menu>
                  </q-btn>
                </template>
              </HMenu>

            </div>
            <div class="q-ml-auto">
              <CUserMenu/>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
          class="c-mobile text-dark lt-lg"
          :class="{
          '--collapsed': headerCollapsed
        }"
      >
        <div class="c-mobile__top">
          <div class="q-px-md q-py-md">
            <div class="row items-center q-col-gutter-md">
              <div class="col-shrink">
                <img class="c-mobile__logo" src="/logo.png"/>
              </div>
              <div class="col-grow text-center">

                <q-field
                    outlined
                    dense
                    bg-color="white"
                >
                  <template v-slot:control>
                    <div>
                      <div class="s-font-3xs">
                        выбрать способ доставки
                      </div>
                    </div>
                  </template>
                </q-field>

              </div>
              <div class="col-shrink text-right">
                <q-btn
                    :icon="ICONS.humburger"
                    flat
                    dense
                    color="dark"
                    @click="drawerMainModel = !drawerMainModel"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="c-mobile__menu">
          <q-tabs
              v-model="activeSection"
              indicator-color="transparent"
              dense
          >
            <q-tab
                @click="menuItemClick(item)"
                exact
                v-for="item in catalogMenuItems"
                :name="item.id as string"
                :key="item.id as string"
                :label="item.label"
                class="c-mobile__menu__item"
            />
          </q-tabs>
        </div>
      </div>

  </q-header>

</template>

<script setup lang="ts">
import {computed, reactive, ref} from "vue";
import {storeToRefs} from "pinia";
import CUserMenu from "./UserMenu.vue"
import {QResizeObserverProps, QScrollObserverProps, useQuasar} from "quasar";
import {useRouterStore} from "@/core/router/store";
import {useLayoutStore} from "@/stores/layout";
import HMenu, {HMenuItem} from "@/components/HMenu.vue";
import {useCatalogStore} from "@/modules/shop/store/catalog";
import {usePageFrontStore} from "@/stores/page-front";
import {useBus} from "@/core/store/bus";
import {useRoute, useRouter} from "vue-router";
import {ICONS} from "@/assets/icons";

const $q = useQuasar()
const {bus} = useBus()

const layoutStore = useLayoutStore()
const {headerHeight, drawerMainModel} = storeToRefs(layoutStore)

const catalogStore = useCatalogStore()
const {sectionsTree} = storeToRefs(catalogStore)

const pageFrontStore = usePageFrontStore()
const {setActiveSection} = pageFrontStore
const {activeSection} = storeToRefs(pageFrontStore)

const routerStore = useRouterStore()

const stickyEnable = ref(false)

const scroll = reactive({
  directionUp: false,
  position: 0,
  inflexionDelta: 0
})

const catalogMenuItems = computed<HMenuItem[]>(() => sectionsTree.value.map<HMenuItem>(section => ({
  id: section.CODE,
  label: section.NAME
})))


const scrollPositionTop = ref(0)
const scrollDirectionDelta = ref(0)
const scrollDirectionTop = ref(0)

const headerCollapsed = computed(() => {
  return scrollPositionTop.value > 200
})

const onResize: QResizeObserverProps['onResize'] = (info) => {
  //console.log('onResize', info.height)
  headerHeight.value = info.height
}

const onScroll: QScrollObserverProps['onScroll'] = (info) => {
  if (document.body.classList.contains('q-body--prevent-scroll')) {
    return;
  }

  scrollPositionTop.value = info.position.top

  if (info.directionChanged) {
    scrollDirectionDelta.value = 0
    scrollDirectionTop.value = info.position.top
  } else {
    scrollDirectionDelta.value = info.position.top - scrollDirectionTop.value
  }

}

const menuTab = ref()
const menu = computed<any[]>(() => {
  return [
    {
      ID: 1,
      NAME: 'Первый'
    },
    {
      ID: 2,
      NAME: 'Второй'
    },
    {
      ID: 3,
      NAME: 'Третий'
    },
    {
      ID: 4,
      NAME: 'Четвертый'
    },
    {
      ID: 5,
      NAME: 'Пятый'
    },
    {
      ID: 6,
      NAME: 'Шестой'
    },
    {
      ID: 7,
      NAME: 'Седьмой'
    },
    {
      ID: 8,
      NAME: 'Восьмой'
    },
    {
      ID: 9,
      NAME: 'Девятый'
    },
    {
      ID: 10,
      NAME: 'Десятый'
    },
    {
      ID: 11,
      NAME: 'Одиннадцатый'
    },
    {
      ID: 12,
      NAME: 'Двенадцатый'
    },
  ]
})

const route = useRoute()
const router = useRouter()

const menuItemClick = (item: HMenuItem) => {
  if (item.id) {
    if (route.path === '/') {
      setActiveSection(item.id)
    } else {
      router.push({path: '/', hash: '#' + item.id})
    }
  }
}

</script>

<style lang="scss" scoped>

.c-mobile {
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);

  .c-mobile__top {
    top: 0;
    left: 0;
    right: 0;
    border-bottom: 1px solid #EEEEEE;
    transition: all 0.7s ease-in-out;
    height: fit-content;
    max-height: 300px;
    background-color: #FBFBFB;
  }

  &.--collapsed {
    .c-mobile__top {
      overflow: hidden;
      max-height: 0px;
      box-shadow: none;
    }
  }
}

.c-mobile__logo {
  max-height: 30px;
}

.c-mobile__menu {
  border-bottom: 1px solid #DDDDDD;
  background-color: #FFFFFF;
  z-index: 2;
  position: relative;
}

.c-mobile__menu__item {

  padding: 0px 10px;

  :deep() {
    .q-tab__content {
      padding: 9px 0 6px 0;
    }

    .q-tab__label {
      font-size: 15px;
    }
  }

  &:not(.q-tab--inactive) {
    :deep() {
      .q-tab__label {
        background-color: $primary;
        border-radius: 10px;
        padding: 0 10px;
        color: #FFFFFF;
      }
    }
  }
}


.c-desktop {
  background-color: #fff;

  .c-desktop__inner {
    box-shadow: 0 4px 8px -3px rgba(34, 60, 80, 0.2);
    transition: 0.3s all ease-out;
  }

  .c-logo {
    font-size: 0;
    line-height: 0;

    img {
      transition: all 1s;
      height: 50px;
      vertical-align: middle;
    }
  }

  &.--collapsed {
    .c-logo {
      img {
        height: 25px;
      }

      .__right {
        height: 0;
        overflow: hidden;
        margin-left: 0;
      }
    }
  }
}


.icons {
  .icons__item {
    display: inline-block;
    text-decoration: none;
  }
}

.scrollup {
  position: fixed;
  z-index: 10;
  bottom: 30px;
  right: 30px;
}

.c-logo {

  display: block;
  line-height: 0;

  img {
    max-height: 40px;
    width: auto;
    transition: all 0.3s;
  }

  @media (max-width: $breakpoint-md-max) {
    img {
      max-height: 34px;
    }
  }
}

.c-logo-mini {
  display: block;
  line-height: 0;

  img {
    height: 45px;
    width: auto;
  }

  @media (max-width: $breakpoint-md-max) {
    img {
      max-height: 34px;
    }
  }
}


</style>
