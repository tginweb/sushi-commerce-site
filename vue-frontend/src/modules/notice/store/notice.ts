import {defineStore} from "pinia";
import {computed, reactive, ref} from "vue";
import {useScopeQuery} from "@/core/store/scopeQuery";
import {ClientNotice} from "@/gql/gen";
import {useGraphql} from "@/core/graphql/service";
import {useTask} from "@/core/hooks/useTask";
import {genqlBuild} from "@/core/graphql/genql/builder";
import {useAlerts} from "@/core/store/alerts";
import toInt from "@/core/util/toInt";
import {useRouter} from "vue-router";

const STORE_NAME = 'notice'

export type ClientNoticeComputed = ClientNotice & {
    isReaded: boolean
    isShown: boolean
    age: number
}

export const useNoticeStore = defineStore(STORE_NAME, () => {

        const {queryWrapped} = useGraphql()

        const {showAlerts} = useAlerts()

        const router = useRouter()

        const notices = ref<ClientNotice[]>([])

        const readedIds = reactive<Record<string, number>>({})

        const lastShownId = ref<number>(0)

        const getIsReaded = (notice: ClientNotice) => {
            return notice.isReaded || !!readedIds[notice.id] || getIsShown(notice) && !notice.haveBody
        }

        const getIsShown = (notice: ClientNotice) => {
            return notice.isReaded || lastShownId.value >= notice.id
        }

        const noticesComputed = computed(() => {
            return notices.value.map<ClientNoticeComputed>((notice) => ({
                ...notice,
                isReaded: getIsReaded(notice),
                isShown: getIsShown(notice),
                age: Math.round(Date.now() / 1000 - notice.createdAt)
            }))
        })

        const {registerScopeQuery} = useScopeQuery()

        const noticeById = computed(() => {
            return notices.value.reduce<Record<string, ClientNotice>>((map, notice) => {
                map[notice.id] = notice
                return map
            }, {})
        })

        const unreadedCount = computed(() => {
            return noticesComputed.value.reduce((acc, item) => {
                if (!item.isReaded)
                    acc++
                return acc
            }, 0)
        })

        const filterNewNotices = (items: ClientNotice[]) => {
            return items.filter(notice => () => {
                return notice.id > lastShownId.value
            })
        }

        const showNewNotices = (items: ClientNotice[]) => {

            return;

            /*
            items.forEach((notice) => {
                if (notice.id > lastShownId.value) {
                    //  lastShownId.value = notice.id
                }
            })

            showAlerts(items.map<Partial<Message>>(notice => {

                console.log(notice)

                return {
                    title: notice.title,
                    message: notice.message,
                    actions: notice.actionItems,
                    type: 'info',
                    duration: 1000 * 200
                }
            }))

             */
        }

        const fetchNoticesPolling = ref(0)

        const fetchNotices = useTask(async () => {
            const items = await queryWrapped<ClientNotice[]>(genqlBuild({
                notice_pub_list: {
                    __fragment: 'NoticeFields'
                }
            }), {})
            if (items)
                showNewNotices(filterNewNotices(items))
        }, {polling: fetchNoticesPolling})

        const readNotice = (notice: ClientNotice) => {

            console.log('readNotice', notice)

            readedIds[notice.id] = notice.id
        }

        registerScopeQuery(STORE_NAME, 'app', {
            notice_pub_list: {
                __fragment: 'NoticeFields'
            },
        }, (data) => {
            notices.value = data.notice_pub_list

            showNewNotices(notices.value)
            // clientCard.value = data.sale_pub_client_card_fetch as SaleClientCard
        })

        const showNoticeDialog = ({id}: { id: number | string }) => {
            id = toInt(id)
            router.push('/notice/show/' + id)
        }

        const register = () => {

        }

        return {
            register,
            notices: noticesComputed,
            readNotice,
            readedIds,
            unreadedCount,
            lastShownId,
            showNoticeDialog,
            noticeById,
        }
    },
    {
        /*
        persist: {
            pick: ['lastShownId'],
        }
         */
    }
)

