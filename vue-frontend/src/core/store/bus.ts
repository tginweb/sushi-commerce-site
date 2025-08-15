import {defineStore} from "pinia";
import {bus} from "@/boot/bus";

export const useBus = defineStore("bus", () => {
    return {
        bus
    }
})
