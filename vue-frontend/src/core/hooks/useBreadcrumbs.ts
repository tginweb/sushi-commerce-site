import {computed} from 'vue'
import {useRoute} from 'vue-router'

export default function useBreadcrumbs(params: {
    withFront: boolean
} = {}) {

    const {
        withFront = true
    } = params

    const route = useRoute()

    const breadcrumbs = computed(() => {
        // Если в meta есть готовый массив breadcrumbs
        if (route.meta.breadcrumb instanceof Function) {
            const custom = route.meta.breadcrumb(route)
            if (Array.isArray(custom)) return custom
        }

        const path = []

        if (withFront) {
            path.push({
                to: '/',
                title: 'Главная'
            })
        }

        // Автогенерация на основе route.matched
        const matched = route.matched
            .filter(record => record.meta?.breadcrumb)
            .map((record, index, array) => {

                const routeMeta = record.meta

                let config = record.meta.breadcrumb

                if (typeof config === 'function') {
                    config = config(route)
                } else if (typeof metaBreadcrumb === 'boolean') {
                    config = {}
                }

                // Пропускаем скрытые элементы
                if (config.hidden) return null

                const to = config.to || record.path

                // Формируем элемент
                return {
                    title: config.title || routeMeta.title || record.name,
                    icon: config.icon,
                    to: to,
                    disabled: config.disabled
                }
            })
            .filter(item => item !== null)

        path.push(...matched)
        return path
    })


    return breadcrumbs
}
