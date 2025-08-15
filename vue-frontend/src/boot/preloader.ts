//@ts-ignore
import {defineBoot} from '#q-app/wrappers';
import {App} from "vue";

declare global {
    interface Window {
        hidePreloader?: () => void
        showPreloader?: () => void
    }
}

export default defineBoot((ctx: any) => {

    const app: App = ctx.app

    if (process.env.SERVER || typeof window === 'undefined' || !window.hidePreloader) {
        return
    }

    const hidePreloader = window.hidePreloader
    const showPreloader = window.showPreloader

    const ignoreErrors = [
        'ResizeObserver'
    ]

    window.addEventListener('error', (event) => {
        const {message} = event
        if (!ignoreErrors.find(pattern => message.match(pattern))) {
           // hidePreloader()
        }
    });

    window.addEventListener("unhandledrejection", function (promiseRejectionEvent) {
        //hidePreloader()
    })

    app.config.errorHandler = function (err, vm, info) {
       // hidePreloader()
        console.error(err, {vm, info})
    }

    app.config.warnHandler = function (err, vm, info) {
        //hidePreloader()
        console.warn(err, {vm, info})
    }
})


