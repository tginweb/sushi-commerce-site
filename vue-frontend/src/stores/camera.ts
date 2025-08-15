import {Webcam} from "@/gql/gen";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {defineStore} from "pinia";
import {ref} from "vue";

const STORE_NAME = 'camera'

export const useCameraStore = defineStore(STORE_NAME, () => {

    const cameraElements = ref<Webcam[]>([])

    const {registerScopeQuery} = useScopeQuery()

    registerScopeQuery(STORE_NAME, 'app', {
        camera_element_list: {
            __fragment: 'CameraElementFields'
        },
    }, (data) => {
        cameraElements.value = data.camera_element_list
    })

    return {
        cameraElements
    }
})

