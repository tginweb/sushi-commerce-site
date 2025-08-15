import { useAlerts } from "@/core/store/alerts";
import { nextTick } from "process";
import { computed, watch } from "vue";
import { AlertsChannel, createAlertsChannel } from "./createAlertsChannel";

export const createAlertsChannelGlobal = (): AlertsChannel => {
  const { showAlerts } = useAlerts();

  const channel = createAlertsChannel({
    name: "global",
  });

  const messages = computed(() => channel.messages.value);

  watch(messages, () => {
    //console.log("messages changed", messages.value);
    nextTick(() => {
      if (messages.value.length) {
        showAlerts(messages.value);
        channel.clearMessages();
      }
    });
  });

  return channel;
};
