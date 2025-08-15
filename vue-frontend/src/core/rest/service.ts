import {GenqlError, Message, ResponseState,} from "@/gql/gen";
import {RequestContext} from "@/core/composable/createRequestContext";
import {useCommonStore} from "@/core/store/common";
import {useConfig} from "@/core/store/config";
import {defineStore} from "pinia";
import {ref} from "vue";
import {RestRequestQueryParams,} from "./types";
import axios from "axios";

export const useRest = defineStore("rest", () => {

    const {getConfig} = useConfig();

    const {getGlobalAlertsChannel, getGlobalRequestContext} = useCommonStore();

    const client = axios.create({
        baseURL: getConfig('app', 'API_REST_URL'),
        timeout: 5000,
        headers: {'X-Custom-Header': 'foobar'}
    });

    const errorProcessor = (e: GenqlError, requestContext?: RequestContext) => {
        const alertsChannel =
            requestContext?.getAlertsChannel() || getGlobalAlertsChannel();

        const messages: Partial<Message>[] = [];

        let status: string = "";

        if (e.errors) {
            e.errors.forEach((error) => {
                if (error.extensions?.status) {
                    status = error.extensions?.status;
                }
                messages.push({
                    type: "error",
                    message: error.message,
                });
            });
        } else {
            console.error(e);
        }

        alertsChannel?.showMessages(messages);
        return {
            status,
        };
    };

    const processResponseState = (
        state: ResponseState,
        requestContext?: RequestContext
    ) => {
        const alertsChannel =
            requestContext?.getAlertsChannel() || getGlobalAlertsChannel();
        const messages = state.messages.filter((message) => message);
        alertsChannel?.showMessages(messages);
    };

    const processing = ref(false);

    const query = async <TResult, TVariables>(
        url: string,
        variables: TVariables | {} = {},
        requestParams: RestRequestQueryParams<any, any> = {},
    ): Promise<TResult | null> => {
        let {} = requestParams;
        let {data} = await client.get(url, {
            params: variables
        });
        if (!data || typeof data !== "object") {
            throw new Error('Response error')
        }
        return data;
    };

    const queryWrapped = async <TResult, TVariables = any>(
        url: string,
        variables: TVariables | {} = {},
        params: RestRequestQueryParams<any, any> = {},
        context: RequestContext = getGlobalRequestContext()
    ): Promise<TResult | null> => {
        const {throwOnError = false, errorProcessorDisable = false} = params;
        processing.value = true;
        let res = null;
        try {
            res = await query<TResult, TVariables>(url, params);
        } catch (e: unknown) {
            console.log('e6', e)
            if (!errorProcessorDisable) {
                errorProcessor(e as GenqlError, context);
            }
            if (throwOnError) {
                throw e;
            }
        } finally {
            processing.value = false;
        }
        return res;
    };

    return {
        client,
        query,
        queryWrapped,
        processing,
    };
});
