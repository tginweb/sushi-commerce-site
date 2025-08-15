import { Message } from "@/gql/gen";
import { ref, Ref } from "vue";

export type AlertsChannelParams = {
  name?: string | undefined;
  clearOnRequestStart?: boolean;
};

export type AlertsChannel = {
  name: string | undefined;
  messages: Ref<Partial<Message>[]>;
  showMessages: (items: Partial<Message>[]) => void;
  setMessages: (items: Partial<Message>[]) => void;
  clearMessages: () => void;
  deleteMessagesById: (ids: string[]) => void;
  deleteMessages: (models: Partial<Message>[]) => void;
  onRequestStart: () => void;
};

export const createAlertsChannel = (
  params: AlertsChannelParams = {}
): AlertsChannel => {
  const { name, clearOnRequestStart = true } = params;

  const messages = ref<Partial<Message>[]>([]);

  const setMessages = (items: Partial<Message>[]) => {
    messages.value = items;
  };

  const showMessages = (items: Partial<Message>[]) => {
    messages.value = items;
  };

  const clearMessages = () => {
    messages.value = [];
  };

  const deleteMessagesById = (ids: string[]) => {
    messages.value = messages.value.filter(
      (message) => !message.id || !ids.includes(message.id)
    );
  };

  const deleteMessages = (models: Partial<Message>[]) => {
    messages.value = messages.value.filter(
      (message) => models.indexOf(message) > -1
    );
  };

  const onRequestStart = () => {
    if (clearOnRequestStart) {
      clearMessages();
    }
  };

  return {
    name,
    messages,
    setMessages,
    showMessages,
    deleteMessagesById,
    deleteMessages,
    clearMessages,
    onRequestStart,
  };
};
