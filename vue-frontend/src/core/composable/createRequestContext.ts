import { isRef, ShallowRef, shallowRef } from "vue";
import { AlertsChannel } from "./createAlertsChannel";

export type RequestContext = {
  name: string | undefined;
  alertsChannel: ShallowRef<AlertsChannel | undefined>;
  setAlertsChannel: (channel: AlertsChannel) => void;
  getAlertsChannel: () => AlertsChannel | undefined;
};

export const createRequestContext = (
  params: {
    name?: string;
    alertsChannel?: ShallowRef<AlertsChannel | undefined> | AlertsChannel;
  } = {}
): RequestContext => {
  const { name } = params;

  let alertsChannel = isRef(params.alertsChannel)
    ? params.alertsChannel
    : shallowRef<AlertsChannel | undefined>(params.alertsChannel);

  const setAlertsChannel = (channel: AlertsChannel) => {
    alertsChannel.value = channel;
  };

  const getAlertsChannel = () => {
    return alertsChannel.value;
  };

  return {
    name,
    alertsChannel,
    getAlertsChannel,
    setAlertsChannel,
  };
};
