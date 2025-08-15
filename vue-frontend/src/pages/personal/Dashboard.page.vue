<template>
  <q-page class=" ">
    <div class="c-sections row q-col-gutter-md q-col-gutter-md-lg">

      <ProfileWidget
          title="Профиль"
          class="col-24 col-lg-12"
      />

      <PersonalWidget
          title="Заказы"
          class="col-24 col-lg-12 "
      />

      <PersonalWidget
          title="Уведомления"
          class="col-24 col-lg-12 "
      />

    </div>
  </q-page>
</template>
<script setup lang="ts">
import PersonalWidget from "./dashboard/widgets/PersonalWidget.vue";
import ProfileWidget from "./dashboard/widgets/ProfileWidget.vue";
import {useRootStore} from "@/stores/root";
import {useConfig} from "@/core/store/config";

const props = withDefaults(defineProps<{}>(), {})

const {storeGetter} = useRootStore()

const unreadedCount = storeGetter['notice:unreadedCount']

const {getConfig} = useConfig()

console.log('unreadedCount', unreadedCount)
</script>
<style>

</style>
