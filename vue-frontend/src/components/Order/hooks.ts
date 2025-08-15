import {computed, toRefs} from "vue";
import {BasketItem, MenuItem, Order, OrderStatus} from "@/gql/gen";
import {storeToRefs} from "pinia";
import {joinAttrsAddress} from "@/modules/shop/util/joinAttrsAddress";
import {useRouter} from "vue-router";
import {useVorderStore} from "src/modules/shop/store/vorder";
import {TBasketOpAddMultipleProduct, useVorderBasketStore} from "@/modules/shop/store/vorder/basket";
import {useShopStore} from "@/modules/shop/store/shop";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";

export type OrderViewStatus = OrderStatus & {
    CURRENT: boolean;
    DONE: boolean;
    COLOR_STROKE: string;
    READY: boolean;
    COLOR_FILL: string;
    ICON: any
}

export type OrderViewProps = {
    order: Order,
    refetchEnable?: boolean,
    refetchInterval?: number,
    view?: 'detail' | 'teaser'
}

export type OrderViewField = {
    code?: string
    name?: string
    value?: string | number | null
    values?: string[]
    valueClass?: string | null
    valueHtml?: string | null
}

export type BasketItemWithElementModel = Omit<BasketItem, 'ELEMENT'> & {
    ELEMENT?: ProductElementModel
}

export function useOrder(props: OrderViewProps) {

    const {
        order,
        refetchEnable,
        refetchInterval
    } = toRefs(props)

    const router = useRouter()
    const saleStore = useShopStore()
    const vorderStore = useVorderStore()
    const vorderBasket = useVorderBasketStore()

    const {orderStatusesOrder} = storeToRefs(saleStore)
    const {basketOp} = vorderBasket

    const delivery = computed(() => {
        return order.value.DELIVERY
    })

    const transportType = computed(() => {
        return delivery.value?.TRANSPORT_TYPE
    })

    const deliveryAddress = computed(() => joinAttrsAddress(order.value.ATTR))

    const statuses = computed<OrderViewStatus[]>(() => {
        let foundCurrent = false
        const orderStatusId = order.value.CSTATUS_ID
        return orderStatusesOrder.value.map((status) => {

            const extend = {} as OrderViewStatus

            if (status.ID === orderStatusId) {
                foundCurrent = true
                extend.CURRENT = true
            }

            if (!foundCurrent) {
                extend.DONE = true
            }

            if (extend.DONE || extend.CURRENT) {
                extend.COLOR_STROKE = '#00AA00';
                extend.READY = true
            } else {
                extend.COLOR_STROKE = '#AAAAAA';
                extend.READY = false
            }

            extend.COLOR_FILL = '#FFF';

            if (status.ID === 'D') {
                if (transportType.value === 'pickup') {
                    extend.NAME = 'Самовывоз'
                    extend.ICON = 'pickup'
                }
            }

            return {
                ...status,
                ...extend
            }

        }).filter((status) => {
            if (status.ID === 'N' && status.DONE) {
                return false
            }
            return true
        })
    })

    const deliveryTimeFormatted = computed(() => {
        const res = [];
        if (order.value.ATTR.DATE)
            res.push(order.value.ATTR.DATE)
        if (order.value.ATTR.TIME)
            res.push(order.value.ATTR.TIME)
        return res.join(' ')
    })

    const onNav = () => {

    }

    const onNavContact = () => {
        router.push('/contacts/');
    }

    const onNavCancel = () => {
        router.push('/personal/order/' + order.value.ID + '/cancel');
    }

    const actions = computed(() => {

        const result: Partial<MenuItem>[] = []

        if (props.view !== 'detail') {
            result.push({
                color: 'primary-light',
                textColor: 'primary',
                label: 'Детали',
                onClick: onNav,
                width: 'col-auto'
            })
        }

        if (order.value.IS_ACTIVE) {
            result.push({
                color: 'primary-brown-1',
                textColor: 'primary',
                label: 'Связаться',
                onClick: onNavContact,
                width: 'col-auto'
            })
        }

        if (order.value.IS_ACTIVE && !order.value.IS_PAID) {
            result.push({
                color: 'primary-brown-1',
                textColor: 'actions-red',
                label: 'Отменить',
                onClick: onNavCancel,
                width: 'col-auto'
            })
        }

        return result
    })

    const fields = computed(() => {

        const res: OrderViewField[] = []

        if (order.value.ATTR.DETAILS) {

            if (transportType.value === 'courier') {

                res.push({
                    code: 'DELIVERY_TYPE',
                    name: 'Способ доставки',
                    value: 'курьером'
                })

                res.push({
                    code: 'DELIVERY',
                    name: 'Адрес',
                    value: deliveryAddress.value
                })

                res.push({
                    code: 'TIME',
                    name: 'Время доставки',
                    valueClass: deliveryTimeFormatted.value
                })

                res.push({
                    code: 'COMMENT',
                    name: 'Комментарий к адресу',
                    value: order.value.ATTR.PROFILE_COMMENT
                })

            } else {

                res.push({
                    code: 'DELIVERY_TYPE',
                    name: 'Способ доставки',
                    value: 'самовывоз'
                })

                res.push({
                    code: 'DELIVERY',
                    name: 'Адрес',
                    valueClass: order.value.PICKUP_DEPARTMENT ? order.value.PICKUP_DEPARTMENT.NAME : null
                })

                res.push({
                    code: 'TIME',
                    name: 'Время самовывоза',
                    value: deliveryTimeFormatted.value
                })
            }

            res.push({
                code: 'PAYMENT_TYPE',
                name: 'Способ оплаты',
                value: order.value.ATTR.PAYMENT_TYPE_STRING
            })

            if (order.value.PAYSYSTEM_IS_ONLINE) {
                if (order.value.IS_PAID) {
                    res.push({
                        code: 'PAID',
                        name: 'Статус оплаты',
                        value: 'заказ оплачен',
                        valueClass: 'bg-green-7 text-white q-px-sm'
                    })
                } else {
                    res.push({
                        code: 'PAID',
                        name: 'Статус оплаты',
                        value: 'заказ не оплачен',
                    })
                }
            }

            res.push({
                code: 'PERSONS_NUMBER',
                name: 'Количество приборов',
                value: order.value.ATTR.PERSONS_NUMBER
            })

            res.push({
                code: 'CALL',
                name: 'Позвонить Вам для подтверждения',
                value: order.value.ATTR.WITH_OPERATOR_STRING
            })
        }

        res.push({
            code: 'USER',
            name: 'Получатель',
            values: [
                order.value.ATTR.FIO,
                order.value.ATTR.PHONE,
                order.value.ATTR.EMAIL
            ].filter(val => !!val) as string[]
        })

        res.push({
            code: 'COMMENT',
            name: 'Комментарий к заказу',
            value: order.value.ATTR.USER_DESCRIPTION
        })

        console.log(res)

        return res.filter(item => item.valueHtml || item.value || item.values && item.values.length)
    })

    const onBasketAdd = (basketItem: BasketItemWithElementModel) => {
        basketOp({
            action: 'add',
            productId: basketItem.PRODUCT_ID,
            quantity: 1
        })
    }

    const onBasketRepeat = () => {
        basketOp({
            action: 'add-multiple',
            products: order.value.BASKET.map<TBasketOpAddMultipleProduct>(item => ({
                productId: item.PRODUCT_ID,
                quantity: item.QUANTITY,
            })),
        })
    }

    return {
        fields,
        delivery,
        transportType,
        statuses,
        actions,
        onBasketAdd,
        onBasketRepeat
    }
}
