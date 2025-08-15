<template>
  <div v-if="debugMode" class="component-debugger">
    <q-btn color="primary" label="Basic Menu">
      <q-menu>

        <q-list style="min-width: 100px">
          <q-item
              clickable
              v-close-popup
              v-for="item in menu"
              :key="item.label || ''"
              @click="onMenuItemClick(item)"
          >
            <q-item-section>{{ item.label }}</q-item-section>
          </q-item>
        </q-list>
      </q-menu>
    </q-btn>
  </div>
</template>

<script setup lang="ts">
import {defineProps, toRefs, withDefaults} from "vue";
import {useDebugStore} from "@/core/store/debug";
import {storeToRefs} from "pinia";
import {MenuItem} from "@/gql/gen";

const debugStore = useDebugStore()

const {debugMode} = storeToRefs(debugStore)

const props = withDefaults(defineProps<{
  menu?: Partial<MenuItem>[]
}>(), {
  menu: () => []
})

const {menu} = toRefs(props)

const {} = props

const onMenuItemClick = (item: Partial<MenuItem>) => {
  item.onClick()
}

</script>
<style lang="scss" scoped>
.component-debugger {
  position: absolute;
  right: 0;
  top: 0;
}
</style>
