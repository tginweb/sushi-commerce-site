import {useBus} from "@/core/store/bus";
import {defineStore} from "pinia";
import {ref} from "vue";
import axios, {CancelTokenSource} from "axios";
import {DaDataAddress, DaDataAddressBounds, DaDataSuggestion} from "@/modules/geo/types/dadata";

export type DaDataAddressRequestParams = {
    locations?: any,
    from_bound?: DaDataAddressBounds,
    to_bound?: DaDataAddressBounds,
    count?: number
}

const defaultLocations = [
    {
        "region": "Иркутская",
        "city": "Иркутск"
    },
    {
        "region": "Иркутская",
        "area": "Иркутский"
    },
    {
        "region": "Иркутская",
        "settlement": "Мегет"
    },
    {
        "kladr_id": "3800100200000"
    },
    {
        "kladr_id": "3800100009200"
    },
    {
        "kladr_id": "3800100003900"
    },
    {
        "kladr_id": "3800100004800"
    },
    {
        "kladr_id": "3800001500000"
    },
    {
        "kladr_id": "3800100010800"
    },
    {
        "kladr_id": "3800100001600"
    }
]

export type DaDataAddressSuggestion = DaDataSuggestion<DaDataAddress>

export const useDaData = defineStore("dadata", () => {

    const {bus} = useBus();

    const BASE_URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest'
    const apiKey = '26b365bf33da0ed78ba841536dbb413248c21ca0'
    const queryToken = ref<CancelTokenSource | null>(null)

    const getAddressSuggestions = async (query: string, params: DaDataAddressRequestParams = {}) => {

        const {
            count = 10,
            locations = defaultLocations,
            from_bound,
            to_bound = 'house'
        } = params


        if (queryToken.value) queryToken.value.cancel();

        queryToken.value = axios.CancelToken.source();

        const headers = {
            'Authorization': `Token ${apiKey}`,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }

        let request: any = {
            query,
            count,
            locations,
        }

        if (from_bound)
            request.from_bound = {"value": from_bound}

        if (to_bound)
            request.to_bound = {"value": to_bound}

        console.log('request', request)

        const apiUrl = `${BASE_URL}/address`

        let res: DaDataAddressSuggestion[] = []

        try {
            const {data: {suggestions}} = await axios.post(apiUrl, request, {
                headers: headers,
                cancelToken: queryToken.value.token
            })
            res = suggestions
        } catch (e) {
            console.log(e)
        }

        return res
    }

    return {
        getAddressSuggestions
    };
});
