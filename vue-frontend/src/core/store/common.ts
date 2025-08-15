import { createAlertsChannelGlobal } from "@/core/composable/createAlertsChannelGlobal";
import { createRequestContext } from "@/core/composable/createRequestContext";
import { createRequestContextManager } from "@/core/composable/createRequestContextManager";
import { defineStore } from "pinia";

const STORE_NAME = "common";

export const useCommonStore = defineStore(STORE_NAME, () => {
  const requestContextManager = createRequestContextManager();
  const globalRequestContext = createRequestContext({
    name: "global",
  });
  const globalAlertsChannel = createAlertsChannelGlobal();

  globalRequestContext.setAlertsChannel(globalAlertsChannel);
  requestContextManager.setGlobalContext(globalRequestContext);

  const getGlobalRequestContext = () =>
    requestContextManager.getGlobalContext();

  const getGlobalAlertsChannel = () =>
    requestContextManager.getGlobalContext().alertsChannel.value;

  return {
    requestContextManager,
    getGlobalRequestContext,
    getGlobalAlertsChannel,
  };
});
