//@ts-ignore
import {defineBoot} from '#q-app/wrappers';
import {handleHotkeys} from "@/core/bus/handleHotkeys";
import {bus} from "./bus";

export default defineBoot((ctx: any) => {

    if (!process.env.SERVER) {
        handleHotkeys((hotkey) => {
            bus.emit('hotkey:' + hotkey)
        })
    }
})
