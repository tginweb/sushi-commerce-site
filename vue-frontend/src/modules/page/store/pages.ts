import {defineStore} from "pinia";
import {computed, Ref, ref, toValue} from "vue";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {Page} from "@/gql/gen";
//@ts-ignore
import {trim} from "lodash-es";
//@ts-ignore
import {MetaOptions} from "quasar/dist/types/meta";

const STORE_NAME = 'pages'

export const usePagesStore = defineStore(STORE_NAME, () => {

    const pages = ref<Page[]>([])

    const getPageByUrl = (url: string): Page | undefined => {
        return pages.value.find(page => page.URL && (trim(page.URL, '/') === trim(url, '/')))
    }

    const {registerScopeQuery} = useScopeQuery()

    registerScopeQuery(STORE_NAME, ['app'], {
        page_list: {
            __fragment: 'PageFields'
        },
    }, (data) => {
        pages.value = data.page_list
    })

    return {
        pages,
        getPageByUrl
    }
})

export function usePage(code: string | Ref<string>, vars: any = {}) {
    const {getPageByUrl} = usePagesStore()
    const page = computed<Page | null | undefined>(() => {
        const codeValue = toValue(code)
        return codeValue ? getPageByUrl(codeValue) : null
    })
    const pageMeta = computed<MetaOptions>(() => {
        return {
            title: page.value?.NAME || '',
            meta: {
                description: {name: 'description', content: page.value?.NAME},
            },
        }
    })
    return {
        page,
        pageMeta
    }
}

