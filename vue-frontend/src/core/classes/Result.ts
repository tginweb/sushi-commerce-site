import {LocalMessage, Redirect} from "@/core/types";

export type ResultStatus = 'error' | 'success' | string

export default class Result {

    messages: LocalMessage[] = []
    redirect?: Redirect
    success: boolean = true
    status: 'error' | 'success' = 'success'

    getMessages() {
        return this.messages
    }

    addMessage(type: string, message: string) {
        this.messages.push({
            type,
            message
        } as LocalMessage)
    }

    setRedirect(redirect: Redirect) {
        this.redirect = redirect
    }

    getRedirect() {
        return this.redirect
    }

    addError(message: string) {
        this.addMessage('error', message)
        this.setStatus('error')
    }

    setStatus(status: ResultStatus) {
        if (status !== 'success') {
            this.success = false
        } else {
            this.success = true
        }
    }

    isSuccess() {
        return this.success
    }
}

