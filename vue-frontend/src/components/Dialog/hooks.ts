import {reactive} from "vue";

export type DialogElementState = {
    height: number
}

export type DialogElementName = 'header' | 'footer' | 'body'

export default function useDialog() {

    const elements = reactive<{
        [key in DialogElementName]: DialogElementState
    }>({
        header: {
            height: 0
        },
        footer: {
            height: 0
        },
        body: {
            height: 0
        },
    });

    const updateElement = (name: DialogElementName, info: DialogElementState) => {
        elements[name] = {
            ...elements[name],
            ...info
        }
    }

    return {
        elements,
        updateElement
    }
}
