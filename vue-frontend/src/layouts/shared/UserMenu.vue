<template>
  <q-no-ssr>
    <div
        ref="com"
        class="com flex no-wrap q-gutter-md items-center justify-end"
        :class="{
        '--dropdown-show': submenuShown
      }"
        v-if="isMounted"
    >

      <q-btn
          v-model="submenus.fav.opened"
          :icon="ICONS.favorite"
          class="c-nav-btn c-nav-btn-fav gt-md q-px-xs"
          color="dark"
          dense
          flat
          :to="{name: 'sale:favorites.dialog'}"
          v-if="isAuthorized"
      />

      <q-btn
          :icon="ICONS.search"
          class="c-nav-btn c-nav-btn-search q-px-xs"
          color="dark"
          dense
          flat
          to="/catalog/search"
      />

      <q-btn-dropdown
          v-if="isAuthorized"
          class="c-nav-btn c-nav-btn-user q-px-xs "
          color="dark"
          flat
          dense
          to="/auth"
          content-class="--max-height-none"
          v-model="submenus.user.opened"
          menu-self="top middle"
          :menu-offset="[-50, 20]"
          @show="onDropdownShow('user')"
          @hide="onDropdownHide('user')"
          @mouseenter.native="showSubmenu('user')"
          @mouseleave.native="hideSubmenu('user')"
          :icon="ICONS.profileCircle"
      >
        <template v-slot:label>

          &nbsp;
          <q-badge class="c-nav-btn-badge" v-if="noticeUnreadCount" style="">
            {{ noticeUnreadCount }}
          </q-badge>

        </template>
        <template v-slot:default>

          <q-list
              @mouseleave="hideSubmenu('user')"
              @mouseenter="showSubmenu('user')"
          >
            <q-item
                v-for="(item, index) of menu"
                :key="index"
                :to="item.url || null"
                clickable
                @click="runAction(item)"
            >
              <q-item-section avatar style="min-width:auto;">
                <q-icon color="dark" :name="resolveIcon(item.icon)" v-if="item.icon"/>
              </q-item-section>
              <q-item-section>
                <span style="white-space: nowrap;">{{ item.infoLabel || item.label }}</span>
              </q-item-section>
              <q-item-section
                  side
                  v-if="item.badgeLabel"
              >
                <q-badge class="c-badge">
                  {{ item.badgeLabel }}
                </q-badge>
              </q-item-section>
            </q-item>
          </q-list>
        </template>
      </q-btn-dropdown>

      <q-btn
          v-else
          :icon="ICONS.profileCircle"
          class="c-nav-btn c-nav-btn-user q-px-xs"
          color="dark"
          dense
          flat
          @click="onNavAuth"
      />

      <q-btn
          :icon="ICONS.basket"
          class="c-nav-btn c-nav-btn-basket q-px-xs"
          color="dark"
          dense
          flat
          @click="drawerBasket = !drawerBasket"
          v-if="!drawerBasket"
      />

      <q-btn
          :icon="ICONS.humburger"
          class="c-nav-btn q-px-xs "
          color="dark"
          dense
          flat
          @click="drawerMainModel = !drawerMainModel"
      />

    </div>
  </q-no-ssr>
</template>

<script setup lang="ts">

import {useMounted} from "@vueuse/core";
import {computed, reactive} from "vue";
import {getTypedEntries} from "@/core/util/getTypedEntries";
import {ICONS, resolveIcon} from "@/assets/icons";
import {useUserStore} from "@/modules/user/store/user";
import {storeToRefs} from "pinia";
import {useNoticeStore} from "@/modules/notice/store/notice";
import {useAction} from "@/core/store/action";
import {useRootStore} from "@/stores/root";
import {useLayoutStore} from "@/stores/layout";
import {useRouter} from "vue-router";

const isMounted = useMounted()
const userStore = useUserStore()
const noticeStore = useNoticeStore()
const {runAction} = useAction()
const {storeGetter} = useRootStore()

const router = useRouter()

const {isAuthorized, menuPersonal} = storeToRefs(userStore)
const {unreadedCount: noticeUnreadCount} = storeToRefs(noticeStore)

const layoutStore = useLayoutStore()
const {drawerMainModel, drawerBasket} = storeToRefs(layoutStore)

type Submenus = Record<'fav' | 'notice' | 'user' | 'basket', {
  opened: boolean
  timeout: number | null
  shown: boolean
  hideTimeout?: any
}>

const submenuState = {
  opened: false,
  timeout: null,
  shown: false,
}

const submenus = reactive<Submenus>({
  fav: {
    ...submenuState
  },
  notice: {
    ...submenuState
  },
  user: {
    ...submenuState
  },
  basket: {
    ...submenuState
  }
})

const onDropdownHide = (name: keyof Submenus) => {
  submenus[name].shown = false
}

const onDropdownShow = (name: keyof Submenus) => {
  submenus[name].shown = true
}

const showSubmenu = (showName: keyof Submenus) => {
  for (const [name, props] of getTypedEntries(submenus)) {
    if (submenus[showName].hideTimeout)
      clearTimeout(submenus[showName].hideTimeout)
    props.opened = (name === showName)
  }
}

const hideSubmenu = (name: keyof Submenus) => {
  if (submenus[name].hideTimeout)
    clearTimeout(submenus[name].hideTimeout)
  submenus[name].hideTimeout = setTimeout(() => {
    submenus[name].opened = false
  }, 800)
}

const submenuShown = computed(() => {
  for (const [name, props] of getTypedEntries(submenus)) {
    if (props.shown) return true
  }
  return false
})

const onNavAuth = () => {
  router.push('/auth')
}

const menu = computed(() => menuPersonal.value.map((item) => ({
  ...item,
  badgeLabel: item.badge ? (item.badge.getter ? storeGetter[item.badge.getter] : item.badge.label) : ''
})))

</script>

<style lang="scss" scoped>


.c-nav-btn {
  line-height: 1em;
  font-size: 23px;

  .c-nav-btn-badge {
    position: absolute;
    top: 0px;
    right: -1px;
  }

  :deep() {
    .q-btn-dropdown__arrow {
      display: none;
    }

    .q-btn__wrapper {
      min-height: auto;
    }

    .q-icon {
      width: 25px;
      height: 25px;
    }

    .q-icon.on-left {
      margin-right: 14px;
    }

    span + .q-icon {
      margin-left: 14px;
    }
  }

  @media (max-width: $breakpoint-lg-max) {
    :deep(.q-icon) {
      width: 21px;
      height: 21px;
    }

    &.c-nav-btn-user {
      :deep(.q-icon) {
        width: 23px !important;
        height: 23px !important;
      }
    }
  }
}

</style>
