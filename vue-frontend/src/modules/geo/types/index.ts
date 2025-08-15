import {GeoCoordinates} from "@/modules/geo/class/GeoCoordinates";

export type TGeoCoordsDir = 'longlat' | 'latlong'

export type TGeoCoordsArray = [number, number]

export type TGeoCoordsDataLowerCase = { lat: number, lon: number }
export type TGeoCoordsDataUpperCase = { LAT: number, LON: number }

export type TGeoCoordsData =
    [number | string, number | string] |
    [number, number, number?] |
    TGeoCoordsDataLowerCase |
    TGeoCoordsDataUpperCase |
    string

export type TGeoCoords = TGeoCoordsData | GeoCoordinates

export type TGeoMarkerProps = {
    id?: string,
    hintContent?: string
    balloonContent?: string
    iconCaption?: string
    iconContent?: string
}

export type TGeoMarkerOptions = {
    preset?: string
    iconColor?: string
    iconCaptionMaxWidth?: string
    iconLayout?: string
    iconImageHref?: string
    iconImageSize?: any
    iconImageOffset?: any
    iconCaption?: string
    iconContent?: string
    iconGlyph?: string
    iconGlyphColor?: string
    caption?: TGeoMarkerCaption
    balloonOffset?: [number, number]
}

export type TGeoMarkerCaption = {
    text?: string
}


export type TGeoMarker<T> = {
    id: any
    entity: T
    entityId: number
    selected?: boolean
    marker?: any
}

export type TGeoZone<T> = {
    id: any
    entity: T
    entityId: number
    selected?: boolean
    marker?: any
}

export type TMapMarkerYandexData = {
    id: string
    coords: any
    props: any
    options: any
    selectedOptions: any
    caption?: TGeoMarkerCaption
}


