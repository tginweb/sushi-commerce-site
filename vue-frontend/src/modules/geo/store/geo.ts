import {defineStore} from "pinia";
import {useGraphql} from "@/core/graphql/service";
import {GeoCoordinates} from "@/modules/geo/class/GeoCoordinates";
import geoGeocoderLocationsByCoordsQuery from "@/gql/gen/query/geoGeocoderLocationsByCoordsQuery";

const STORE_NAME = "geo";

export const useGeoStore = defineStore(STORE_NAME, () => {

    const {useQuery} = useGraphql()

    async function getLocationByCoords(coords: GeoCoordinates) {
        const res =  await getLocationsByCoords(coords)
        if (res) {
            return res[0]
        }
    }

    function getLocationsByCoords(coords: GeoCoordinates) {
        return geoGeocoderLocationsByCoordsQuery({
            __fragment: 'GeoObject',
        }).run({
            lat: coords.lat,
            lon: coords.lon
        })
    }

    function getCoordsByLocation(coords: GeoCoordinates) {
        return geoGeocoderLocationsByCoordsQuery({
            __fragment: 'GeoObject',
        }).run({
            lat: coords.lat,
            lon: coords.lon
        })
    }

    return {
        getLocationByCoords,
        getLocationsByCoords,
        getCoordsByLocation
    }
});

