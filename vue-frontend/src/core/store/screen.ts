import {defineStore} from "pinia";
import {useQuasar} from "quasar";

export const useScreen = defineStore("screen", () => {

    const $q = useQuasar()

    return {
        screenWidth: $q.screen.width,
        screenHeight: $q.screen.height,
    }
})
