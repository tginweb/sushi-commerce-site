import {defineStore} from "pinia";
import {MenuItem} from "@/gql/gen";
import {parseActionPath} from "@/core/util/project/parseActionPath";
import {runJs} from "@/core/util/runJs";
import {useBus} from "@/core/store/bus";
import {openUrl} from "@/core/util/openUrl";
import {useRootStore} from "@/stores/root";

export const useAction = defineStore("action", () => {

    const {bus} = useBus()
    const {storeAction} = useRootStore()

    const runAction = (actionItem: Partial<MenuItem>) => {

        if (actionItem.onClick) {
            runJs(actionItem.onClick)
            return;
        }

        if (actionItem.url) {
            const action = parseActionPath(actionItem.url)

            if (action) {
                switch (action.type) {
                    case 'location':
                        if (action.path) {
                            openUrl(action.path, actionItem.blank || action.blank)
                        }
                        break;
                    case 'router':
                        if (action.path) {
                            if (actionItem.native) {
                                openUrl(action.path, actionItem.blank)
                            } else {
                                if (action.replace) {
                                    console.log('router:replace', action)

                                    bus.emit('router:replace', action.path)
                                } else {
                                    bus.emit('router:push', action.path)
                                }
                            }
                        }
                        break;
                    case 'store':
                        storeAction(action.store, action.path, action.params)
                        break;
                    case 'js': {
                        runJs(action.code)
                        break;
                    }
                }
            }
        }
    }

    return {
        runAction,
    }
})
