<template>
</template>
<script setup lang="ts">

import {useBus} from "@/core/store/bus";
import {useRouter} from "vue-router";

const {bus} = useBus()
const router = useRouter()

bus.on('router:push', (to) => router.push(to))
bus.on('router:replace', (to) => router.replace(to))

</script>
