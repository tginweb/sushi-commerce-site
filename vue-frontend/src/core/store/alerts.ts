import Toast from "@/components/Toast.vue";
import {Message} from "@/gql/gen";
import {useAction} from "@/core/store/action";
import {useBus} from "@/core/store/bus";
import {defineStore} from "pinia";
import {POSITION, useToast} from "vue-toastification/src";

export const useAlerts = defineStore("alerts", () => {
    const {bus} = useBus();
    const toast = useToast();
    const {runAction} = useAction();

    const onAlertClick = (message: Partial<Message>) => {
        const action = message.actions?.find((action) => true);
        if (action) {
            runAction(action);
        }
    };

    const showAlerts = (messages: Partial<Message>[]) => {
        for (const message of messages) {
            const cb = toast[message.type as keyof typeof toast];
            if (cb) {
                cb(
                    {
                        component: Toast,
                        props: {
                            title: message.title,
                            message: message.message,
                            messages: message.messages,
                            actions: message.actions,
                        },
                        listeners: {
                            click: () => onAlertClick(message),
                        },
                    },
                    {
                        position: POSITION.TOP_CENTER,
                        timeout: message.duration || 2000,
                    }
                );
            }
        }
    };

    const showAlert = (message: Partial<Message>) => {
        showAlerts([message]);
    };

    const showFlash = (message: Partial<Message>) => {
        showAlert({
            type: "info",
            ...message,
        });
    };

    return {
        showAlerts,
        showAlert,
        showFlash,
    };
});
