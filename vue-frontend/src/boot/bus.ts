import {EventBus} from 'quasar'
import {BusEvents} from "@/core/app/types";

//@ts-ignore
export const bus = new EventBus<BusEvents>()

