import {defineStore} from "pinia";
import {useTimestamp} from "@vueuse/core";
import {computed} from "vue";

export const useTimers = defineStore("timers", () => {

    const timestampIntervalMinute = useTimestamp({controls: false, interval: 10000, immediate: true})

    const timestampUnixIntervalMinute = computed(() => Math.round(timestampIntervalMinute.value / 1000))

    return {
        timestampIntervalMinute,
        timestampUnixIntervalMinute
    }
})
