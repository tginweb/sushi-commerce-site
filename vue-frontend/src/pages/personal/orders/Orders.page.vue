<template>
  <q-page class="">
    <div>

    </div>
  </q-page>
</template>
<script setup lang="ts">
import {onMounted, toRefs, watch} from "vue";
import {useRouter} from "vue-router";

const props = withDefaults(defineProps<{
  id?: string
}>(), {})

const {id} = toRefs(props)

const router = useRouter()

onMounted(() => {
  router.push('/personal/orders/active')
})

</script>
<style>

</style>
