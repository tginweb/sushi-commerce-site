<template>

  <q-drawer
      ref="drawer"
      v-model="drawerMainModel"
      :breakpoint="400"
      :width="true ? 400 : 290"
      behavior="mobile"
      bordered
      class="c-drawer"
      content-class="bg-white"
      no-swipe-open
      overlay
      side="right"
  >
    <div class="column fit no-wrap">
      <div
          class="q-px-sm q-py-sm flex col-shrink items-center"
      >
        <div class="s-font-lg text-weight-bold q-ml-sm">
          <a href="tel:+73952506130" style="font-size:14px; font-weight: 500;">+7 (3952) 50-61-30</a>
        </div>
        <div class="q-ml-auto">
          <q-btn
              dense
              flat
              size="15px"
              :icon="ICONS.close"
              @click="drawerMainModel = false"
          />
        </div>
      </div>
      <div class="col-grow">

        <q-scroll-area class="fit">
          <q-list separator class="s-font-md">

            <q-item
                clickable
                v-ripple
                exact
                to="/personal"
                class="q-py-md"
            >
              <q-item-section avatar style="min-width: auto;">
                <q-icon :name="ICONS.profileCircle"/>
              </q-item-section>
              <q-item-section>Профиль</q-item-section>
            </q-item>

            <q-item
                clickable
                v-ripple
                exact
                to="/personal/order"
                class="q-py-md"
            >
              <q-item-section avatar style="min-width: auto;">
                <q-icon style="visibility: hidden;"/>
              </q-item-section>
              <q-item-section>
                <div>
                  <div class="relative-position inline-block">
                    Текущий заказ
                    <q-badge class="c-badge" style="position: absolute; top: -3px; right: -20px;">

                    </q-badge>
                  </div>
                </div>
              </q-item-section>
            </q-item>

            <q-item
                v-if="menuItems.top"
                clickable
                v-ripple
                v-for="item in menuItems.top"
                class="q-py-md"
                v-bind="menuItemToListItem(item)"
            >
              <q-item-section avatar style="min-width: auto;" v-if="item.icon">
                <q-icon :name="resolveIcon(item.icon)"/>
              </q-item-section>
              <q-item-section>{{ item.label }}</q-item-section>
            </q-item>

          </q-list>
        </q-scroll-area>
      </div>

    </div>

  </q-drawer>
</template>
<script setup lang="ts">

import {ICONS, resolveIcon} from "@/assets/icons";
import {useLayoutStore} from "@/stores/layout";
import {storeToRefs} from "pinia";
import {useMenuStore} from "@/modules/menu/store/menu";
import {menuItemToListItem} from "@/core/util/project/menuItemToListItem";

const menuStore = useMenuStore()
const {menuItems} = storeToRefs(menuStore)

const layoutStore = useLayoutStore()
const {drawerMainModel} = storeToRefs(layoutStore)

</script>

<style lang="scss" scoped>

.c-profile {
  border-top: 8px solid #f2f2f2;
  border-bottom: 8px solid #f2f2f2;
}

.c-menu__item {
  padding: 10px 0 10px 0;
  font-weight: bold;
}

.q-item {
  font-weight: 500;
  border-top: 1px solid #f2f2f2 !important;
}


</style>


