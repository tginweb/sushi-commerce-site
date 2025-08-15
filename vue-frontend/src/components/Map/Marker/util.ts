import type {LngLat} from "@yandex/ymaps3-types";
import {OrderProfileModel} from "@/modules/shop/composable/orderable/useProfileModel";
import {DefinedProps} from "@/core/vue/types";
import {toRefs} from "vue";

export type MarkerEmits = {
    (e: 'marker-click', marker: Marker): void
}

export type MarkerProps = {
    marker: Marker
}

export interface MarkerInterface {
    coords: LngLat
    name: string
    selected: boolean
    entityId: number
    type: string
    time?: number | null
}

export interface MarkerOffice extends MarkerInterface {
    type: 'office'
    office: any
}

export interface MarkerProfile extends MarkerInterface {
    type: 'profile'
    profile: OrderProfileModel
}

export type Marker = MarkerOffice | MarkerProfile

export function useMarker(props: DefinedProps<MarkerProps>, emit: MarkerEmits) {
    const {marker} = toRefs(props)
    const onClick = () => {
        emit('marker-click', marker.value)
    }
    return {
        marker,
        onClick
    }
}
