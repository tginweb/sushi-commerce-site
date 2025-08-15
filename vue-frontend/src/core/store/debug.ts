import {defineStore} from "pinia";
import {useBus} from "@/core/store/bus";
import {ref} from "vue";

const STORE_NAME = 'debug'

export const useDebugStore = defineStore(STORE_NAME, () => {

    const {bus} = useBus()

    const debugMode = ref<boolean>(false)

    //@ts-ignore
    bus.on('hotkey:Ctrl+9', () => {
        debugMode.value = !debugMode.value
        console.log('DEBUG MODE', debugMode.value)
    })

    return {
        debugMode
    }
})

