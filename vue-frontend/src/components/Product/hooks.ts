import {storeToRefs} from "pinia";
import {computed, ref, toRefs} from "vue";
import {ImageSize, useImager} from "@/core/store/imager";
import {BasketItemModel} from "@/modules/shop/composable/useBasketItemModel";
import {useRouter} from "vue-router";
import {useShopFavStore} from "@/modules/shop/store/fav";
import toInt from "@/core/util/toInt";
import {useNow} from "@vueuse/core";
import {useVorderBasketStore} from "@/modules/shop/store/vorder/basket";
import {useVorderCalculatorStore} from "@/modules/shop/store/vorder/calculator";
import {ProductElementModel} from "@/modules/shop/composable/useProduct";
import {
    ProductDiscountsCalculation,
    ProductDiscountsCalculationOptions
} from "@/modules/shop/store/vorder/util/benefit";
import {useVorderBenefitStore} from "@/modules/shop/store/vorder/benefit";

export type ProductCardProps = {
    product: ProductElementModel
    borderStyle?: string | undefined
    orientation?: string | undefined
    viewMode?: string | undefined
}

export type ProductCardViewStyle = {
    rowClass: string
    colMediaClass: string
    colMainClass: string
    mediaClass: string
    title: string
}

export type UseProductParams = {
    giftsVisible?: boolean
    validateAdd?: any
    imageSize?: ImageSize
    visible?: boolean
    onDetailsRoute?: (product: ProductElementModel) => any | boolean
    onDetails?: (product: ProductElementModel) => void
    onDetailsBehavior?: 'basket' | 'product'
    onDetailsProduct?: (product: ProductElementModel) => void
    excludeFlags?: string[]
    productOpenAllowSecondary?: boolean
    calcOptions?: ProductDiscountsCalculationOptions
    viewModes?: Record<string, ProductCardViewStyle>
}

export function useProductView(props: ProductCardProps, params: UseProductParams = {}) {

    const basketStore = useVorderBasketStore()
    const calculatorStore = useVorderCalculatorStore()
    const benefitStore = useVorderBenefitStore()

    const {basketOp} = basketStore
    const {calculateProductDiscounts} = calculatorStore

    const {basketItemsModelsByProduct} = storeToRefs(basketStore)

    const {product} = toRefs(props)

    const {
        giftsVisible = false,
        validateAdd,
        onDetailsProduct,
        onDetailsBehavior = 'product',
        onDetails,
        onDetailsRoute = true,
        excludeFlags,
        imageSize = 'm-1',
        productOpenAllowSecondary,
        calcOptions = {},
        viewModes = {}
    } = params

    const productId = product.value.ID || 0

    const basketItem = ref({} as BasketItemModel)

    const onDetailsRouteDefault = (product: ProductElementModel) => '/product/' + product.ID

    const _onDetailsRoute = onDetailsRoute ? (typeof onDetailsRoute === 'function' ? onDetailsRoute : onDetailsRouteDefault) : false

    const now = useNow({interval: 1000 * 5})

    //now.value.getTime()


    const inBasket = computed(() => basketItem.value.commited)

    const basketItemChildrenByProductId = computed(() => {
        return basketItem.value.children.reduce<Record<number, BasketItemModel>>((map, item) => {
            if (item.fields.PRODUCT_ID) map[item.fields.PRODUCT_ID] = item
            return map
        }, {})
    })

    const basketQuantity = computed(() => basketItem.value?.fields.QUANTITY || 0)

    const basketInputProps = ref<any>({
        GIFT: null,
    })

    const basketProps = ref<any>({
        GIFT: null,
    })

    const hover = ref<boolean>(false)

    const onMouseEnter = () => {
        console.log('onMouseEnter')
        hover.value = true
    }

    const onMouseLeave = () => {
        console.log('onMouseLeave')
        hover.value = false
    }

    const onGiftSelect = (gift: any) => {

    }

    const setError = (error: any) => {

    }

    const onValidateAdd = () => {
        return true
        /*
        if (product.REQUIRED_MIN_PRICE && (priceBasketWithoutSpecial.value < product.REQUIRED_MIN_PRICE)) {
            alert('Сумма заказа меньше ' + product.REQUIRED_MIN_PRICE)
            return false
        }
        if (product.GIFTS?.length && !basketInputProps.value.GIFT) {
            alert('Необоходимо выбрать подарок')
            return false
        }
        if (validateAdd) {
            const res = validateAdd()
            if (res !== true) {
                setError(res)
                return false
            }
        }
        setError(false)
        return true

         */
    }

    const onBasketAdd = async () => {
        if (!onValidateAdd || onValidateAdd()) {
            basketOp({
                action: 'add',
                item: basketItem.value,
                quantity: 1
                // props: basketProps
            })
        }
    }

    const onBasketQuantity = (value: number | string | null) => {
        value = toInt(value)
        if (value >= 0) {
            basketOp({
                action: 'quantity',
                productId: productId,
                quantity: value,
            })
        }
    }

    const router = useRouter()

    const onDetailsDefault = () => {
        if (_onDetailsRoute) {
            router.push('/product/' + product.value.ID)
        }
        console.log("OPEN PRODUCT", product)
    }

    const onDetailsCallback = () => {
        if (onDetails) {
            onDetails(product.value)
        } else if (onDetailsBehavior === 'basket') {
            onBasketAdd()
        } else if (onDetailsBehavior === 'product') {
            if (onDetailsProduct) {
                onDetailsProduct(product.value)
            } else {
                onDetailsDefault()
            }
        }
    }

    const calc = ref({} as ProductDiscountsCalculation)

    const {imageResolve} = useImager()

    const imageUrl = computed(() => imageResolve(product.value.IMAGE?.SRC, imageSize))

    const bindContainer = computed(() => {
        const res: any = {
            class: {}
        }

        res.class['--border-style-' + props.borderStyle] = true;
        res.class['--orientation-' + props.orientation] = true;
        res.class['--viewmode-' + props.viewMode] = true;

        if (product.value) {
            res.class['section-' + product.value.IBLOCK_SECTION_ID] = true;
        }

        return res
    })

    const view = computed<ProductCardViewStyle>(() => {
        return viewModes[props.viewMode + '-' + props.orientation] || {} as ProductCardViewStyle
    })

    const flags = computed(() => product.value.FLAGS || [])

    const bindLinkListeners = computed(() => {
        const res: any = {}
        return res
    })

    const bindLinkComponent = computed(() => {
        let res = 'div'
        if (_onDetailsRoute) {
            res = 'a'
        }
        return res
    })

    const bindLink = computed(() => {
        const res: any = {}
        if (_onDetailsRoute) {
            res.href = _onDetailsRoute(product.value)
        }
        return res
    })

    const descTeaser = computed(() => {
        return product.value.PREVIEW_TEXT
    })

    const basketMutating = computed(() => false)

    const priceAvailable = computed(() => {
        return product.value.PRICE && product.value.PRICE.PRICE && !product.value.IS_SALE_SPECIAL
    })

    const weight = computed(() => {
        return product.value.PROPERTIES.WEIGHT
    })

    const onClick = (e: Event & { target: HTMLInputElement }) => {
        e.preventDefault()
        e.stopPropagation()
        if (!e.target.closest('.prevent-click')) {
            onDetailsCallback()
        }
    }

    const setItems = computed(() => product.value.getSetItems())

    const availableSchedule = computed(() => {
        return product.value.getSchedule()
    })

    /*
    watch(() => basketItemsModelsByProduct.value[productId], () => {
        if (basketItemsModelsByProduct.value[productId]) {
            basketItem.value = basketItemsModelsByProduct.value[productId] as BasketItemModel
        } else {
            basketItem.value = useBasketItemModel({
                PRODUCT_ID: product.value.ID,
                QUANTITY: 1
            } as BasketItem, product.value)
        }
    }, {immediate: true})

    watch(() => benefitStore.basketBenefitsHash, () => {
        // console.log('basketRulesActiveHash', basketRulesActiveHash.value)
        calc.value = calculateProductDiscounts(product.value, calcOptions)
    }, {immediate: true})

     */


    return {
        availableSchedule,
        onClick,
        product,
        calc,
        weight,
        priceAvailable,
        basketItem,
        basketMutating,
        basketProps,
        basketInputProps,
        basketQuantity,
        onGiftSelect,
        onBasketAdd,
        onBasketQuantity,
        onValidateAdd,
        openProduct: onDetails,
        setError,
        basketItemChildrenByProductId,
        imageUrl,
        bindContainer,
        bindLink,
        bindLinkListeners,
        view,
        flags,
        descTeaser,
        bindLinkComponent,
        setItems,
        inBasket,
        onMouseEnter,
        onMouseLeave,
        hover,
        now
    }
}


export type UseProductActionsProps = {
    product: ProductElementModel
    hover?: boolean
}

export function useProductActions(props: UseProductActionsProps) {

    const favStore = useShopFavStore()
    const {favProductIds} = storeToRefs(favStore)

    const {product, hover} = toRefs(props)

    const inFav = computed(() => !!favProductIds.value[product.value.ID.toString()])
    const favMutating = computed(() => false)

    const favToggle = () => {
        favStore.favToggle(product.value.ID)
    }

    return {
        product,
        inFav,
        favMutating,
        favToggle
    }
}


