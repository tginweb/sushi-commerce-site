export type AppContact = {
    type?: 'phone' | 'email' | 'vk' | 'ok'
    label?: string
    url?: string
    view?: string
    show: boolean
}
