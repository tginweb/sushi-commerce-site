declare module "@/core/app/types" {
    interface BusEvents {
        'vorder:validate': (from: 'dialog', code?: string) => void,
        'vorder:basket.change': () => void,
    }
}
