import {ClientNoticeGenqlSelection} from "@/gql/gen";

export const NoticeFields: ClientNoticeGenqlSelection = {
    actionItems: {
        __fragment: "MenuItem"
    },
    body: true,
    bodyHtml: true,
    cls: true,
    createdAt: true,
    eventData: true,
    eventGroup: true,
    eventName: true,
    haveBody: true,
    id: true,
    image: true,
    isReaded: true,
    message: true,
    offerId: true,
    showAs: true,
    targetClientId: true,
    targetCode: true,
    targetUserId: true,
    title: true
}
