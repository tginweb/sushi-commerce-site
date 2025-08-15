import {JsonFragmentName} from './fragments'

// @ts-nocheck
/* istanbul ignore file */
/* tslint:disable */
/* eslint-disable */

export type Scalars = {
    Boolean: boolean,
    String: string,
    Int: number,
    Float: number,
    ID: string,
    Json: any,
}

export interface AccessError {
    authRedirect: (Scalars['Boolean'] | null)
    data: (Scalars['Json'] | null)
    fieldMessage: (Scalars['String'] | null)
    message: (Scalars['String'] | null)
    messages: (Scalars['String'] | null)[]
    name: (ErrorNameEnum | null)
    rel: (Scalars['Json'] | null)
    type: (ErrorTypeEnum | null)
    __typename: 'AccessError'
}

export interface ActionMobile {
    addBacklink: (Scalars['Boolean'] | null)
    addSession: (Scalars['Boolean'] | null)
    await: (Scalars['Boolean'] | null)
    code: (Scalars['String'] | null)
    replace: (Scalars['Boolean'] | null)
    title: (Scalars['String'] | null)
    titleAuto: (Scalars['Boolean'] | null)
    url: (Scalars['String'] | null)
    __typename: 'ActionMobile'
}

export interface ActionWeb {
    code: (Scalars['String'] | null)
    title: (Scalars['String'] | null)
    url: (Scalars['String'] | null)
    __typename: 'ActionWeb'
}

export interface AppClient {
    CLIENT_ID: (Scalars['String'] | null)
    CURRENT_SESSION_ID: (Scalars['String'] | null)
    DEBUG_PARAMS: (AppClientDebugParams | null)
    ID: Scalars['Int']
    MOBILE_PUSH_TOKEN: (Scalars['String'] | null)
    SESSION_ID: (Scalars['String'] | null)
    TOKEN: (Scalars['String'] | null)
    USER_ID: (Scalars['Int'] | null)
    WEB_PUSH_TOKEN: (Scalars['String'] | null)
    __typename: 'AppClient'
}

export interface AppClientDebugParams {
    eventsTransport: (Scalars['Json'] | null)
    __typename: 'AppClientDebugParams'
}

export interface AppProductRecordset {
    info: QueryInfo
    nodes: Product[]
    __typename: 'AppProductRecordset'
}

export type AttrPaymentTypeEnum = 'card' | 'cash' | 'online'

export type AttrTimeModeEnum = 'custom' | 'nearest'

export type AttrTransportTypeEnum = 'courier' | 'pickup'

export interface Basket {
    BASE_PRICE: (Scalars['Float'] | null)
    CLIENT_CHANGED_AT: Scalars['Int']
    COUNT: (Scalars['Int'] | null)
    GIFTS: (Scalars['Int'] | null)[]
    HASH: (Scalars['String'] | null)
    ITEMS: BasketItem[]
    MIN_PRICE: (Scalars['Float'] | null)
    MIN_PRICE_REACHED: (Scalars['Boolean'] | null)
    OFFERS: ((SpecialOffer | null)[] | null)
    PRICE: (Scalars['Float'] | null)
    QUANTITY: (Scalars['Float'] | null)
    SYNCED: (Scalars['Boolean'] | null)
    TS: (Scalars['String'] | null)
    WEIGHT: (Scalars['Float'] | null)
    __typename: 'Basket'
}

export interface BasketBuildItem {
    ELEMENT: (Product | null)
    PRODUCT_ID: (Scalars['Int'] | null)
    QUANTITY: (Scalars['Int'] | null)
    __typename: 'BasketBuildItem'
}

export interface BasketItem {
    BASE_PRICE: Scalars['Float']
    BENEFIT: BasketItemBenefitType
    BUILD: ((BasketBuildItem | null)[] | null)
    CLIENT_CHANGED_AT: Scalars['Int']
    CLIENT_ID: Scalars['String']
    COMMENT: (Scalars['String'] | null)
    DESC: (Scalars['String'] | null)
    DISABLE: (Scalars['Boolean'] | null)
    DISABLE_REASON: (Scalars['String'] | null)
    DISCOUNTS: BasketItemDiscount[]
    ELEMENT: (Product | null)
    FINAL_PRICE: Scalars['Float']
    FINAL_PRICE_BASE: (Scalars['Float'] | null)
    ID: Scalars['Int']
    INPUT_PROPS_HASH: (Scalars['String'] | null)
    MEASURE_NAME: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    ORDER_ID: (Scalars['Int'] | null)
    PAID: (Scalars['Boolean'] | null)
    PARENT_ID: Scalars['Int']
    PRICE: Scalars['Float']
    PRICE_BASE: Scalars['Float']
    PRODUCT_ID: Scalars['Int']
    PROPS: ((BasketItemProp | null)[] | null)
    QUANTITY: Scalars['Float']
    UUID: (Scalars['String'] | null)
    __typename: 'BasketItem'
}

export type BasketItemBenefitType = 'gift' | 'special'

export interface BasketItemDiscount {
    AMOUNT: Scalars['Float']
    BASE_PRICE: Scalars['Float']
    DISCOUNTED_PRICE: Scalars['Float']
    RULE: (Scalars['String'] | null)
    TARGET: BasketItemDiscountTarget
    TYPE: BasketItemDiscountType
    __typename: 'BasketItemDiscount'
}

export type BasketItemDiscountTarget = 'basket' | 'delivery' | 'product' | 'section' | 'total'

export type BasketItemDiscountType = 'fixed' | 'percent'

export interface BasketItemProp {
    CODE: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    VALUE: (Scalars['Json'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'BasketItemProp'
}

export interface BasketRule {
    ACTIONS: BasketRuleActionInterface[]
    ALL_STOP: (Scalars['Boolean'] | null)
    CAPTION: (Scalars['String'] | null)
    CHILDREN: BasketRule[]
    CODE: Scalars['String']
    CONDITIONS: BasketRuleCondition[]
    HOTEST: (Scalars['Boolean'] | null)
    ID: (Scalars['Int'] | null)
    LEVEL_STOP: (Scalars['Boolean'] | null)
    NAME: (Scalars['String'] | null)
    NAME_TEMPLATE: (Scalars['String'] | null)
    PARENT: (Scalars['String'] | null)
    PERCENT: (Scalars['Int'] | null)
    TYPE: BasketRuleType
    __typename: 'BasketRule'
}

export interface BasketRuleActionDiscount {
    AMOUNT: Scalars['Int']
    AMOUNT_SURCHARGE: Scalars['Int']
    ID: (Scalars['Int'] | null)
    MODE: DiscountModeEnum
    NAME: (Scalars['String'] | null)
    PRODUCT_IDS: Scalars['Int'][]
    SECTION_IDS: Scalars['Int'][]
    TARGET: DiscountTargetEnum
    TYPE: BasketRuleActionTypeEnum
    __typename: 'BasketRuleActionDiscount'
}

export type BasketRuleActionInterface = (BasketRuleActionDiscount) & { __isUnion?: true }

export type BasketRuleActionTypeEnum = 'discount' | 'gift'

export interface BasketRuleCondition {
    CHILDREN: BasketRuleCondition[]
    CODE: (Scalars['String'] | null)
    ID: (Scalars['Int'] | null)
    IN: (Scalars['Json'][] | null)
    MAX: (Scalars['Int'] | null)
    MIN: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    NOT: (Scalars['Boolean'] | null)
    TYPE: BasketRuleConditionTypeEnum
    VALUE: (Scalars['Json'] | null)
    __typename: 'BasketRuleCondition'
}

export type BasketRuleConditionTypeEnum = 'attr_value' | 'bonus_filled' | 'coupons_filled' | 'delivery_id' | 'group_and' | 'group_or' | 'order_price' | 'payment_type' | 'paysystem_id' | 'product_id' | 'section_id' | 'transport_type'

export type BasketRuleType = 'common' | 'discount' | 'gift'

export interface BasketRulesResulBenefitProduct {
    PRODUCT_ID: (Scalars['Int'] | null)
    QUANTITY: (Scalars['Int'] | null)
    TYPE: BasketRulesResulBenefitProductType
    __typename: 'BasketRulesResulBenefitProduct'
}

export type BasketRulesResulBenefitProductType = 'productGift' | 'productSpecial'

export interface BasketRulesResult {
    ALLOW: (Scalars['String'] | null)[]
    BENEFIT_PRODUCTS: BasketRulesResulBenefitProduct[]
    DENY: (Scalars['String'] | null)[]
    DISCOUNTS_BASKET: BasketRuleActionDiscount[]
    DISCOUNTS_DELIVERY: BasketRuleActionDiscount[]
    DISCOUNTS_PRODUCT: BasketRuleActionDiscount[]
    DISCOUNTS_SECTION: BasketRuleActionDiscount[]
    DISCOUNTS_TOTAL: BasketRuleActionDiscount[]
    __typename: 'BasketRulesResult'
}

export interface BonusLevel {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    PROPERTIES: BonusLevelProps
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'BonusLevel'
}

export interface BonusLevelProps {
    ACCUMULATION_PERCENT: (Scalars['Int'] | null)
    COLOR: (Scalars['String'] | null)
    MAX_USE_PERCENT: (Scalars['Int'] | null)
    MONTH_SPENT_MAX: (Scalars['Int'] | null)
    MONTH_SPENT_MIN: (Scalars['Int'] | null)
    ORDERS_SUMM: (Scalars['String'] | null)
    __typename: 'BonusLevelProps'
}

export interface ClientCardRecordset {
    info: QueryInfo
    nodes: SaleClientCard[]
    __typename: 'ClientCardRecordset'
}

export interface ClientEmit {
    body: (Scalars['String'] | null)
    cls: (Scalars['String'] | null)
    createdAt: Scalars['Json']
    eventData: (Scalars['Json'] | null)
    eventGroup: (Scalars['String'] | null)
    eventName: (Scalars['String'] | null)
    id: (Scalars['Int'] | null)
    message: (Scalars['String'] | null)
    targetClientId: (Scalars['String'] | null)
    targetUserId: (Scalars['Int'] | null)
    title: (Scalars['String'] | null)
    __typename: 'ClientEmit'
}

export interface ClientNotice {
    actionItems: MenuItem[]
    body: (Scalars['String'] | null)
    bodyHtml: (Scalars['String'] | null)
    cls: (Scalars['String'] | null)
    createdAt: Scalars['Json']
    eventData: (Scalars['Json'] | null)
    eventGroup: (Scalars['String'] | null)
    eventName: (Scalars['String'] | null)
    haveBody: (Scalars['Boolean'] | null)
    id: Scalars['Int']
    image: (Scalars['String'] | null)
    isReaded: (Scalars['Boolean'] | null)
    message: (Scalars['String'] | null)
    offerId: (Scalars['Int'] | null)
    showAs: (Scalars['String'] | null)
    targetClientId: (Scalars['String'] | null)
    targetCode: (Scalars['String'] | null)
    targetUserId: (Scalars['Int'] | null)
    title: (Scalars['String'] | null)
    __typename: 'ClientNotice'
}

export interface Command {
    code: (Scalars['String'] | null)
    confirm: (Scalars['Boolean'] | null)
    params: (Scalars['Json'] | null)
    path: (Scalars['String'] | null)
    type: (Scalars['String'] | null)
    __typename: 'Command'
}

export interface CommonError {
    data: (Scalars['Json'] | null)
    fieldMessage: (Scalars['String'] | null)
    message: (Scalars['String'] | null)
    messages: (Scalars['String'] | null)[]
    name: (ErrorNameEnum | null)
    rel: (Scalars['Json'] | null)
    type: (ErrorTypeEnum | null)
    __typename: 'CommonError'
}

export interface CompanyOffice {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    PROPERTIES: CompanyOfficeProps
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'CompanyOffice'
}

export interface CompanyOfficeProps {
    ADDRESS: (Scalars['String'] | null)
    COORDINATES: (Coordinates | null)
    EMAIL: (Scalars['String'] | null)
    PHONES: Scalars['String'][]
    REQUISITES: (ElementPropValueHtml | null)
    ROLES: ListValue[]
    SERVICE_ID: (Scalars['String'] | null)
    WORKTIME: (Scalars['String'] | null)
    __typename: 'CompanyOfficeProps'
}

export interface CompanyVacancy {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'CompanyVacancy'
}

export interface Condition {
    eq: (Scalars['Json'] | null)
    gt: (Scalars['Json'] | null)
    lt: (Scalars['Json'] | null)
    path: (Scalars['String'] | null)
    __typename: 'Condition'
}

export interface ConstructorBuild {
    CONSTRUCTOR_CODE: (Scalars['String'] | null)
    CONSTRUCTOR_URL: (Scalars['String'] | null)
    SOSTAV: ((ConstructorBuildItem | null)[] | null)
    __typename: 'ConstructorBuild'
}

export interface ConstructorBuildItem {
    ELEMENT: (ConstructorElement | null)
    ELEMENT_ID: (Scalars['Int'] | null)
    QUANTITY: (Scalars['Int'] | null)
    __typename: 'ConstructorBuildItem'
}

export interface ConstructorElement {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    BENEFITS: ProductBenefit[]
    BUILD: (ConstructorBuild | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    FLAGS: ProductFlag[]
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    IS_SALE_SPECIAL: (Scalars['Boolean'] | null)
    MEASURE: (ProductMeasure | null)
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    OFFERS: ((Product | null)[] | null)
    OFFER_PARENT_ELEMENT: (Product | null)
    PARENT: (Product | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    PRICE: ProductPrice
    ROOT_SECTION: (Section | null)
    SALES_COUNT: (Scalars['Int'] | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SET_ITEMS: ProductSetItem[]
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: ((ProductTag | null)[] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    WEIGHT: (Scalars['Int'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'ConstructorElement'
}

export interface Coordinates {
    LAT: Scalars['Float']
    LON: Scalars['Float']
    __typename: 'Coordinates'
}

export interface Coupon {
    COUPON: (Scalars['String'] | null)
    ID: Scalars['Int']
    NAME: (Scalars['String'] | null)
    PRODUCT: (Product | null)
    PRODUCT_ID: (Scalars['Int'] | null)
    __typename: 'Coupon'
}

export interface CourierState {
    ARRIVAL_TIME: (Scalars['String'] | null)
    ARRIVAL_TIME_CAPTION: (Scalars['String'] | null)
    CAR_COLOR: (Scalars['String'] | null)
    CAR_NUMBER: (Scalars['String'] | null)
    COORDS: (Scalars['Json'] | null)
    __typename: 'CourierState'
}

export type DateFormatEnum = 'date' | 'datetime'

export interface DeliveryCalculate {
    ADDRESS_COORDS: (Scalars['String'] | null)
    DELIVERY_ADDRESS: (Scalars['String'] | null)
    DELIVERY_HASH: (Scalars['String'] | null)
    ID: Scalars['Int']
    NEED_TIME: (Scalars['Int'] | null)
    NEED_TIME_FORMATTED: (Scalars['String'] | null)
    ORDER_ID: (Scalars['Float'] | null)
    ORDER_PRICE: (Scalars['Int'] | null)
    PHONE: (Scalars['String'] | null)
    REQUEST_DELTA: (Scalars['Int'] | null)
    REQUEST_TIME: (Scalars['Int'] | null)
    REQUEST_TIME_FORMATTED: (Scalars['String'] | null)
    RES_DELIVER_PRICE: (Scalars['Float'] | null)
    RES_OFFICE_1C_ID: (Scalars['Int'] | null)
    RES_OFFICE_ID: (Scalars['Int'] | null)
    RES_STATUS: (MessageTypeEnum | null)
    RES_TIME: (Scalars['Int'] | null)
    RES_TIME_FORMATTED: (Scalars['String'] | null)
    TIME_MODE: (TimeModeEnum | null)
    TRANSPORT_TYPE: (Scalars['String'] | null)
    VORDER_ID: (Scalars['Int'] | null)
    __typename: 'DeliveryCalculate'
}

export interface DeliveryComputed {
    CALCULATE_DESCRIPTION: (Scalars['Json'] | null)
    CALCULATE_ERRORS: (Scalars['Json'] | null)
    CALC_TIMESTAMP: (Scalars['Int'] | null)
    DELIVERY_DISCOUNT_PRICE: (Scalars['String'] | null)
    DELIVERY_DISCOUNT_PRICE_FORMATED: (Scalars['String'] | null)
    ID: Scalars['Int']
    NAME: (Scalars['String'] | null)
    PERIOD_TEXT: (Scalars['String'] | null)
    PRICE: (Scalars['Float'] | null)
    PRICE_FORMATED: (Scalars['String'] | null)
    SERVICE: (DeliveryService | null)
    __typename: 'DeliveryComputed'
}

export interface DeliveryService {
    ID: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PARENT_ID: (Scalars['Int'] | null)
    TRANSPORT_TYPE: (DeliveryTransportTypeEnum | null)
    __typename: 'DeliveryService'
}

export type DeliveryTransportTypeEnum = 'courier' | 'pickup'

export interface DeliveryZone {
    ACTIVE: (Scalars['Boolean'] | null)
    CODE: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    ID: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PREVIEW_TEXT: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    __typename: 'DeliveryZone'
}

export interface Discount {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    PROPERTIES: DiscountProps
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'Discount'
}

export type DiscountModeEnum = 'fixed' | 'percent'

export interface DiscountProps {
    ACTION_DISCOUNT_PERCENT: (Scalars['Int'] | null)
    ACTION_PRODUCT_IDS: Scalars['Int'][]
    ACTION_PRODUCT_IDS_ENTITIES: Element[]
    ACTION_SECTION_IDS: Scalars['String'][]
    CONDITION: DiscountPropsCONDITION[]
    CONDITION_ORDER_PRICE_FROM: Scalars['Int'][]
    CONDITION_TRANSPORT_TYPE: Scalars['String'][]
    DATE: (Scalars['String'] | null)
    HOTEST: (Scalars['Boolean'] | null)
    NAME_TEMPLATE: (Scalars['String'] | null)
    __typename: 'DiscountProps'
}

export interface DiscountPropsCONDITION {
    DATE: (Scalars['String'] | null)
    ORDER_PRICE_FROM: (Scalars['Int'] | null)
    TRANSPORT_TYPE: (Scalars['String'] | null)
    __typename: 'DiscountPropsCONDITION'
}

export type DiscountTargetEnum = 'basket' | 'delivery' | 'product' | 'total'

export interface Element {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'Element'
}

export interface ElementMeta {
    DESCRIPTION: (Scalars['String'] | null)
    KEYWORDS: (Scalars['String'] | null)
    PAGE_TITLE: (Scalars['String'] | null)
    TITLE: (Scalars['String'] | null)
    __typename: 'ElementMeta'
}

export interface ElementPropValueHtml {
    TEXT: (Scalars['String'] | null)
    TYPE: (Scalars['String'] | null)
    __typename: 'ElementPropValueHtml'
}

export interface ElementPropValueWithDesc {
    DESC: (Scalars['String'] | null)
    VALUE: (Scalars['String'] | null)
    __typename: 'ElementPropValueWithDesc'
}

export interface EntityInfo {
    name: (Scalars['String'] | null)
    role: (Scalars['String'] | null)
    sectionPaths: (Scalars['String'] | null)
    type: (Scalars['String'] | null)
    urls: EntityInfoUrls
    __typename: 'EntityInfo'
}

export interface EntityInfoUrls {
    index: (Scalars['String'] | null)
    section: (Scalars['String'] | null)
    view: (Scalars['String'] | null)
    __typename: 'EntityInfoUrls'
}

export interface EntityProp {
    CODE: (Scalars['String'] | null)
    DESC: (Scalars['Json'] | null)
    FILE: (Image | null)
    FILES: ((Image | null)[] | null)
    ID: (Scalars['Int'] | null)
    MUL: (Scalars['Boolean'] | null)
    NAME: (Scalars['String'] | null)
    TYPE: (Scalars['String'] | null)
    VAL: (Scalars['Json'] | null)
    VAL_ENUM_ID: (Scalars['Json'] | null)
    VAL_ID: (Scalars['Json'] | null)
    __typename: 'EntityProp'
}

export type ErrorInterface = (AccessError | CommonError | ExternalServiceError | FormError | OtpError | RateError) & { __isUnion?: true }

export type ErrorNameEnum = 'access_not_authorized' | 'access_restricted' | 'common' | 'common_input_error' | 'common_invalid_format' | 'common_not_found' | 'es_blocked' | 'es_rate_limit' | 'es_rejected' | 'field_format_invalid' | 'field_required' | 'otp_attempts' | 'otp_code_wrong' | 'rate_limit'

export type ErrorTypeEnum = 'access' | 'common' | 'external_service' | 'form' | 'otp' | 'rate'

export interface ExternalServiceError {
    authRedirect: (Scalars['Boolean'] | null)
    data: (Scalars['Json'] | null)
    fieldMessage: (Scalars['String'] | null)
    message: (Scalars['String'] | null)
    messages: (Scalars['String'] | null)[]
    name: (ErrorNameEnum | null)
    rel: (Scalars['Json'] | null)
    type: (ErrorTypeEnum | null)
    __typename: 'ExternalServiceError'
}

export interface FaqQuestion {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'FaqQuestion'
}

export interface FaqQuestionRecordset {
    info: QueryInfo
    nodes: FaqQuestion[]
    __typename: 'FaqQuestionRecordset'
}

export interface FormError {
    data: (Scalars['Json'] | null)
    fieldLabel: (Scalars['String'] | null)
    fieldMessage: (Scalars['String'] | null)
    fieldName: (Scalars['String'] | null)
    message: (Scalars['String'] | null)
    messages: (Scalars['String'] | null)[]
    name: (ErrorNameEnum | null)
    rel: (Scalars['Json'] | null)
    type: (ErrorTypeEnum | null)
    __typename: 'FormError'
}

export interface GeoObject {
    address_full: (Scalars['String'] | null)
    address_original: (Scalars['String'] | null)
    address_short: (Scalars['String'] | null)
    area: (Scalars['String'] | null)
    area_fias_id: (Scalars['String'] | null)
    area_format: (Scalars['String'] | null)
    area_original: (Scalars['String'] | null)
    city: (Scalars['String'] | null)
    city_fias_id: (Scalars['String'] | null)
    city_format: (Scalars['String'] | null)
    city_original: (Scalars['String'] | null)
    district: (Scalars['String'] | null)
    district_fias_id: (Scalars['String'] | null)
    district_format: (Scalars['String'] | null)
    district_original: (Scalars['String'] | null)
    geo_lat: (Scalars['Float'] | null)
    geo_lon: (Scalars['Float'] | null)
    house: (Scalars['String'] | null)
    house_fias_id: (Scalars['String'] | null)
    house_format: (Scalars['String'] | null)
    house_original: (Scalars['String'] | null)
    region: (Scalars['String'] | null)
    region_fias_id: (Scalars['String'] | null)
    region_format: (Scalars['String'] | null)
    region_original: (Scalars['String'] | null)
    street: (Scalars['String'] | null)
    street_fias_id: (Scalars['String'] | null)
    street_format: (Scalars['String'] | null)
    street_original: (Scalars['String'] | null)
    street_path_full: (Scalars['String'] | null)
    street_path_short: (Scalars['String'] | null)
    __typename: 'GeoObject'
}

export interface Image {
    FILE_SIZE: (Scalars['String'] | null)
    ID: (Scalars['ID'] | null)
    ORIGINAL_NAME: (Scalars['String'] | null)
    SRC: (Scalars['String'] | null)
    SRC_DEFAULT: (Scalars['String'] | null)
    __typename: 'Image'
}

export interface ListValue {
    CODE: Scalars['String']
    ID: Scalars['Int']
    VALUE: Scalars['String']
    __typename: 'ListValue'
}

export interface Menu {
    children: ((MenuItem | null)[] | null)
    code: (Scalars['String'] | null)
    id: (Scalars['String'] | null)
    __typename: 'Menu'
}

export interface MenuItem {
    badge: (Scalars['Json'] | null)
    bgColor: (Scalars['String'] | null)
    blank: (Scalars['Boolean'] | null)
    children: ((MenuItem | null)[] | null)
    color: (Scalars['String'] | null)
    command: (Command | null)
    dense: (Scalars['Boolean'] | null)
    disable: (Scalars['Boolean'] | null)
    display: ((Scalars['String'] | null)[] | null)
    entityId: (Scalars['String'] | null)
    entityType: (Scalars['String'] | null)
    flat: (Scalars['Boolean'] | null)
    icon: (Scalars['String'] | null)
    id: (Scalars['String'] | null)
    image: (Image | null)
    imageId: (Scalars['Int'] | null)
    infoLabel: (Scalars['String'] | null)
    label: (Scalars['String'] | null)
    loading: (Scalars['Boolean'] | null)
    native: (Scalars['Boolean'] | null)
    onClick: (Scalars['Json'] | null)
    outline: (Scalars['Boolean'] | null)
    params: (Scalars['Json'] | null)
    parent: (Scalars['String'] | null)
    roles: ((Scalars['String'] | null)[] | null)
    textColor: (Scalars['String'] | null)
    url: (Scalars['String'] | null)
    width: (Scalars['String'] | null)
    __typename: 'MenuItem'
}

export interface MenuItemMobile {
    action: (ActionMobile | null)
    backgroundColor: (Scalars['String'] | null)
    badge: (Scalars['Json'] | null)
    color: (Scalars['String'] | null)
    condition: (Condition | null)
    icon: (Scalars['String'] | null)
    id: (Scalars['String'] | null)
    image: (Image | null)
    imageId: (Scalars['Int'] | null)
    label: (Scalars['String'] | null)
    labelColor: (Scalars['String'] | null)
    link: (Scalars['Boolean'] | null)
    outline: (Scalars['Boolean'] | null)
    outlineColor: (Scalars['String'] | null)
    outlineWidth: (Scalars['Int'] | null)
    params: (Scalars['Json'] | null)
    parent: (Scalars['String'] | null)
    roles: (Scalars['Json'] | null)
    templatable: (Scalars['Boolean'] | null)
    templatableProps: ((Scalars['String'] | null)[] | null)
    __typename: 'MenuItemMobile'
}

export interface Message {
    actions: MenuItem[]
    category: (Scalars['String'] | null)
    code: (Scalars['String'] | null)
    data: (Scalars['Json'] | null)
    duration: (Scalars['Int'] | null)
    id: (Scalars['String'] | null)
    message: (Scalars['String'] | null)
    messages: ((Scalars['String'] | null)[] | null)
    name: (Scalars['String'] | null)
    notify: (Scalars['Boolean'] | null)
    rel: (Scalars['String'] | null)
    status: (Scalars['String'] | null)
    title: (Scalars['String'] | null)
    type: (MessageTypeEnum | null)
    __typename: 'Message'
}

export type MessageTypeEnum = 'error' | 'info' | 'success' | 'warning'

export interface Mutation {
    catalog_product_order: Response
    company_office_update: Response
    company_vacancy_order: Response
    notice_pub_push_send_queue: Response
    notice_pub_push_update_token: Response
    notice_pub_sync_readed: (NoticePubSyncReadedResult | null)
    review_order_guest_review: (ReviewOrderGuestReviewResult | null)
    review_service_review: (ReviewServiceReviewResult | null)
    sale_client_card_create: Response
    sale_client_card_delete: Response
    sale_client_card_update: Response
    sale_order_cancel: (SaleOrderCancelResult | null)
    sale_order_pay_online: (SaleOrderPayOnlineResult | null)
    sale_order_repeat: (SaleOrderRepeatResult | null)
    sale_profile_calc_delivery: (SaleProfileCalcDeliveryResult | null)
    sale_profile_default: (SaleProfileDefaultResult | null)
    sale_profile_delete: (SaleProfileDeleteResult | null)
    sale_profile_save: (SaleProfileSaveResult | null)
    sale_vorder_apply: (SaleVorderApplyResult | null)
    sale_vorder_basket: (SaleVorderBasketResult | null)
    sale_vorder_coupon: (SaleVorderCouponResult | null)
    sale_vorder_new: (SaleVorderNewResult | null)
    sale_vorder_reserve: (SaleVorderReserveResult | null)
    sale_vorder_submit: (SaleVorderSubmitResult | null)
    sale_vorder_sync: (SaleVorderSyncResult | null)
    user_app_client: Response
    user_auth_create_sa: Response
    user_auth_login_confirm: (UserAuthLoginConfirmResult | null)
    user_auth_login_request: (UserAuthLoginRequestResult | null)
    user_auth_login_start: (UserAuthLoginStartResult | null)
    user_logout: Response
    user_profile_all_filled: Response
    user_profile_birthday: Response
    user_profile_child: Response
    user_profile_email: Response
    user_profile_name: Response
    user_profile_save: Response
    __typename: 'Mutation'
}

export interface NoticePubSyncReadedResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: ((ClientNotice | null)[] | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'NoticePubSyncReadedResult'
}

export interface Offer {
    ACTIONS_MOBILE: ((MenuItemMobile | null)[] | null)
    ACTIONS_WEB: ((MenuItem | null)[] | null)
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    BANNER_HOR_DESKTOP: (Image | null)
    BANNER_HOR_MOBILE: (Image | null)
    BANNER_INTERNAL_TEXT: (Scalars['String'] | null)
    BANNER_SQUARE: (Image | null)
    CODE: (Scalars['String'] | null)
    CONTENT_IMAGE: (Image | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    DISCOUNT_ID: (Scalars['Int'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    IS_HOT: (Scalars['Boolean'] | null)
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    OFFER_NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SLIDES: ((OfferSlide | null)[] | null)
    SORT: (Scalars['Int'] | null)
    STARTUP_SHOW: (Scalars['String'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    VARS: (Scalars['Json'] | null)
    VID: (Scalars['String'] | null)
    VIEW_MODE: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'Offer'
}

export interface OfferRecordset {
    info: QueryInfo
    nodes: Offer[]
    __typename: 'OfferRecordset'
}

export interface OfferSlide {
    BG_COLOR: (Scalars['String'] | null)
    CODE: (Scalars['String'] | null)
    CONTENT_HTML: (Scalars['String'] | null)
    CONTENT_IMAGE: (Image | null)
    CONTENT_TYPE: (Scalars['String'] | null)
    ID: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    __typename: 'OfferSlide'
}

export interface OfficeRecordset {
    info: QueryInfo
    nodes: CompanyOffice[]
    __typename: 'OfficeRecordset'
}

export interface Order {
    ACCESS_HASH: (Scalars['String'] | null)
    ACCOUNT_NUMBER: (Scalars['String'] | null)
    ACTIONS: (Scalars['Json'] | null)
    ADDRESS_FOR_1C: (Scalars['String'] | null)
    ATTR: OrderAttributesValue
    ATTRS: OrderAttribute[]
    BASKET: BasketItem[]
    BONUSES: (Scalars['Int'] | null)
    BUYER_NAME: (Scalars['String'] | null)
    CANCEL_REASONS: ((OrderCancelReason | null)[] | null)
    CONTRACT_NUM: (Scalars['String'] | null)
    COUPONS: Coupon[]
    COURIER_STATE: (CourierState | null)
    CSTATUS_COLOR: (Scalars['String'] | null)
    CSTATUS_ID: (Scalars['String'] | null)
    CSTATUS_NAME: (Scalars['String'] | null)
    DATE_FORMATTED: (Scalars['Json'] | null)
    DATE_INSERT: (Scalars['Json'] | null)
    DATE_PAYED: (Scalars['Json'] | null)
    DATE_TIME_FORMATTED: (Scalars['Json'] | null)
    DATE_UPDATE: (Scalars['Json'] | null)
    DELIVERY: (DeliveryService | null)
    DELIVERY_ADDRESS_FULL: (Scalars['String'] | null)
    DELIVERY_CALCULATED: (Scalars['Boolean'] | null)
    DELIVERY_DATETIME: (Scalars['Json'] | null)
    DELIVERY_DEPARTMENT: (CompanyOffice | null)
    DELIVERY_FREE_FROM_PRICE: (Scalars['Int'] | null)
    DELIVERY_ID: (Scalars['Int'] | null)
    DISCOUNT_PERCENT: (Scalars['Float'] | null)
    DISCOUNT_REASON: (Scalars['String'] | null)
    EDU_GROUP_NUM: (Scalars['String'] | null)
    ID: (Scalars['Int'] | null)
    IS_ACTIVE: (Scalars['Boolean'] | null)
    IS_CANCELED: (Scalars['Boolean'] | null)
    IS_CAN_CANCEL: (Scalars['Boolean'] | null)
    IS_CAN_PAY: (Scalars['Boolean'] | null)
    IS_CAN_PAY_BILL: (Scalars['Boolean'] | null)
    IS_CAN_PAY_ONLINE: (Scalars['Boolean'] | null)
    IS_FINISHED: (Scalars['Boolean'] | null)
    IS_PAID: (Scalars['Boolean'] | null)
    PAYMENTS: ((Payment | null)[] | null)
    PAYSYSTEM: (Paysystem | null)
    PAYSYSTEM_ID: (Scalars['Int'] | null)
    PAYSYSTEM_IS_ONLINE: (Scalars['Boolean'] | null)
    PAY_LINK: (Scalars['String'] | null)
    PERSON_TYPE_ID: (Scalars['Int'] | null)
    PICKUP_DEPARTMENT: (CompanyOffice | null)
    PRICE: (Scalars['Float'] | null)
    PRICE_BASKET: (Scalars['Float'] | null)
    PRICE_BASKET_BASE: (Scalars['Float'] | null)
    PRICE_DELIVERY: (Scalars['Float'] | null)
    PRICE_DELIVERY_BASE: (Scalars['Float'] | null)
    PRICE_DISCOUNT: (Scalars['Float'] | null)
    PRICE_PAY: (Scalars['Float'] | null)
    PRICE_PAY_BASE: (Scalars['Float'] | null)
    PRICE_TOTAL: (Scalars['Float'] | null)
    PRICE_TOTAL_BASE: (Scalars['Float'] | null)
    SCOPE: (OrderScope | null)
    SCOPE_ENTITY: (Command | null)
    SECRET_URL: (Scalars['String'] | null)
    SERVICE_ID: (Scalars['Int'] | null)
    STATUS: (OrderStatus | null)
    STATUS_COLOR: (Scalars['String'] | null)
    STATUS_ID: (Scalars['String'] | null)
    STATUS_NAME: (Scalars['String'] | null)
    STUDENT_FIO: (Scalars['String'] | null)
    SYNCED: (Scalars['Boolean'] | null)
    TS: (Scalars['String'] | null)
    URL: (Scalars['String'] | null)
    USER: (User | null)
    USER_DESCRIPTION: (Scalars['String'] | null)
    USER_ID: (Scalars['Int'] | null)
    __typename: 'Order'
}

export interface OrderAttribute {
    CODE: OrderAttributeCodeEnum
    DEFAULT_VALUE: (Scalars['Json'] | null)
    KIND: OrderAttributeKindEnum
    NAME: (Scalars['String'] | null)
    OPTIONS: ((OrderAttributeOption | null)[] | null)
    TYPE: OrderAttributeTypeEnum
    VALUE: (Scalars['Json'] | null)
    VALUE_VIEW: (Scalars['Json'] | null)
    __typename: 'OrderAttribute'
}

export type OrderAttributeCodeEnum = 'ADDRESS' | 'ADDRESS_IS_CUSTOM' | 'ADDRESS_SOURCE' | 'APP_VERSION' | 'BENEFIT_TYPE' | 'BONUSES' | 'CASH_SUM' | 'CITY' | 'CITY_FIAS_ID' | 'COMMENT' | 'DATA' | 'DATE' | 'DATETIME' | 'DELIVERY_DEPARTMENT' | 'DELIVERY_FREE_FROM_PRICE' | 'DELIVERY_FREE_UPDATED_TIME' | 'DELIVERY_ID' | 'DELIVERY_PRICE' | 'DEPARTMENT_SERVICE_ID' | 'DETAILS' | 'DISCOUNT_PERCENT' | 'DISCOUNT_REASON' | 'EMAIL' | 'ENTRANCE' | 'FIO' | 'FLAT' | 'FLOOR' | 'GIFTS_LIST' | 'HOUSE' | 'HOUSE_COORDS' | 'HOUSE_FIAS_ID' | 'INTERCOM' | 'LIFT' | 'LOCATION' | 'NEED_CONFIRM' | 'PAYMENT_TYPE' | 'PAY_SYSTEM_ID' | 'PERSONS_NUMBER' | 'PHONE' | 'PICKUP_DEPARTMENT' | 'PRIVATE_HOUSE' | 'PROFILE_COMMENT' | 'PROFILE_DEFAULT' | 'PROFILE_ID' | 'PROMOCODE' | 'RECEIVER_ANOTHER' | 'RECEIVER_NAME' | 'RECEIVER_PHONE' | 'RESERVE_AVAILABLE_TIME' | 'RESERVE_NEED_TIME' | 'RESERVE_REQUEST_TIME' | 'RESERVE_STATUS' | 'RESERVE_SUCCESS_HASH' | 'RESERVE_SUCCESS_REQUEST_TIME' | 'ROISTAT' | 'SERVICE_SEND' | 'SERVICE_SEND_START' | 'SETTLEMENT' | 'SETTLEMENT_FIAS_ID' | 'SOURCE' | 'STREET' | 'STREET_COORDS' | 'STREET_FIAS_ID' | 'STREET_PATH' | 'STRUCT' | 'TEST_TIME' | 'TIME' | 'TIME_MODE' | 'TRANSPORT_TYPE' | 'USER_DESCRIPTION' | 'UUID' | 'VORDER_ID' | 'WITH_OPERATOR'

export type OrderAttributeKindEnum = 'field' | 'prop'

export interface OrderAttributeOption {
    DESCRIPTION: (Scalars['String'] | null)
    DISABLE: (Scalars['Boolean'] | null)
    ICON: (Scalars['String'] | null)
    ID: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    NAME_SHORT: (Scalars['String'] | null)
    SORT: (Scalars['Int'] | null)
    VALUE: (Scalars['Json'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'OrderAttributeOption'
}

export type OrderAttributeTypeEnum = 'boolean' | 'date' | 'enum' | 'json' | 'location' | 'number' | 'string'

export interface OrderAttributesValue {
    ADDRESS: (Scalars['String'] | null)
    ADDRESS_IS_CUSTOM: (Scalars['Boolean'] | null)
    ADDRESS_IS_CUSTOM_STRING: (Scalars['String'] | null)
    ADDRESS_SOURCE: (Scalars['String'] | null)
    APP_VERSION: (Scalars['String'] | null)
    BENEFIT_TYPE: (Scalars['String'] | null)
    BENEFIT_TYPE_STRING: (Scalars['String'] | null)
    BONUSES: (Scalars['Int'] | null)
    CASH_SUM: (Scalars['Int'] | null)
    CITY: (Scalars['String'] | null)
    CITY_FIAS_ID: (Scalars['String'] | null)
    COMMENT: (Scalars['String'] | null)
    DATA: (OrderData | null)
    DATE: (Scalars['String'] | null)
    DATETIME: (Scalars['Int'] | null)
    DELIVERY_DEPARTMENT: (Scalars['Int'] | null)
    DELIVERY_FREE_FROM_PRICE: (Scalars['String'] | null)
    DELIVERY_FREE_UPDATED_TIME: (Scalars['Int'] | null)
    DELIVERY_ID: (Scalars['Int'] | null)
    DELIVERY_ID_STRING: (Scalars['String'] | null)
    DELIVERY_PRICE: (Scalars['Int'] | null)
    DEPARTMENT_SERVICE_ID: (Scalars['Int'] | null)
    DETAILS: (Scalars['Boolean'] | null)
    DETAILS_STRING: (Scalars['String'] | null)
    DISCOUNT_PERCENT: (Scalars['Int'] | null)
    DISCOUNT_REASON: (Scalars['String'] | null)
    EMAIL: (Scalars['String'] | null)
    ENTRANCE: (Scalars['String'] | null)
    FIO: (Scalars['String'] | null)
    FLAT: (Scalars['String'] | null)
    FLOOR: (Scalars['String'] | null)
    GIFTS_LIST: (Scalars['String'] | null)
    HOUSE: (Scalars['String'] | null)
    HOUSE_COORDS: (Coordinates | null)
    HOUSE_COORDS_STRING: (Scalars['String'] | null)
    HOUSE_FIAS_ID: (Scalars['String'] | null)
    INTERCOM: (Scalars['String'] | null)
    LIFT: (Scalars['Boolean'] | null)
    LIFT_STRING: (Scalars['String'] | null)
    LOCATION: (Scalars['String'] | null)
    NEED_CONFIRM: (Scalars['Boolean'] | null)
    NEED_CONFIRM_STRING: (Scalars['String'] | null)
    PAYMENT_TYPE: (AttrPaymentTypeEnum | null)
    PAYMENT_TYPE_STRING: (Scalars['String'] | null)
    PAY_SYSTEM_ID: (Scalars['Int'] | null)
    PAY_SYSTEM_ID_STRING: (Scalars['String'] | null)
    PERSONS_NUMBER: (Scalars['Int'] | null)
    PHONE: (Scalars['String'] | null)
    PICKUP_DEPARTMENT: (Scalars['Int'] | null)
    PRIVATE_HOUSE: (Scalars['Boolean'] | null)
    PRIVATE_HOUSE_STRING: (Scalars['String'] | null)
    PROFILE_COMMENT: (Scalars['String'] | null)
    PROFILE_DEFAULT: (Scalars['Boolean'] | null)
    PROFILE_DEFAULT_STRING: (Scalars['String'] | null)
    PROFILE_ID: (Scalars['Int'] | null)
    PROMOCODE: (Scalars['String'] | null)
    RECEIVER_ANOTHER: (Scalars['Boolean'] | null)
    RECEIVER_ANOTHER_STRING: (Scalars['String'] | null)
    RECEIVER_NAME: (Scalars['String'] | null)
    RECEIVER_PHONE: (Scalars['String'] | null)
    RESERVE_AVAILABLE_TIME: (Scalars['Int'] | null)
    RESERVE_NEED_TIME: (Scalars['Int'] | null)
    RESERVE_REQUEST_TIME: (Scalars['Int'] | null)
    RESERVE_STATUS: (Scalars['String'] | null)
    RESERVE_SUCCESS_HASH: (Scalars['String'] | null)
    RESERVE_SUCCESS_REQUEST_TIME: (Scalars['Int'] | null)
    ROISTAT: (Scalars['String'] | null)
    SERVICE_SEND: (Scalars['Boolean'] | null)
    SERVICE_SEND_START: (Scalars['Int'] | null)
    SERVICE_SEND_STRING: (Scalars['String'] | null)
    SETTLEMENT: (Scalars['String'] | null)
    SETTLEMENT_FIAS_ID: (Scalars['String'] | null)
    SOURCE: (Scalars['String'] | null)
    STREET: (Scalars['String'] | null)
    STREET_COORDS: (Coordinates | null)
    STREET_COORDS_STRING: (Scalars['String'] | null)
    STREET_FIAS_ID: (Scalars['String'] | null)
    STREET_PATH: (Scalars['String'] | null)
    STRUCT: (Scalars['Json'] | null)
    TEST_TIME: (Scalars['Int'] | null)
    TEST_TIME_STRING: (Scalars['String'] | null)
    TIME: (Scalars['String'] | null)
    TIME_MODE: (AttrTimeModeEnum | null)
    TIME_MODE_STRING: (Scalars['String'] | null)
    TRANSPORT_TYPE: (AttrTransportTypeEnum | null)
    USER_DESCRIPTION: (Scalars['String'] | null)
    UUID: (Scalars['String'] | null)
    VORDER_ID: (Scalars['Int'] | null)
    WITH_OPERATOR: (Scalars['Boolean'] | null)
    WITH_OPERATOR_STRING: (Scalars['String'] | null)
    __typename: 'OrderAttributesValue'
}

export interface OrderCancelReason {
    CODE: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    __typename: 'OrderCancelReason'
}

export interface OrderData {
    paramArray: (Scalars['Json'] | null)
    paramInt: (Scalars['Int'] | null)
    paramString: (Scalars['String'] | null)
    __typename: 'OrderData'
}

export interface OrderProfile {
    ATTR: OrderProfileAttributesValue
    ATTRS: OrderAttribute[]
    CAPTION: (Scalars['String'] | null)
    COMPANY_ID: (Scalars['Int'] | null)
    COORDS: (Coordinates | null)
    DELIVERY_FREE_FROM_PRICE: Scalars['Int']
    ID: Scalars['Int']
    IS_DEFAULT: (Scalars['Boolean'] | null)
    NAME: Scalars['String']
    PERSON_TYPE: (PersonType | null)
    PERSON_TYPE_ID: Scalars['Int']
    USER: (User | null)
    USER_ID: Scalars['Int']
    __typename: 'OrderProfile'
}

export interface OrderProfileAttributesValue {
    ADDRESS: (Scalars['String'] | null)
    ADDRESS_IS_CUSTOM: (Scalars['Boolean'] | null)
    ADDRESS_SOURCE: (Scalars['String'] | null)
    CITY: (Scalars['String'] | null)
    CITY_FIAS_ID: (Scalars['String'] | null)
    ENTRANCE: (Scalars['String'] | null)
    FLAT: (Scalars['String'] | null)
    FLOOR: (Scalars['String'] | null)
    HOUSE: (Scalars['String'] | null)
    HOUSE_COORDS: (Coordinates | null)
    HOUSE_FIAS_ID: (Scalars['String'] | null)
    LIFT: (Scalars['Boolean'] | null)
    PRIVATE_HOUSE: (Scalars['Boolean'] | null)
    PROFILE_COMMENT: (Scalars['String'] | null)
    PROFILE_DEFAULT: (Scalars['Boolean'] | null)
    RECEIVER_ANOTHER: (Scalars['Boolean'] | null)
    RECEIVER_NAME: (Scalars['String'] | null)
    RECEIVER_PHONE: (Scalars['String'] | null)
    SETTLEMENT: (Scalars['String'] | null)
    SETTLEMENT_FIAS_ID: (Scalars['String'] | null)
    STREET: (Scalars['String'] | null)
    STREET_COORDS: (Coordinates | null)
    STREET_FIAS_ID: (Scalars['String'] | null)
    STREET_PATH: (Scalars['String'] | null)
    STRUCT: (Scalars['Json'] | null)
    TEST_TIME: (Scalars['Int'] | null)
    __typename: 'OrderProfileAttributesValue'
}

export interface OrderProfileRecordset {
    info: QueryInfo
    nodes: OrderProfile[]
    __typename: 'OrderProfileRecordset'
}

export interface OrderScope {
    CONTRACT_NUM: (Scalars['String'] | null)
    ENTITY_ID: (Scalars['Int'] | null)
    ENTITY_TYPE: (Scalars['String'] | null)
    __typename: 'OrderScope'
}

export interface OrderStatus {
    COLOR: (Scalars['String'] | null)
    ID: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    SORT: (Scalars['Int'] | null)
    TYPE: (Scalars['String'] | null)
    __typename: 'OrderStatus'
}

export interface OtpError {
    data: (Scalars['Json'] | null)
    fieldMessage: (Scalars['String'] | null)
    message: (Scalars['String'] | null)
    messages: (Scalars['String'] | null)[]
    name: (ErrorNameEnum | null)
    param: (Scalars['Int'] | null)
    rel: (Scalars['Json'] | null)
    type: (ErrorTypeEnum | null)
    __typename: 'OtpError'
}

export interface Page {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CONTENT_CHUNKS: ((PageContentChunk | null)[] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATA_CHUNKS: ((PageDataChunk | null)[] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'Page'
}

export interface PageContentChunk {
    CODE: (Scalars['String'] | null)
    GROUP: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    TYPE: (Scalars['String'] | null)
    VALUE: (Scalars['String'] | null)
    __typename: 'PageContentChunk'
}

export interface PageDataChunk {
    CODE: (Scalars['String'] | null)
    TYPE: (Scalars['String'] | null)
    VALUE: (Scalars['Json'] | null)
    __typename: 'PageDataChunk'
}

export interface PageRecordset {
    info: QueryInfo
    nodes: Page[]
    __typename: 'PageRecordset'
}

export interface Payment {
    DATE_PAID: (Scalars['Json'] | null)
    ID: (Scalars['Int'] | null)
    IS_PAID: (Scalars['Boolean'] | null)
    ORDER_ID: (Scalars['String'] | null)
    ORDER_URL: (Scalars['String'] | null)
    PAYSYSTEM: (Paysystem | null)
    PAYSYSTEM_ID: (Scalars['Int'] | null)
    PAY_NAV: (Scalars['Json'] | null)
    PS_INVOICE_ID: (Scalars['String'] | null)
    PS_STATUS: (Scalars['String'] | null)
    PS_STATUS_CODE: (Scalars['String'] | null)
    PS_STATUS_ID: (Scalars['String'] | null)
    PS_STATUS_NAME: (Scalars['String'] | null)
    SUM: (Scalars['Float'] | null)
    SUM_PAID: (Scalars['Float'] | null)
    __typename: 'Payment'
}

export interface PaymentType {
    CODE: (Scalars['String'] | null)
    ICON: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    __typename: 'PaymentType'
}

export interface Paysystem {
    CODE: (Scalars['String'] | null)
    DESCRIPTION: (Scalars['String'] | null)
    ID: (Scalars['Int'] | null)
    IS_BILL: (Scalars['Boolean'] | null)
    IS_INNER: (Scalars['Boolean'] | null)
    IS_ONLINE: (Scalars['Boolean'] | null)
    IS_ONLINE_DELAYED: (Scalars['Boolean'] | null)
    NAME: (Scalars['String'] | null)
    __typename: 'Paysystem'
}

export interface PersonType {
    CODE: (Scalars['String'] | null)
    ID: (Scalars['Int'] | null)
    IS_COMPANY: (Scalars['Boolean'] | null)
    NAME: (Scalars['String'] | null)
    RESTRICTED: (Scalars['Boolean'] | null)
    SORT: (Scalars['Int'] | null)
    __typename: 'PersonType'
}

export interface Product {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    BENEFITS: ProductBenefit[]
    BUILD: (ConstructorBuild | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    FLAGS: ProductFlag[]
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    IMAGE: (Image | null)
    IS_SALE_SPECIAL: (Scalars['Boolean'] | null)
    MEASURE: (ProductMeasure | null)
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    OFFERS: ((Product | null)[] | null)
    OFFER_PARENT_ELEMENT: (Product | null)
    PARENT: (Product | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    PRICE: ProductPrice
    PROPERTIES: ProductProps
    ROOT_SECTION: (Section | null)
    SALES_COUNT: (Scalars['Int'] | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SET_ITEMS: ProductSetItem[]
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: ((ProductTag | null)[] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    WEIGHT: (Scalars['Int'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'Product'
}

export interface ProductBenefit {
    IS_GIFT: (Scalars['Boolean'] | null)
    PRODUCT: (Product | null)
    PRODUCT_ID: Scalars['Int']
    QUANTITY: Scalars['Int']
    __typename: 'ProductBenefit'
}

export interface ProductFlag {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'ProductFlag'
}

export interface ProductMeasure {
    NAME: (Scalars['String'] | null)
    RATIO: (Scalars['Float'] | null)
    __typename: 'ProductMeasure'
}

export interface ProductPrice {
    DISCOUNTED: Scalars['Float']
    DISCOUNT_PERCENT: Scalars['Float']
    PRICE: Scalars['Float']
    __typename: 'ProductPrice'
}

export interface ProductProps {
    ADDITIVES: Scalars['Int'][]
    ADDITIVES_ENTITIES: Element[]
    AVAILABLE_SCHEDULE: ProductPropsAVAILABLE_SCHEDULE[]
    BELKI: (Scalars['String'] | null)
    BENEFITS: ProductPropsBENEFITS[]
    COMPONENT_IS: (ListValue | null)
    FLAG_ITEMS: Scalars['Int'][]
    FLAG_ITEMS_ENTITIES: Element[]
    HOLIDAY: (ListValue | null)
    IS_HIT: (Scalars['Boolean'] | null)
    KKAL: (Scalars['Int'] | null)
    NEW: (ListValue | null)
    PHOTOV2: (Image | null)
    ROLLS: Scalars['Int'][]
    ROLLS_ENTITIES: Element[]
    SALE_SPECIAL: (Scalars['Boolean'] | null)
    SERVICE_ID: (Scalars['String'] | null)
    SET_ITEMS: ProductPropsSET_ITEMS[]
    SOSTAV: ElementPropValueWithDesc[]
    TAGS: Scalars['Int'][]
    TAGS_ENTITIES: Element[]
    UGLEVODY: (Scalars['String'] | null)
    UPSALE_ELEMENTS: (Scalars['Int'] | null)
    UPSALE_ELEMENTS_ENTITY: (Element | null)
    UPSALE_SECTIONS: Scalars['String'][]
    WEIGHT: (Scalars['String'] | null)
    ZHIRY: (Scalars['String'] | null)
    __typename: 'ProductProps'
}

export interface ProductPropsAVAILABLE_SCHEDULE {
    DAY: (ListValue | null)
    FROM: (Scalars['String'] | null)
    TO: (Scalars['String'] | null)
    __typename: 'ProductPropsAVAILABLE_SCHEDULE'
}

export interface ProductPropsBENEFITS {
    IS_GIFT: (Scalars['Boolean'] | null)
    PRODUCT: (Scalars['Int'] | null)
    PRODUCT_ENTITY: (Element | null)
    QUANTITY: (Scalars['Int'] | null)
    __typename: 'ProductPropsBENEFITS'
}

export interface ProductPropsSET_ITEMS {
    PRODUCT_ID: (Scalars['Int'] | null)
    PRODUCT_ID_ENTITY: (Element | null)
    QUANTITY: (Scalars['String'] | null)
    __typename: 'ProductPropsSET_ITEMS'
}

export interface ProductSection {
    ACTIVE: (Scalars['Boolean'] | null)
    CHILDREN: Section[]
    CODE: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DEPTH_LEVEL: (Scalars['Int'] | null)
    DESCRIPTION: (Scalars['String'] | null)
    DESCRIPTION_TYPE: (Scalars['String'] | null)
    DETAIL_PICTURE: (Image | null)
    ELEMENT_CNT: (Scalars['Int'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_TYPE_ID: (Scalars['String'] | null)
    ID: Scalars['Int']
    LEFT_MARGIN: (Scalars['Int'] | null)
    META: (ElementMeta | null)
    NAME: (Scalars['String'] | null)
    PARENT: (ProductSection | null)
    PARENTS: ((ProductSection | null)[] | null)
    PICTURE: (Image | null)
    PROPERTIES: ProductSectionProps
    REPLACE_LINK: (Scalars['String'] | null)
    RIGHT_MARGIN: (Scalars['Int'] | null)
    SEARCHABLE_CONTENT: (Scalars['String'] | null)
    SECTION_PAGE_URL: (Scalars['String'] | null)
    SORT: (Scalars['Int'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    __typename: 'ProductSection'
}

export interface ProductSectionProps {
    CATEGORY_CLASS: (Scalars['String'] | null)
    DATA: (Scalars['Int'] | null)
    DATA_FORMATTED: (Scalars['String'] | null)
    DAY: (Scalars['Int'] | null)
    ENUM: (DeliveryTransportTypeEnum | null)
    FLOAT: (Scalars['Float'] | null)
    HINT: (Scalars['String'] | null)
    HTML: (Scalars['String'] | null)
    INT: (Scalars['Int'] | null)
    LIST: (ListValue | null)
    LOGIC: (Scalars['Boolean'] | null)
    MENU_BG_COLOR: (Scalars['String'] | null)
    MENU_COLOR: (Scalars['String'] | null)
    MENU_FOCUSED_ICON: (Image | null)
    MENU_ICON: (Image | null)
    M_CARD: (Scalars['String'] | null)
    NAV_TITLE: (Scalars['String'] | null)[]
    NAV_URL: (Scalars['String'] | null)[]
    REPLACE_LINK: (Scalars['String'] | null)
    SEARCH_IN: (Scalars['String'] | null)
    UPSALE_SECTIONS: (Scalars['Int'] | null)[]
    UPSALE_SECTIONS_ENTITY: (Section | null)[]
    __typename: 'ProductSectionProps'
}

export interface ProductSetItem {
    PRODUCT: (Product | null)
    PRODUCT_ID: Scalars['Int']
    QUANTITY: Scalars['Int']
    __typename: 'ProductSetItem'
}

export interface ProductTag {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'ProductTag'
}

export interface Query {
    camera_element_list: Webcam[]
    catalog_product_all: Product[]
    catalog_product_fav_list: Product[]
    catalog_product_fav_list2: Product[]
    catalog_product_list: Product[]
    catalog_product_recordset: (AppProductRecordset | null)
    catalog_product_search: (SearchResult | null)
    catalog_product_search_new: Product[]
    catalog_product_section_list: ProductSection[]
    catalog_product_single: Product
    company_office_list: CompanyOffice[]
    company_office_recordset: (OfficeRecordset | null)
    company_office_single: CompanyOffice
    company_vacancy_list: CompanyVacancy[]
    company_vacancy_recordset: (VacancyRecordset | null)
    company_vacancy_single: CompanyVacancy
    entity_info_list: ((EntityInfo | null)[] | null)
    faq_element_list: FaqQuestion[]
    faq_element_recordset: (FaqQuestionRecordset | null)
    faq_element_single: FaqQuestion
    geo_geocoder_location_by_address: (GeoObject | null)
    geo_geocoder_locations_by_coords: ((GeoObject | null)[] | null)
    list: ((ClientEmit | null)[] | null)
    menu_menus: ((Menu | null)[] | null)
    notice_pub_list: ClientNotice[]
    offer_common_list: Offer[]
    offer_list: Offer[]
    offer_recordset: (OfferRecordset | null)
    offer_single: Offer
    offer_user_list: Offer[]
    page_list: Page[]
    page_recordset: (PageRecordset | null)
    page_route: (Page | null)
    page_single: Page
    review_list: Review[]
    sale_bonus_level_list: BonusLevel[]
    sale_client_card_apply_by_phone: (SaleClientCard | null)
    sale_client_card_fetch: (SaleClientCard | null)
    sale_client_card_list: SaleClientCard[]
    sale_client_card_recordset: (ClientCardRecordset | null)
    sale_client_card_single: SaleClientCard
    sale_delivery_zones: DeliveryZone[]
    sale_order_active_list: Order[]
    sale_order_ensure_payment: (Payment | null)
    sale_order_statuses: (OrderStatus[] | null)
    sale_profile_list: OrderProfile[]
    sale_profile_recordset: (OrderProfileRecordset | null)
    sale_profile_single: OrderProfile
    sale_vorder_basket_products: ((Product | null)[] | null)
    sale_vorder_current: (VorderCurrent | null)
    sale_vorder_summary: (VorderSummary | null)
    search_suggestions: SearchSuggestion[]
    search_suggestions_popular: SearchSuggestion[]
    user_app_client: (AppClient | null)
    user_fetch: (User | null)
    user_session: (UserSession | null)
    __typename: 'Query'
}

export interface QueryInfo {
    limit: (Scalars['Int'] | null)
    nextPage: (Scalars['Int'] | null)
    page: (Scalars['Int'] | null)
    pages: (Scalars['Int'] | null)
    total: (Scalars['Int'] | null)
    __typename: 'QueryInfo'
}

export interface RateError {
    data: (Scalars['Json'] | null)
    fieldMessage: (Scalars['String'] | null)
    message: (Scalars['String'] | null)
    messages: (Scalars['String'] | null)[]
    name: (ErrorNameEnum | null)
    rel: (Scalars['Json'] | null)
    ttl: (Scalars['Int'] | null)
    type: (ErrorTypeEnum | null)
    __typename: 'RateError'
}

export interface Response {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (Scalars['Json'] | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'Response'
}

export interface ResponseRate {
    limited: (Scalars['Boolean'] | null)
    ttl: (Scalars['Int'] | null)
    __typename: 'ResponseRate'
}

export interface ResponseRedirect {
    restricted: (Scalars['Boolean'] | null)
    ttl: (Scalars['Int'] | null)
    __typename: 'ResponseRedirect'
}

export interface ResponseState {
    events: (Scalars['Json'] | null)
    messages: Message[]
    rate: (ResponseRate | null)
    redirect: (ResponseRedirect | null)
    __typename: 'ResponseState'
}

export interface Review {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CHILDREN: ((Review | null)[] | null)
    CODE: (Scalars['String'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    ELEMENT: (Element | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    ORDER: (Order | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'Review'
}

export interface ReviewOrderGuestReviewResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (ReviewOrderGuestReviewResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'ReviewOrderGuestReviewResult'
}

export interface ReviewOrderGuestReviewResultPayload {
    departmentId: (Scalars['Int'] | null)
    departmentName: (Scalars['String'] | null)
    redirectUrl: (Scalars['String'] | null)
    __typename: 'ReviewOrderGuestReviewResultPayload'
}

export interface ReviewServiceReviewResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (Review | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'ReviewServiceReviewResult'
}

export interface SaleClientCard {
    BONUSES: (Scalars['Int'] | null)
    BONUSES_EXPIRE: (Scalars['Int'] | null)
    BONUSES_EXPIRE_FORMATTED: (Scalars['String'] | null)
    BONUSES_PERCENT: (Scalars['Float'] | null)
    CLIENT_PHONE: (Scalars['String'] | null)
    DISCOUNTS: Discount[]
    DIS_FIRST_ORDER: (Scalars['Float'] | null)
    DIS_SELF_PICKUP: (Scalars['Float'] | null)
    EXPIRED: (Scalars['Boolean'] | null)
    FETCHED: (Scalars['Boolean'] | null)
    FETCHED_ACTUAL: (Scalars['Boolean'] | null)
    FETCHED_TIME: (Scalars['Int'] | null)
    FETCHED_TIME_FORMATTED: (Scalars['String'] | null)
    ID: Scalars['Int']
    LEVEL: (BonusLevel | null)
    LEVEL_CODE: (Scalars['String'] | null)
    LEVEL_NAME: (Scalars['String'] | null)
    MONTH_SPENT: (Scalars['Float'] | null)
    TRANSPORT: (DeliveryTransportTypeEnum | null)
    USER_ID: (Scalars['Int'] | null)
    __typename: 'SaleClientCard'
}

export interface SaleOrderCancelResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (Order[] | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleOrderCancelResult'
}

export interface SaleOrderPayOnlineResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleOrderPayOnlineResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleOrderPayOnlineResult'
}

export interface SaleOrderPayOnlineResultPayload {
    action: (ActionWeb | null)
    order: (Order | null)
    __typename: 'SaleOrderPayOnlineResultPayload'
}

export interface SaleOrderRepeatResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (Order | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleOrderRepeatResult'
}

export interface SaleProfileCalcDeliveryResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (DeliveryCalculate | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleProfileCalcDeliveryResult'
}

export interface SaleProfileDefaultResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (OrderProfile | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleProfileDefaultResult'
}

export interface SaleProfileDeleteResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (Scalars['Int'] | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleProfileDeleteResult'
}

export interface SaleProfileSaveResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (OrderProfile | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleProfileSaveResult'
}

export interface SaleVorderApplyResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleVorderApplyResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleVorderApplyResult'
}

export interface SaleVorderApplyResultPayload {
    vorder: (Vorder | null)
    __typename: 'SaleVorderApplyResultPayload'
}

export interface SaleVorderBasketResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleVorderBasketResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleVorderBasketResult'
}

export interface SaleVorderBasketResultPayload {
    vorder: (Vorder | null)
    __typename: 'SaleVorderBasketResultPayload'
}

export interface SaleVorderCouponResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleVorderCouponResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleVorderCouponResult'
}

export interface SaleVorderCouponResultPayload {
    coupon: (Coupon | null)
    vorder: (Vorder | null)
    __typename: 'SaleVorderCouponResultPayload'
}

export interface SaleVorderNewResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleVorderNewResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleVorderNewResult'
}

export interface SaleVorderNewResultPayload {
    vorder: (Vorder | null)
    __typename: 'SaleVorderNewResultPayload'
}

export interface SaleVorderReserveResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleVorderReserveResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleVorderReserveResult'
}

export interface SaleVorderReserveResultPayload {
    calc: (DeliveryCalculate | null)
    vorder: (Vorder | null)
    __typename: 'SaleVorderReserveResultPayload'
}

export interface SaleVorderSubmitResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleVorderSubmitResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleVorderSubmitResult'
}

export interface SaleVorderSubmitResultPayload {
    order: (Order | null)
    orderId: (Scalars['Int'] | null)
    orderUrl: (Scalars['String'] | null)
    vorder: (Vorder | null)
    __typename: 'SaleVorderSubmitResultPayload'
}

export interface SaleVorderSyncResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (SaleVorderSyncResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'SaleVorderSyncResult'
}

export interface SaleVorderSyncResultPayload {
    vorder: (Vorder | null)
    __typename: 'SaleVorderSyncResultPayload'
}

export interface SearchResult {
    ELEMENTS: Product[]
    SECTIONS: Section[]
    __typename: 'SearchResult'
}

export interface SearchSuggestion {
    data: (SearchSuggestionData | null)
    label: (Scalars['String'] | null)
    value: (Scalars['String'] | null)
    __typename: 'SearchSuggestion'
}

export interface SearchSuggestionData {
    entityId: (Scalars['Int'] | null)
    entityRole: (Scalars['String'] | null)
    entityTitle: (Scalars['String'] | null)
    entityTypeCode: (Scalars['String'] | null)
    entityTypeId: (Scalars['String'] | null)
    entityTypeName: (Scalars['String'] | null)
    hint: (Scalars['String'] | null)
    type: (Scalars['String'] | null)
    __typename: 'SearchSuggestionData'
}

export interface Section {
    ACTIVE: (Scalars['Boolean'] | null)
    CHILDREN: Section[]
    CODE: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DEPTH_LEVEL: (Scalars['Int'] | null)
    DESCRIPTION: (Scalars['String'] | null)
    DESCRIPTION_TYPE: (Scalars['String'] | null)
    DETAIL_PICTURE: (Image | null)
    ELEMENT_CNT: (Scalars['Int'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_TYPE_ID: (Scalars['String'] | null)
    ID: Scalars['Int']
    LEFT_MARGIN: (Scalars['Int'] | null)
    META: (ElementMeta | null)
    NAME: (Scalars['String'] | null)
    PARENT: (Section | null)
    PARENTS: ((Section | null)[] | null)
    PICTURE: (Image | null)
    REPLACE_LINK: (Scalars['String'] | null)
    RIGHT_MARGIN: (Scalars['Int'] | null)
    SEARCHABLE_CONTENT: (Scalars['String'] | null)
    SECTION_PAGE_URL: (Scalars['String'] | null)
    SORT: (Scalars['Int'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    __typename: 'Section'
}

export interface SpecialOffer {
    ELEMENT: (Product | null)
    ELEMENT_ID: (Scalars['Int'] | null)
    MIN_PRICE: (Scalars['Int'] | null)
    MODE: (Scalars['String'] | null)
    TYPE: (Scalars['String'] | null)
    TYPE_INFO: (SpecialOfferType | null)
    __typename: 'SpecialOffer'
}

export interface SpecialOfferType {
    CODE: (Scalars['String'] | null)
    COLOR: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    __typename: 'SpecialOfferType'
}

export type TimeModeEnum = 'custom' | 'nearest'

export interface User {
    AVATAR: (UserAvatar | null)
    EMAIL: (Scalars['String'] | null)
    FAMILY: ((UserFamily | null)[] | null)
    GREETING_NAME: (Scalars['String'] | null)
    GROUPS_INFO: ((UserGroup | null)[] | null)
    ID: (Scalars['Int'] | null)
    LAST_NAME: (Scalars['String'] | null)
    LOGIN: (Scalars['String'] | null)
    LOGIN_FORMAT: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    NAME_FULL: (Scalars['String'] | null)
    NAME_TEASER: (Scalars['String'] | null)
    PERSONAL_BIRTHDAY: (Scalars['String'] | null)
    PERSONAL_PHOTO: (Image | null)
    PERSON_TYPE_ID: (Scalars['Int'] | null)
    PHONE: (Scalars['String'] | null)
    PHONE_FORMATTED: (Scalars['String'] | null)
    PROFILE_FILLED: (Scalars['Boolean'] | null)
    PROFILE_GIFT_USED: (Scalars['Boolean'] | null)
    PROMOCODE: (Scalars['String'] | null)
    PROPS: ((EntityProp | null)[] | null)
    ROLES: (Scalars['Json'] | null)
    SECOND_NAME: (Scalars['String'] | null)
    SESSION_ID: (Scalars['String'] | null)
    __typename: 'User'
}

export interface UserAuthConfirm {
    CODE: Scalars['String']
    COLOR: (Scalars['String'] | null)
    CONFIRM_CONTENT_MOBILE: (Scalars['String'] | null)
    CONFIRM_CONTENT_WEB: (Scalars['String'] | null)
    CONFIRM_STEPS: ((UserAuthConfirmStep | null)[] | null)
    ICON: (Scalars['String'] | null)
    LIST_BUTTON_MOBILE: (MenuItemMobile | null)
    LIST_BUTTON_WEB: (MenuItem | null)
    LIST_CAPTION: (Scalars['String'] | null)
    LIST_NAME: (Scalars['String'] | null)
    NAME: Scalars['String']
    RESEND_TITLE: (Scalars['String'] | null)
    __typename: 'UserAuthConfirm'
}

export interface UserAuthConfirmStep {
    CAPTION: (Scalars['String'] | null)
    CODE: (Scalars['String'] | null)
    NAME: (Scalars['String'] | null)
    __typename: 'UserAuthConfirmStep'
}

export interface UserAuthLoginConfirmResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (UserAuthLoginConfirmResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'UserAuthLoginConfirmResult'
}

export interface UserAuthLoginConfirmResultPayload {
    appClient: (AppClient | null)
    redirect: (Scalars['Json'] | null)
    sessionId: (Scalars['String'] | null)
    userId: (Scalars['Int'] | null)
    __typename: 'UserAuthLoginConfirmResultPayload'
}

export interface UserAuthLoginRequestResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (UserAuthLoginRequestResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'UserAuthLoginRequestResult'
}

export interface UserAuthLoginRequestResultPayload {
    id: (Scalars['String'] | null)
    sid: (Scalars['String'] | null)
    __typename: 'UserAuthLoginRequestResultPayload'
}

export interface UserAuthLoginStartResult {
    error: (ErrorInterface | null)
    errors: ErrorInterface[]
    payload: (UserAuthLoginStartResultPayload | null)
    state: ResponseState
    success: (Scalars['Boolean'] | null)
    __typename: 'UserAuthLoginStartResult'
}

export interface UserAuthLoginStartResultPayload {
    confirmModes: UserAuthConfirm[]
    __typename: 'UserAuthLoginStartResultPayload'
}

export interface UserAvatar {
    ELEMENT_ID: (Scalars['Int'] | null)
    IMAGE: (Image | null)
    __typename: 'UserAvatar'
}

export interface UserFamily {
    BIRTHDAY: (Scalars['String'] | null)
    ID: Scalars['Int']
    NAME: (Scalars['String'] | null)
    RELATION: (Scalars['String'] | null)
    USER_ID: (Scalars['String'] | null)
    __typename: 'UserFamily'
}

export interface UserGroup {
    ID: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    TYPE: (Scalars['String'] | null)
    __typename: 'UserGroup'
}

export interface UserSession {
    FUSER_ID: (Scalars['Int'] | null)
    SESSION_ID: (Scalars['String'] | null)
    USER_ID: (Scalars['Int'] | null)
    __typename: 'UserSession'
}

export interface VacancyRecordset {
    info: QueryInfo
    nodes: CompanyVacancy[]
    __typename: 'VacancyRecordset'
}

export interface Vorder {
    ATTR: OrderAttributesValue
    ATTRS: OrderAttribute[]
    BASKET: (Basket | null)
    BONUSES: (Scalars['Int'] | null)
    COUPONS: Coupon[]
    DELIVERY_DEPARTMENT_ID: (Scalars['Int'] | null)
    DEPARTMENT_ID: (Scalars['Int'] | null)
    EMAIL: (Scalars['String'] | null)
    FIELDS_RAW: (Scalars['Json'] | null)
    FUSER_ID: (Scalars['Int'] | null)
    ID: Scalars['Int']
    ORDER: (Order | null)
    ORDER_ID: (Scalars['Int'] | null)
    PHONE: (Scalars['String'] | null)
    PICKUP_DEPARTMENT_ID: (Scalars['Int'] | null)
    PROFILE_ID: (Scalars['Int'] | null)
    PROPS_RAW: (Scalars['Json'] | null)
    SESSION_ID: (Scalars['String'] | null)
    USER: (User | null)
    USER_ID: (Scalars['Int'] | null)
    __typename: 'Vorder'
}

export interface VorderCurrent {
    ATTR: OrderAttributesValue
    ATTRS: OrderAttribute[]
    BASKET: (Basket | null)
    BASKET_RULES: ((BasketRule | null)[] | null)
    BASKET_RULES_RESULT: ((BasketRulesResult | null)[] | null)
    BONUSES: (Scalars['Int'] | null)
    BONUSES_AVAILABLE: (Scalars['Int'] | null)
    BONUSES_PERCENT: (Scalars['Int'] | null)
    COUPONS: Coupon[]
    COUPON_CAN_ADD: (Scalars['Boolean'] | null)
    DELIVERIES: DeliveryComputed[]
    DELIVERY_CALCULATED: (Scalars['Boolean'] | null)
    DELIVERY_DEPARTMENT_ID: (Scalars['Int'] | null)
    DELIVERY_FREE_FROM_PRICE: (Scalars['Float'] | null)
    DEPARTMENTS: (CompanyOffice | null)[]
    DEPARTMENT_ID: (Scalars['Int'] | null)
    DISCOUNTS: Discount[]
    EMAIL: (Scalars['String'] | null)
    FIELDS_RAW: (Scalars['Json'] | null)
    FUSER_ID: (Scalars['Int'] | null)
    ID: Scalars['Int']
    ORDER: (Order | null)
    ORDER_ID: (Scalars['Int'] | null)
    PAYMENT_TYPES: PaymentType[]
    PERSON_TYPES: PersonType[]
    PHONE: (Scalars['String'] | null)
    PICKUP_DEPARTMENT_ID: (Scalars['Int'] | null)
    PROFILES: (OrderProfile | null)[]
    PROFILE_ID: (Scalars['Int'] | null)
    PROPS_RAW: (Scalars['Json'] | null)
    RESPONSE_STATE: (ResponseState | null)
    SESSION_ID: (Scalars['String'] | null)
    TRANSPORT_TYPES: DeliveryTransportTypeEnum[]
    TS: (Scalars['String'] | null)
    USER: (User | null)
    USER_ID: (Scalars['Int'] | null)
    __typename: 'VorderCurrent'
}

export interface VorderSummary {
    EMAIL: (Scalars['String'] | null)
    FUSER_ID: (Scalars['Int'] | null)
    ID: (Scalars['Int'] | null)
    PHONE: (Scalars['String'] | null)
    __typename: 'VorderSummary'
}

export interface Webcam {
    ACTIVE: (Scalars['Boolean'] | null)
    ACTIVE_FROM: (Scalars['Json'] | null)
    ACTIVE_TO: (Scalars['Int'] | null)
    CREATED_BY: (Scalars['Int'] | null)
    CREATED_USER_NAME: (Scalars['String'] | null)
    DATE_CREATE: (Scalars['Json'] | null)
    DETAIL_PICTURE: (Image | null)
    DETAIL_TEXT: (Scalars['String'] | null)
    DETAIL_TEXT_TYPE: (Scalars['String'] | null)
    EXTERNAL_ID: (Scalars['String'] | null)
    IBLOCK_CODE: (Scalars['String'] | null)
    IBLOCK_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_ID: (Scalars['Int'] | null)
    IBLOCK_SECTION_IDS: Scalars['Int'][]
    ID: Scalars['Int']
    META: (ElementMeta | null)
    MODIFIED_BY: (Scalars['Int'] | null)
    NAME: (Scalars['String'] | null)
    PATH: ((Section | null)[] | null)
    PREVIEW_PICTURE: (Image | null)
    PREVIEW_TEXT: (Scalars['String'] | null)
    PREVIEW_TEXT_TYPE: (Scalars['String'] | null)
    PROPERTIES: WebcamProps
    ROOT_SECTION: (Section | null)
    SECTION: (Section | null)
    SECTIONS: ((Section | null)[] | null)
    SHOW_COUNTER: (Scalars['Int'] | null)
    SHOW_COUNTER_START: (Scalars['Json'] | null)
    SORT: (Scalars['Int'] | null)
    TAGS: (Scalars['String'] | null)
    TIMESTAMP_X: (Scalars['Json'] | null)
    URL: (Scalars['String'] | null)
    XML_ID: (Scalars['String'] | null)
    __typename: 'Webcam'
}

export interface WebcamProps {
    CODE: (Scalars['String'] | null)
    __typename: 'WebcamProps'
}

export interface AccessErrorGenqlSelection{
    authRedirect?: boolean | number
    data?: boolean | number
    fieldMessage?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    rel?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ActionMobileGenqlSelection{
    addBacklink?: boolean | number
    addSession?: boolean | number
    await?: boolean | number
    code?: boolean | number
    replace?: boolean | number
    title?: boolean | number
    titleAuto?: boolean | number
    url?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ActionWebGenqlSelection{
    code?: boolean | number
    title?: boolean | number
    url?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface AppClientGenqlSelection{
    CLIENT_ID?: boolean | number
    CURRENT_SESSION_ID?: boolean | number
    DEBUG_PARAMS?: AppClientDebugParamsGenqlSelection
    ID?: boolean | number
    MOBILE_PUSH_TOKEN?: boolean | number
    SESSION_ID?: boolean | number
    TOKEN?: boolean | number
    USER_ID?: boolean | number
    WEB_PUSH_TOKEN?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface AppClientDebugParamsGenqlSelection{
    eventsTransport?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface AppProductRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: ProductGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketGenqlSelection{
    BASE_PRICE?: boolean | number
    CLIENT_CHANGED_AT?: boolean | number
    COUNT?: boolean | number
    GIFTS?: boolean | number
    HASH?: boolean | number
    ITEMS?: BasketItemGenqlSelection
    MIN_PRICE?: boolean | number
    MIN_PRICE_REACHED?: boolean | number
    OFFERS?: SpecialOfferGenqlSelection
    PRICE?: boolean | number
    QUANTITY?: boolean | number
    SYNCED?: boolean | number
    TS?: boolean | number
    WEIGHT?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketBuildItemGenqlSelection{
    ELEMENT?: ProductGenqlSelection
    PRODUCT_ID?: boolean | number
    QUANTITY?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketItemGenqlSelection{
    BASE_PRICE?: boolean | number
    BENEFIT?: boolean | number
    BUILD?: BasketBuildItemGenqlSelection
    CLIENT_CHANGED_AT?: boolean | number
    CLIENT_ID?: boolean | number
    COMMENT?: boolean | number
    DESC?: boolean | number
    DISABLE?: boolean | number
    DISABLE_REASON?: boolean | number
    DISCOUNTS?: BasketItemDiscountGenqlSelection
    ELEMENT?: ProductGenqlSelection
    FINAL_PRICE?: boolean | number
    FINAL_PRICE_BASE?: boolean | number
    ID?: boolean | number
    INPUT_PROPS_HASH?: boolean | number
    MEASURE_NAME?: boolean | number
    NAME?: boolean | number
    ORDER_ID?: boolean | number
    PAID?: boolean | number
    PARENT_ID?: boolean | number
    PRICE?: boolean | number
    PRICE_BASE?: boolean | number
    PRODUCT_ID?: boolean | number
    PROPS?: BasketItemPropGenqlSelection
    QUANTITY?: boolean | number
    UUID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketItemDiscountGenqlSelection{
    AMOUNT?: boolean | number
    BASE_PRICE?: boolean | number
    DISCOUNTED_PRICE?: boolean | number
    RULE?: boolean | number
    TARGET?: boolean | number
    TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketItemPropGenqlSelection{
    CODE?: boolean | number
    NAME?: boolean | number
    VALUE?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketRuleGenqlSelection{
    ACTIONS?: BasketRuleActionInterfaceGenqlSelection
    ALL_STOP?: boolean | number
    CAPTION?: boolean | number
    CHILDREN?: BasketRuleGenqlSelection
    CODE?: boolean | number
    CONDITIONS?: BasketRuleConditionGenqlSelection
    HOTEST?: boolean | number
    ID?: boolean | number
    LEVEL_STOP?: boolean | number
    NAME?: boolean | number
    NAME_TEMPLATE?: boolean | number
    PARENT?: boolean | number
    PERCENT?: boolean | number
    TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketRuleActionDiscountGenqlSelection{
    AMOUNT?: boolean | number
    AMOUNT_SURCHARGE?: boolean | number
    ID?: boolean | number
    MODE?: boolean | number
    NAME?: boolean | number
    PRODUCT_IDS?: boolean | number
    SECTION_IDS?: boolean | number
    TARGET?: boolean | number
    TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketRuleActionInterfaceGenqlSelection{
    ID?: boolean | number
    NAME?: boolean | number
    TYPE?: boolean | number
    on_BasketRuleActionDiscount?: BasketRuleActionDiscountGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketRuleConditionGenqlSelection{
    CHILDREN?: BasketRuleConditionGenqlSelection
    CODE?: boolean | number
    ID?: boolean | number
    IN?: boolean | number
    MAX?: boolean | number
    MIN?: boolean | number
    NAME?: boolean | number
    NOT?: boolean | number
    TYPE?: boolean | number
    VALUE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketRulesResulBenefitProductGenqlSelection{
    PRODUCT_ID?: boolean | number
    QUANTITY?: boolean | number
    TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BasketRulesResultGenqlSelection{
    ALLOW?: boolean | number
    BENEFIT_PRODUCTS?: BasketRulesResulBenefitProductGenqlSelection
    DENY?: boolean | number
    DISCOUNTS_BASKET?: BasketRuleActionDiscountGenqlSelection
    DISCOUNTS_DELIVERY?: BasketRuleActionDiscountGenqlSelection
    DISCOUNTS_PRODUCT?: BasketRuleActionDiscountGenqlSelection
    DISCOUNTS_SECTION?: BasketRuleActionDiscountGenqlSelection
    DISCOUNTS_TOTAL?: BasketRuleActionDiscountGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BonusLevelGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: BonusLevelActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: BonusLevelDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: BonusLevelDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: BonusLevelPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    PROPERTIES?: BonusLevelPropsGenqlSelection
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: BonusLevelShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: BonusLevelTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface BonusLevelPropsGenqlSelection{
    ACCUMULATION_PERCENT?: boolean | number
    COLOR?: boolean | number
    MAX_USE_PERCENT?: boolean | number
    MONTH_SPENT_MAX?: boolean | number
    MONTH_SPENT_MIN?: boolean | number
    ORDERS_SUMM?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CaptchaInput {ANSWER?: (Scalars['String'] | null),PROVIDER?: (Scalars['String'] | null),SID?: (Scalars['String'] | null),VALUE?: (Scalars['Json'] | null)}

export interface ClientCardFilterInput {BONUSES?: (IntFilterInput | null),BONUSES_EXPIRE?: (DateFilterInput | null),BONUSES_PERCENT?: (IntFilterInput | null),CLIENT_PHONE?: (StringFilterInput | null),DIS_FIRST_ORDER?: (IntFilterInput | null),DIS_SELF_PICKUP?: (IntFilterInput | null),FETCHED?: (Scalars['Boolean'] | null),FETCHED_TIME?: (DateFilterInput | null),ID?: (IntFilterInput | null),LEVEL_CODE?: (StringFilterInput | null),LEVEL_NAME?: (StringFilterInput | null),MONTH_SPENT?: (IntFilterInput | null),USER_ID?: (IntFilterInput | null)}

export interface ClientCardRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: SaleClientCardGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ClientEmitGenqlSelection{
    body?: boolean | number
    cls?: boolean | number
    createdAt?: { __args: ClientEmitCreatedAtArgs } | boolean | number
    eventData?: boolean | number
    eventGroup?: boolean | number
    eventName?: boolean | number
    id?: boolean | number
    message?: boolean | number
    targetClientId?: boolean | number
    targetUserId?: boolean | number
    title?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ClientNoticeGenqlSelection{
    actionItems?: MenuItemGenqlSelection
    body?: boolean | number
    bodyHtml?: boolean | number
    cls?: boolean | number
    createdAt?: { __args: ClientNoticeCreatedAtArgs } | boolean | number
    eventData?: boolean | number
    eventGroup?: boolean | number
    eventName?: boolean | number
    haveBody?: boolean | number
    id?: boolean | number
    image?: boolean | number
    isReaded?: boolean | number
    message?: boolean | number
    offerId?: boolean | number
    showAs?: boolean | number
    targetClientId?: boolean | number
    targetCode?: boolean | number
    targetUserId?: boolean | number
    title?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CommandGenqlSelection{
    code?: boolean | number
    confirm?: boolean | number
    params?: boolean | number
    path?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CommonErrorGenqlSelection{
    data?: boolean | number
    fieldMessage?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    rel?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CompanyOfficeGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: CompanyOfficeActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: CompanyOfficeDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: CompanyOfficeDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: CompanyOfficePreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    PROPERTIES?: CompanyOfficePropsGenqlSelection
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: CompanyOfficeShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: CompanyOfficeTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CompanyOfficeInput {CODE?: (Scalars['String'] | null),IBLOCK_ID?: (Scalars['Int'] | null),NAME?: (Scalars['String'] | null),PROPERTIES?: (CompanyOfficeInputProps | null)}

export interface CompanyOfficeInputProps {ADDRESS?: (Scalars['String'] | null),COORDINATES?: (CoordinatesInput | null),EMAIL?: (Scalars['String'] | null),GIS_URL?: (Scalars['String'] | null),MAIN?: (Scalars['String'] | null),PAYSYSTEM_ID?: (Scalars['Int'] | null),PHONES?: (Scalars['String'][] | null),REQUISITES?: (Scalars['String'] | null),ROLES?: (Scalars['Int'][] | null),SERVICE_ID?: (Scalars['String'] | null),WORKTIME?: (Scalars['String'] | null),WORKTIME_FRI?: (Scalars['String'] | null),WORKTIME_MON?: (Scalars['String'] | null),WORKTIME_SAT?: (Scalars['String'] | null),WORKTIME_SUN?: (Scalars['String'] | null),WORKTIME_THU?: (Scalars['String'] | null),WORKTIME_TUE?: (Scalars['String'] | null),WORKTIME_WED?: (Scalars['String'] | null)}

export interface CompanyOfficePropsGenqlSelection{
    ADDRESS?: boolean | number
    COORDINATES?: CoordinatesGenqlSelection
    EMAIL?: boolean | number
    PHONES?: boolean | number
    REQUISITES?: ElementPropValueHtmlGenqlSelection
    ROLES?: ListValueGenqlSelection
    SERVICE_ID?: boolean | number
    WORKTIME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CompanyVacancyGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: CompanyVacancyActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: CompanyVacancyDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: CompanyVacancyDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: CompanyVacancyPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: CompanyVacancyShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: CompanyVacancyTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ConditionGenqlSelection{
    eq?: boolean | number
    gt?: boolean | number
    lt?: boolean | number
    path?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ConstructorBuildGenqlSelection{
    CONSTRUCTOR_CODE?: boolean | number
    CONSTRUCTOR_URL?: boolean | number
    SOSTAV?: ConstructorBuildItemGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ConstructorBuildItemGenqlSelection{
    ELEMENT?: ConstructorElementGenqlSelection
    ELEMENT_ID?: boolean | number
    QUANTITY?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ConstructorElementGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: ConstructorElementActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    BENEFITS?: ProductBenefitGenqlSelection
    BUILD?: ConstructorBuildGenqlSelection
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: ConstructorElementDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: ConstructorElementDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    FLAGS?: ProductFlagGenqlSelection
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    IS_SALE_SPECIAL?: boolean | number
    MEASURE?: ProductMeasureGenqlSelection
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    OFFERS?: ProductGenqlSelection
    OFFER_PARENT_ELEMENT?: ProductGenqlSelection
    PARENT?: ProductGenqlSelection
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: ConstructorElementPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    PRICE?: ProductPriceGenqlSelection
    ROOT_SECTION?: SectionGenqlSelection
    SALES_COUNT?: boolean | number
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SET_ITEMS?: ProductSetItemGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: ConstructorElementShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: ProductTagGenqlSelection
    TIMESTAMP_X?: { __args: ConstructorElementTimestampXArgs } | boolean | number
    URL?: boolean | number
    WEIGHT?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CoordinatesGenqlSelection{
    LAT?: boolean | number
    LON?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CoordinatesInput {LAT?: (Scalars['Float'] | null),LON?: (Scalars['Float'] | null)}

export interface CouponGenqlSelection{
    COUPON?: boolean | number
    ID?: boolean | number
    NAME?: boolean | number
    PRODUCT?: ProductGenqlSelection
    PRODUCT_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface CourierStateGenqlSelection{
    ARRIVAL_TIME?: boolean | number
    ARRIVAL_TIME_CAPTION?: boolean | number
    CAR_COLOR?: boolean | number
    CAR_NUMBER?: boolean | number
    COORDS?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface DateFilterInput {eq?: (Scalars['String'] | null),gt?: (Scalars['String'] | null),in?: ((Scalars['String'] | null)[] | null),lt?: (Scalars['String'] | null),not?: (Scalars['String'] | null)}

export interface DeliveryCalculateGenqlSelection{
    ADDRESS_COORDS?: boolean | number
    DELIVERY_ADDRESS?: boolean | number
    DELIVERY_HASH?: boolean | number
    ID?: boolean | number
    NEED_TIME?: boolean | number
    NEED_TIME_FORMATTED?: boolean | number
    ORDER_ID?: boolean | number
    ORDER_PRICE?: boolean | number
    PHONE?: boolean | number
    REQUEST_DELTA?: boolean | number
    REQUEST_TIME?: boolean | number
    REQUEST_TIME_FORMATTED?: boolean | number
    RES_DELIVER_PRICE?: boolean | number
    RES_OFFICE_1C_ID?: boolean | number
    RES_OFFICE_ID?: boolean | number
    RES_STATUS?: boolean | number
    RES_TIME?: boolean | number
    RES_TIME_FORMATTED?: boolean | number
    TIME_MODE?: boolean | number
    TRANSPORT_TYPE?: boolean | number
    VORDER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface DeliveryComputedGenqlSelection{
    CALCULATE_DESCRIPTION?: boolean | number
    CALCULATE_ERRORS?: boolean | number
    CALC_TIMESTAMP?: boolean | number
    DELIVERY_DISCOUNT_PRICE?: boolean | number
    DELIVERY_DISCOUNT_PRICE_FORMATED?: boolean | number
    ID?: boolean | number
    NAME?: boolean | number
    PERIOD_TEXT?: boolean | number
    PRICE?: boolean | number
    PRICE_FORMATED?: boolean | number
    SERVICE?: DeliveryServiceGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface DeliveryServiceGenqlSelection{
    ID?: boolean | number
    NAME?: boolean | number
    PARENT_ID?: boolean | number
    TRANSPORT_TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface DeliveryZoneGenqlSelection{
    ACTIVE?: boolean | number
    CODE?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    ID?: boolean | number
    NAME?: boolean | number
    PREVIEW_TEXT?: { __args: DeliveryZonePreviewTextArgs } | boolean | number
    SORT?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface DiscountGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: DiscountActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: DiscountDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: DiscountDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: DiscountPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    PROPERTIES?: DiscountPropsGenqlSelection
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: DiscountShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: DiscountTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface DiscountPropsGenqlSelection{
    ACTION_DISCOUNT_PERCENT?: boolean | number
    ACTION_PRODUCT_IDS?: boolean | number
    ACTION_PRODUCT_IDS_ENTITIES?: ElementGenqlSelection
    ACTION_SECTION_IDS?: boolean | number
    CONDITION?: DiscountPropsCONDITIONGenqlSelection
    CONDITION_ORDER_PRICE_FROM?: boolean | number
    CONDITION_TRANSPORT_TYPE?: boolean | number
    DATE?: boolean | number
    HOTEST?: boolean | number
    NAME_TEMPLATE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface DiscountPropsCONDITIONGenqlSelection{
    DATE?: boolean | number
    ORDER_PRICE_FROM?: boolean | number
    TRANSPORT_TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ElementGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: ElementActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: ElementDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: ElementDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: ElementPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: ElementShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: ElementTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ElementInput {CODE?: (Scalars['String'] | null),IBLOCK_ID?: (Scalars['Int'] | null),NAME?: (Scalars['String'] | null)}

export interface ElementListFilterInput {ACTIVE?: (Scalars['Boolean'] | null),CODE?: (StringFilterInput | null),IBLOCK_CODE?: (StringFilterInput | null),IBLOCK_ID?: (IntFilterInput | null),IBLOCK_SECTION_ID?: (IntFilterInput | null),ID?: (IntFilterInput | null),NAME?: (StringFilterInput | null),SORT?: (IntFilterInput | null)}

export interface ElementMetaGenqlSelection{
    DESCRIPTION?: boolean | number
    KEYWORDS?: boolean | number
    PAGE_TITLE?: boolean | number
    TITLE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ElementPropValueHtmlGenqlSelection{
    TEXT?: boolean | number
    TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ElementPropValueWithDescGenqlSelection{
    DESC?: boolean | number
    VALUE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface EntityInfoGenqlSelection{
    name?: boolean | number
    role?: boolean | number
    sectionPaths?: boolean | number
    type?: boolean | number
    urls?: EntityInfoUrlsGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface EntityInfoUrlsGenqlSelection{
    index?: boolean | number
    section?: boolean | number
    view?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface EntityPropGenqlSelection{
    CODE?: boolean | number
    DESC?: boolean | number
    FILE?: ImageGenqlSelection
    FILES?: ImageGenqlSelection
    ID?: boolean | number
    MUL?: boolean | number
    NAME?: boolean | number
    TYPE?: boolean | number
    VAL?: boolean | number
    VAL_ENUM_ID?: boolean | number
    VAL_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ErrorInterfaceGenqlSelection{
    data?: boolean | number
    fieldMessage?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    rel?: boolean | number
    type?: boolean | number
    on_AccessError?: AccessErrorGenqlSelection
    on_CommonError?: CommonErrorGenqlSelection
    on_ExternalServiceError?: ExternalServiceErrorGenqlSelection
    on_FormError?: FormErrorGenqlSelection
    on_OtpError?: OtpErrorGenqlSelection
    on_RateError?: RateErrorGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ExternalServiceErrorGenqlSelection{
    authRedirect?: boolean | number
    data?: boolean | number
    fieldMessage?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    rel?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface FaqQuestionGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: FaqQuestionActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: FaqQuestionDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: FaqQuestionDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: FaqQuestionPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: FaqQuestionShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: FaqQuestionTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface FaqQuestionRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: FaqQuestionGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface FormErrorGenqlSelection{
    data?: boolean | number
    fieldLabel?: boolean | number
    fieldMessage?: boolean | number
    fieldName?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    rel?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface GeoObjectGenqlSelection{
    address_full?: boolean | number
    address_original?: boolean | number
    address_short?: boolean | number
    area?: boolean | number
    area_fias_id?: boolean | number
    area_format?: boolean | number
    area_original?: boolean | number
    city?: boolean | number
    city_fias_id?: boolean | number
    city_format?: boolean | number
    city_original?: boolean | number
    district?: boolean | number
    district_fias_id?: boolean | number
    district_format?: boolean | number
    district_original?: boolean | number
    geo_lat?: boolean | number
    geo_lon?: boolean | number
    house?: boolean | number
    house_fias_id?: boolean | number
    house_format?: boolean | number
    house_original?: boolean | number
    region?: boolean | number
    region_fias_id?: boolean | number
    region_format?: boolean | number
    region_original?: boolean | number
    street?: boolean | number
    street_fias_id?: boolean | number
    street_format?: boolean | number
    street_original?: boolean | number
    street_path_full?: boolean | number
    street_path_short?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ImageGenqlSelection{
    FILE_SIZE?: boolean | number
    ID?: boolean | number
    ORIGINAL_NAME?: boolean | number
    SRC?: boolean | number
    SRC_DEFAULT?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface IntFilterInput {eq?: (Scalars['Int'] | null),exists?: (Scalars['Boolean'] | null),gt?: (Scalars['Int'] | null),in?: ((Scalars['Int'] | null)[] | null),lt?: (Scalars['Int'] | null),not?: (Scalars['Int'] | null)}

export interface ListValueGenqlSelection{
    CODE?: boolean | number
    ID?: boolean | number
    VALUE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface MenuGenqlSelection{
    children?: MenuItemGenqlSelection
    code?: boolean | number
    id?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface MenuItemGenqlSelection{
    badge?: boolean | number
    bgColor?: boolean | number
    blank?: boolean | number
    children?: MenuItemGenqlSelection
    color?: boolean | number
    command?: CommandGenqlSelection
    dense?: boolean | number
    disable?: boolean | number
    display?: boolean | number
    entityId?: boolean | number
    entityType?: boolean | number
    flat?: boolean | number
    icon?: boolean | number
    id?: boolean | number
    image?: ImageGenqlSelection
    imageId?: boolean | number
    infoLabel?: boolean | number
    label?: boolean | number
    loading?: boolean | number
    native?: boolean | number
    onClick?: boolean | number
    outline?: boolean | number
    params?: boolean | number
    parent?: boolean | number
    roles?: boolean | number
    textColor?: boolean | number
    url?: boolean | number
    width?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface MenuItemMobileGenqlSelection{
    action?: ActionMobileGenqlSelection
    backgroundColor?: boolean | number
    badge?: boolean | number
    color?: boolean | number
    condition?: ConditionGenqlSelection
    icon?: boolean | number
    id?: boolean | number
    image?: ImageGenqlSelection
    imageId?: boolean | number
    label?: boolean | number
    labelColor?: boolean | number
    link?: boolean | number
    outline?: boolean | number
    outlineColor?: boolean | number
    outlineWidth?: boolean | number
    params?: boolean | number
    parent?: boolean | number
    roles?: boolean | number
    templatable?: boolean | number
    templatableProps?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface MessageGenqlSelection{
    actions?: MenuItemGenqlSelection
    category?: boolean | number
    code?: boolean | number
    data?: boolean | number
    duration?: boolean | number
    id?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    notify?: boolean | number
    rel?: boolean | number
    status?: boolean | number
    title?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface MutationGenqlSelection{
    catalog_product_order?: (ResponseGenqlSelection & { __args?: MutationCatalogProductOrderArgs })
    company_office_update?: (ResponseGenqlSelection & { __args: MutationCompanyOfficeUpdateArgs })
    company_vacancy_order?: (ResponseGenqlSelection & { __args?: MutationCompanyVacancyOrderArgs })
    notice_pub_push_send_queue?: ResponseGenqlSelection
    notice_pub_push_update_token?: (ResponseGenqlSelection & { __args?: MutationNoticePubPushUpdateTokenArgs })
    notice_pub_sync_readed?: (NoticePubSyncReadedResultGenqlSelection & { __args?: MutationNoticePubSyncReadedArgs })
    review_order_guest_review?: (ReviewOrderGuestReviewResultGenqlSelection & { __args?: MutationReviewOrderGuestReviewArgs })
    review_service_review?: (ReviewServiceReviewResultGenqlSelection & { __args?: MutationReviewServiceReviewArgs })
    sale_client_card_create?: (ResponseGenqlSelection & { __args: MutationSaleClientCardCreateArgs })
    sale_client_card_delete?: (ResponseGenqlSelection & { __args?: MutationSaleClientCardDeleteArgs })
    sale_client_card_update?: (ResponseGenqlSelection & { __args: MutationSaleClientCardUpdateArgs })
    sale_order_cancel?: (SaleOrderCancelResultGenqlSelection & { __args?: MutationSaleOrderCancelArgs })
    sale_order_pay_online?: (SaleOrderPayOnlineResultGenqlSelection & { __args?: MutationSaleOrderPayOnlineArgs })
    sale_order_repeat?: (SaleOrderRepeatResultGenqlSelection & { __args?: MutationSaleOrderRepeatArgs })
    sale_profile_calc_delivery?: (SaleProfileCalcDeliveryResultGenqlSelection & { __args?: MutationSaleProfileCalcDeliveryArgs })
    sale_profile_default?: (SaleProfileDefaultResultGenqlSelection & { __args?: MutationSaleProfileDefaultArgs })
    sale_profile_delete?: (SaleProfileDeleteResultGenqlSelection & { __args?: MutationSaleProfileDeleteArgs })
    sale_profile_save?: (SaleProfileSaveResultGenqlSelection & { __args?: MutationSaleProfileSaveArgs })
    sale_vorder_apply?: (SaleVorderApplyResultGenqlSelection & { __args?: MutationSaleVorderApplyArgs })
    sale_vorder_basket?: (SaleVorderBasketResultGenqlSelection & { __args?: MutationSaleVorderBasketArgs })
    sale_vorder_coupon?: (SaleVorderCouponResultGenqlSelection & { __args?: MutationSaleVorderCouponArgs })
    sale_vorder_new?: SaleVorderNewResultGenqlSelection
    sale_vorder_reserve?: (SaleVorderReserveResultGenqlSelection & { __args?: MutationSaleVorderReserveArgs })
    sale_vorder_submit?: (SaleVorderSubmitResultGenqlSelection & { __args?: MutationSaleVorderSubmitArgs })
    sale_vorder_sync?: (SaleVorderSyncResultGenqlSelection & { __args?: MutationSaleVorderSyncArgs })
    user_app_client?: (ResponseGenqlSelection & { __args?: MutationUserAppClientArgs })
    user_auth_create_sa?: (ResponseGenqlSelection & { __args?: MutationUserAuthCreateSaArgs })
    user_auth_login_confirm?: (UserAuthLoginConfirmResultGenqlSelection & { __args?: MutationUserAuthLoginConfirmArgs })
    user_auth_login_request?: (UserAuthLoginRequestResultGenqlSelection & { __args?: MutationUserAuthLoginRequestArgs })
    user_auth_login_start?: (UserAuthLoginStartResultGenqlSelection & { __args?: MutationUserAuthLoginStartArgs })
    user_logout?: ResponseGenqlSelection
    user_profile_all_filled?: ResponseGenqlSelection
    user_profile_birthday?: (ResponseGenqlSelection & { __args?: MutationUserProfileBirthdayArgs })
    user_profile_child?: (ResponseGenqlSelection & { __args?: MutationUserProfileChildArgs })
    user_profile_email?: (ResponseGenqlSelection & { __args?: MutationUserProfileEmailArgs })
    user_profile_name?: (ResponseGenqlSelection & { __args?: MutationUserProfileNameArgs })
    user_profile_save?: (ResponseGenqlSelection & { __args?: MutationUserProfileSaveArgs })
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface NoticePubSyncReadedResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: ClientNoticeGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OfferGenqlSelection{
    ACTIONS_MOBILE?: MenuItemMobileGenqlSelection
    ACTIONS_WEB?: MenuItemGenqlSelection
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: OfferActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    BANNER_HOR_DESKTOP?: ImageGenqlSelection
    BANNER_HOR_MOBILE?: ImageGenqlSelection
    BANNER_INTERNAL_TEXT?: boolean | number
    BANNER_SQUARE?: ImageGenqlSelection
    CODE?: boolean | number
    CONTENT_IMAGE?: ImageGenqlSelection
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: OfferDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: OfferDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    DISCOUNT_ID?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    IS_HOT?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    OFFER_NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: OfferPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: OfferShowCounterStartArgs } | boolean | number
    SLIDES?: OfferSlideGenqlSelection
    SORT?: boolean | number
    STARTUP_SHOW?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: OfferTimestampXArgs } | boolean | number
    URL?: boolean | number
    VARS?: boolean | number
    VID?: boolean | number
    VIEW_MODE?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OfferRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: OfferGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OfferSlideGenqlSelection{
    BG_COLOR?: boolean | number
    CODE?: boolean | number
    CONTENT_HTML?: boolean | number
    CONTENT_IMAGE?: ImageGenqlSelection
    CONTENT_TYPE?: boolean | number
    ID?: boolean | number
    NAME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OfficeFilterInput {ACTIVE?: (Scalars['Boolean'] | null),CODE?: (StringFilterInput | null),IBLOCK_CODE?: (StringFilterInput | null),IBLOCK_ID?: (IntFilterInput | null),IBLOCK_SECTION_ID?: (IntFilterInput | null),ID?: (IntFilterInput | null),NAME?: (StringFilterInput | null),SORT?: (IntFilterInput | null)}

export interface OfficeRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: CompanyOfficeGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderGenqlSelection{
    ACCESS_HASH?: boolean | number
    ACCOUNT_NUMBER?: boolean | number
    ACTIONS?: boolean | number
    ADDRESS_FOR_1C?: boolean | number
    ATTR?: OrderAttributesValueGenqlSelection
    ATTRS?: OrderAttributeGenqlSelection
    BASKET?: BasketItemGenqlSelection
    BONUSES?: boolean | number
    BUYER_NAME?: boolean | number
    CANCEL_REASONS?: OrderCancelReasonGenqlSelection
    CONTRACT_NUM?: boolean | number
    COUPONS?: CouponGenqlSelection
    COURIER_STATE?: CourierStateGenqlSelection
    CSTATUS_COLOR?: boolean | number
    CSTATUS_ID?: boolean | number
    CSTATUS_NAME?: boolean | number
    DATE_FORMATTED?: { __args: OrderDateFormattedArgs } | boolean | number
    DATE_INSERT?: { __args: OrderDateInsertArgs } | boolean | number
    DATE_PAYED?: { __args: OrderDatePayedArgs } | boolean | number
    DATE_TIME_FORMATTED?: { __args: OrderDateTimeFormattedArgs } | boolean | number
    DATE_UPDATE?: { __args: OrderDateUpdateArgs } | boolean | number
    DELIVERY?: DeliveryServiceGenqlSelection
    DELIVERY_ADDRESS_FULL?: boolean | number
    DELIVERY_CALCULATED?: boolean | number
    DELIVERY_DATETIME?: { __args: OrderDeliveryDatetimeArgs } | boolean | number
    DELIVERY_DEPARTMENT?: CompanyOfficeGenqlSelection
    DELIVERY_FREE_FROM_PRICE?: boolean | number
    DELIVERY_ID?: boolean | number
    DISCOUNT_PERCENT?: boolean | number
    DISCOUNT_REASON?: boolean | number
    EDU_GROUP_NUM?: boolean | number
    ID?: boolean | number
    IS_ACTIVE?: boolean | number
    IS_CANCELED?: boolean | number
    IS_CAN_CANCEL?: boolean | number
    IS_CAN_PAY?: boolean | number
    IS_CAN_PAY_BILL?: boolean | number
    IS_CAN_PAY_ONLINE?: boolean | number
    IS_FINISHED?: boolean | number
    IS_PAID?: boolean | number
    PAYMENTS?: PaymentGenqlSelection
    PAYSYSTEM?: PaysystemGenqlSelection
    PAYSYSTEM_ID?: boolean | number
    PAYSYSTEM_IS_ONLINE?: boolean | number
    PAY_LINK?: boolean | number
    PERSON_TYPE_ID?: boolean | number
    PICKUP_DEPARTMENT?: CompanyOfficeGenqlSelection
    PRICE?: boolean | number
    PRICE_BASKET?: boolean | number
    PRICE_BASKET_BASE?: boolean | number
    PRICE_DELIVERY?: boolean | number
    PRICE_DELIVERY_BASE?: boolean | number
    PRICE_DISCOUNT?: boolean | number
    PRICE_PAY?: boolean | number
    PRICE_PAY_BASE?: boolean | number
    PRICE_TOTAL?: boolean | number
    PRICE_TOTAL_BASE?: boolean | number
    SCOPE?: OrderScopeGenqlSelection
    SCOPE_ENTITY?: CommandGenqlSelection
    SECRET_URL?: boolean | number
    SERVICE_ID?: boolean | number
    STATUS?: OrderStatusGenqlSelection
    STATUS_COLOR?: boolean | number
    STATUS_ID?: boolean | number
    STATUS_NAME?: boolean | number
    STUDENT_FIO?: boolean | number
    SYNCED?: boolean | number
    TS?: boolean | number
    URL?: boolean | number
    USER?: UserGenqlSelection
    USER_DESCRIPTION?: boolean | number
    USER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderAttributeGenqlSelection{
    CODE?: boolean | number
    DEFAULT_VALUE?: boolean | number
    KIND?: boolean | number
    NAME?: boolean | number
    OPTIONS?: OrderAttributeOptionGenqlSelection
    TYPE?: boolean | number
    VALUE?: boolean | number
    VALUE_VIEW?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderAttributeOptionGenqlSelection{
    DESCRIPTION?: boolean | number
    DISABLE?: boolean | number
    ICON?: boolean | number
    ID?: boolean | number
    NAME?: boolean | number
    NAME_SHORT?: boolean | number
    SORT?: boolean | number
    VALUE?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderAttributesValueGenqlSelection{
    ADDRESS?: boolean | number
    ADDRESS_IS_CUSTOM?: boolean | number
    ADDRESS_IS_CUSTOM_STRING?: boolean | number
    ADDRESS_SOURCE?: boolean | number
    APP_VERSION?: boolean | number
    BENEFIT_TYPE?: boolean | number
    BENEFIT_TYPE_STRING?: boolean | number
    BONUSES?: boolean | number
    CASH_SUM?: boolean | number
    CITY?: boolean | number
    CITY_FIAS_ID?: boolean | number
    COMMENT?: boolean | number
    DATA?: OrderDataGenqlSelection
    DATE?: boolean | number
    DATETIME?: boolean | number
    DELIVERY_DEPARTMENT?: boolean | number
    DELIVERY_FREE_FROM_PRICE?: boolean | number
    DELIVERY_FREE_UPDATED_TIME?: boolean | number
    DELIVERY_ID?: boolean | number
    DELIVERY_ID_STRING?: boolean | number
    DELIVERY_PRICE?: boolean | number
    DEPARTMENT_SERVICE_ID?: boolean | number
    DETAILS?: boolean | number
    DETAILS_STRING?: boolean | number
    DISCOUNT_PERCENT?: boolean | number
    DISCOUNT_REASON?: boolean | number
    EMAIL?: boolean | number
    ENTRANCE?: boolean | number
    FIO?: boolean | number
    FLAT?: boolean | number
    FLOOR?: boolean | number
    GIFTS_LIST?: boolean | number
    HOUSE?: boolean | number
    HOUSE_COORDS?: CoordinatesGenqlSelection
    HOUSE_COORDS_STRING?: boolean | number
    HOUSE_FIAS_ID?: boolean | number
    INTERCOM?: boolean | number
    LIFT?: boolean | number
    LIFT_STRING?: boolean | number
    LOCATION?: boolean | number
    NEED_CONFIRM?: boolean | number
    NEED_CONFIRM_STRING?: boolean | number
    PAYMENT_TYPE?: boolean | number
    PAYMENT_TYPE_STRING?: boolean | number
    PAY_SYSTEM_ID?: boolean | number
    PAY_SYSTEM_ID_STRING?: boolean | number
    PERSONS_NUMBER?: boolean | number
    PHONE?: boolean | number
    PICKUP_DEPARTMENT?: boolean | number
    PRIVATE_HOUSE?: boolean | number
    PRIVATE_HOUSE_STRING?: boolean | number
    PROFILE_COMMENT?: boolean | number
    PROFILE_DEFAULT?: boolean | number
    PROFILE_DEFAULT_STRING?: boolean | number
    PROFILE_ID?: boolean | number
    PROMOCODE?: boolean | number
    RECEIVER_ANOTHER?: boolean | number
    RECEIVER_ANOTHER_STRING?: boolean | number
    RECEIVER_NAME?: boolean | number
    RECEIVER_PHONE?: boolean | number
    RESERVE_AVAILABLE_TIME?: boolean | number
    RESERVE_NEED_TIME?: boolean | number
    RESERVE_REQUEST_TIME?: boolean | number
    RESERVE_STATUS?: boolean | number
    RESERVE_SUCCESS_HASH?: boolean | number
    RESERVE_SUCCESS_REQUEST_TIME?: boolean | number
    ROISTAT?: boolean | number
    SERVICE_SEND?: boolean | number
    SERVICE_SEND_START?: boolean | number
    SERVICE_SEND_STRING?: boolean | number
    SETTLEMENT?: boolean | number
    SETTLEMENT_FIAS_ID?: boolean | number
    SOURCE?: boolean | number
    STREET?: boolean | number
    STREET_COORDS?: CoordinatesGenqlSelection
    STREET_COORDS_STRING?: boolean | number
    STREET_FIAS_ID?: boolean | number
    STREET_PATH?: boolean | number
    STRUCT?: boolean | number
    TEST_TIME?: boolean | number
    TEST_TIME_STRING?: boolean | number
    TIME?: boolean | number
    TIME_MODE?: boolean | number
    TIME_MODE_STRING?: boolean | number
    TRANSPORT_TYPE?: boolean | number
    USER_DESCRIPTION?: boolean | number
    UUID?: boolean | number
    VORDER_ID?: boolean | number
    WITH_OPERATOR?: boolean | number
    WITH_OPERATOR_STRING?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderAttributesValueInput {ADDRESS?: (Scalars['String'] | null),ADDRESS_IS_CUSTOM?: (Scalars['Boolean'] | null),ADDRESS_SOURCE?: (Scalars['String'] | null),APP_VERSION?: (Scalars['String'] | null),BENEFIT_TYPE?: (Scalars['String'] | null),BONUSES?: (Scalars['Int'] | null),CASH_SUM?: (Scalars['Int'] | null),CITY?: (Scalars['String'] | null),CITY_FIAS_ID?: (Scalars['String'] | null),COMMENT?: (Scalars['String'] | null),DATA?: (OrderDataInput | null),DATE?: (Scalars['String'] | null),DATETIME?: (Scalars['Int'] | null),DELIVERY_DEPARTMENT?: (Scalars['Int'] | null),DELIVERY_FREE_FROM_PRICE?: (Scalars['String'] | null),DELIVERY_FREE_UPDATED_TIME?: (Scalars['Int'] | null),DELIVERY_ID?: (Scalars['Int'] | null),DELIVERY_PRICE?: (Scalars['Int'] | null),DEPARTMENT_SERVICE_ID?: (Scalars['Int'] | null),DETAILS?: (Scalars['Boolean'] | null),DISCOUNT_PERCENT?: (Scalars['Int'] | null),DISCOUNT_REASON?: (Scalars['String'] | null),EMAIL?: (Scalars['String'] | null),ENTRANCE?: (Scalars['String'] | null),FIO?: (Scalars['String'] | null),FLAT?: (Scalars['String'] | null),FLOOR?: (Scalars['String'] | null),GIFTS_LIST?: (Scalars['String'] | null),HOUSE?: (Scalars['String'] | null),HOUSE_COORDS?: (CoordinatesInput | null),HOUSE_FIAS_ID?: (Scalars['String'] | null),INTERCOM?: (Scalars['String'] | null),LIFT?: (Scalars['Boolean'] | null),LOCATION?: (Scalars['String'] | null),NEED_CONFIRM?: (Scalars['Boolean'] | null),PAYMENT_TYPE?: (AttrPaymentTypeEnum | null),PAY_SYSTEM_ID?: (Scalars['Int'] | null),PERSONS_NUMBER?: (Scalars['Int'] | null),PHONE?: (Scalars['String'] | null),PICKUP_DEPARTMENT?: (Scalars['Int'] | null),PRIVATE_HOUSE?: (Scalars['Boolean'] | null),PROFILE_COMMENT?: (Scalars['String'] | null),PROFILE_DEFAULT?: (Scalars['Boolean'] | null),PROFILE_ID?: (Scalars['Int'] | null),PROMOCODE?: (Scalars['String'] | null),RECEIVER_ANOTHER?: (Scalars['Boolean'] | null),RECEIVER_NAME?: (Scalars['String'] | null),RECEIVER_PHONE?: (Scalars['String'] | null),RESERVE_AVAILABLE_TIME?: (Scalars['Int'] | null),RESERVE_NEED_TIME?: (Scalars['Int'] | null),RESERVE_REQUEST_TIME?: (Scalars['Int'] | null),RESERVE_STATUS?: (Scalars['String'] | null),RESERVE_SUCCESS_HASH?: (Scalars['String'] | null),RESERVE_SUCCESS_REQUEST_TIME?: (Scalars['Int'] | null),ROISTAT?: (Scalars['String'] | null),SERVICE_SEND?: (Scalars['Boolean'] | null),SERVICE_SEND_START?: (Scalars['Int'] | null),SETTLEMENT?: (Scalars['String'] | null),SETTLEMENT_FIAS_ID?: (Scalars['String'] | null),SOURCE?: (Scalars['String'] | null),STREET?: (Scalars['String'] | null),STREET_COORDS?: (CoordinatesInput | null),STREET_FIAS_ID?: (Scalars['String'] | null),STREET_PATH?: (Scalars['String'] | null),STRUCT?: (Scalars['String'] | null),TEST_TIME?: (Scalars['Int'] | null),TIME?: (Scalars['String'] | null),TIME_MODE?: (AttrTimeModeEnum | null),TRANSPORT_TYPE?: (AttrTransportTypeEnum | null),USER_DESCRIPTION?: (Scalars['String'] | null),UUID?: (Scalars['String'] | null),VORDER_ID?: (Scalars['Int'] | null),WITH_OPERATOR?: (Scalars['Boolean'] | null)}

export interface OrderCancelReasonGenqlSelection{
    CODE?: boolean | number
    NAME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderDataGenqlSelection{
    paramArray?: boolean | number
    paramInt?: boolean | number
    paramString?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderDataInput {paramArray?: (Scalars['Json'] | null),paramInt?: (Scalars['Int'] | null),paramString?: (Scalars['String'] | null)}

export interface OrderFilter {ACCOUNT_NUMBER?: (StringFilterInput | null),CANCELED?: (Scalars['Boolean'] | null),DATE_DAY?: (Scalars['Int'] | null),DATE_MONTH?: (Scalars['Int'] | null),DATE_PAYED_DAY?: (Scalars['Int'] | null),DATE_PAYED_MONTH?: (Scalars['Int'] | null),DATE_PAYED_YEAR?: (Scalars['Int'] | null),DATE_YEAR?: (Scalars['Int'] | null),ELEMENT_NAME?: (StringFilterInput | null),EMAIL?: (StringFilterInput | null),ID?: (IntFilterInput | null),MODE?: (Scalars['String'] | null),PAYED?: (Scalars['Boolean'] | null),PAY_SYSTEM_ID?: (IntFilterInput | null),PHONE?: (StringFilterInput | null),PROP_CONTRACT_NUM?: (StringFilterInput | null),PROP_EDU_GROUP_NUM?: (StringFilterInput | null),PROP_EMAIL?: (StringFilterInput | null),PROP_FIO?: (StringFilterInput | null),PROP_PHONE?: (StringFilterInput | null),PROP_PRODUCT_SECTIONS?: (StringFilterInput | null),PROP_STUDENT_FIO?: (StringFilterInput | null),SECTION_ID?: (IntFilterInput | null),STATUS_ID?: (StringFilterInput | null),USER_ID?: (IntFilterInput | null)}

export interface OrderProfileGenqlSelection{
    ATTR?: OrderProfileAttributesValueGenqlSelection
    ATTRS?: OrderAttributeGenqlSelection
    CAPTION?: boolean | number
    COMPANY_ID?: boolean | number
    COORDS?: CoordinatesGenqlSelection
    DELIVERY_FREE_FROM_PRICE?: boolean | number
    ID?: boolean | number
    IS_DEFAULT?: boolean | number
    NAME?: boolean | number
    PERSON_TYPE?: PersonTypeGenqlSelection
    PERSON_TYPE_ID?: boolean | number
    USER?: UserGenqlSelection
    USER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderProfileAttributesValueGenqlSelection{
    ADDRESS?: boolean | number
    ADDRESS_IS_CUSTOM?: boolean | number
    ADDRESS_SOURCE?: boolean | number
    CITY?: boolean | number
    CITY_FIAS_ID?: boolean | number
    ENTRANCE?: boolean | number
    FLAT?: boolean | number
    FLOOR?: boolean | number
    HOUSE?: boolean | number
    HOUSE_COORDS?: CoordinatesGenqlSelection
    HOUSE_FIAS_ID?: boolean | number
    LIFT?: boolean | number
    PRIVATE_HOUSE?: boolean | number
    PROFILE_COMMENT?: boolean | number
    PROFILE_DEFAULT?: boolean | number
    RECEIVER_ANOTHER?: boolean | number
    RECEIVER_NAME?: boolean | number
    RECEIVER_PHONE?: boolean | number
    SETTLEMENT?: boolean | number
    SETTLEMENT_FIAS_ID?: boolean | number
    STREET?: boolean | number
    STREET_COORDS?: CoordinatesGenqlSelection
    STREET_FIAS_ID?: boolean | number
    STREET_PATH?: boolean | number
    STRUCT?: boolean | number
    TEST_TIME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderProfileRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: OrderProfileGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderScopeGenqlSelection{
    CONTRACT_NUM?: boolean | number
    ENTITY_ID?: boolean | number
    ENTITY_TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OrderStatusGenqlSelection{
    COLOR?: boolean | number
    ID?: boolean | number
    NAME?: boolean | number
    SORT?: boolean | number
    TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface OtpErrorGenqlSelection{
    data?: boolean | number
    fieldMessage?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    param?: boolean | number
    rel?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PageGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: PageActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CONTENT_CHUNKS?: PageContentChunkGenqlSelection
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATA_CHUNKS?: PageDataChunkGenqlSelection
    DATE_CREATE?: { __args: PageDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: PageDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: PagePreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: PageShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: PageTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PageContentChunkGenqlSelection{
    CODE?: boolean | number
    GROUP?: boolean | number
    NAME?: boolean | number
    TYPE?: boolean | number
    VALUE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PageDataChunkGenqlSelection{
    CODE?: boolean | number
    TYPE?: boolean | number
    VALUE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PageRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: PageGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PaymentGenqlSelection{
    DATE_PAID?: { __args: PaymentDatePaidArgs } | boolean | number
    ID?: boolean | number
    IS_PAID?: boolean | number
    ORDER_ID?: boolean | number
    ORDER_URL?: boolean | number
    PAYSYSTEM?: PaysystemGenqlSelection
    PAYSYSTEM_ID?: boolean | number
    PAY_NAV?: boolean | number
    PS_INVOICE_ID?: boolean | number
    PS_STATUS?: boolean | number
    PS_STATUS_CODE?: boolean | number
    PS_STATUS_ID?: boolean | number
    PS_STATUS_NAME?: boolean | number
    SUM?: boolean | number
    SUM_PAID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PaymentTypeGenqlSelection{
    CODE?: boolean | number
    ICON?: boolean | number
    NAME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PaysystemGenqlSelection{
    CODE?: boolean | number
    DESCRIPTION?: boolean | number
    ID?: boolean | number
    IS_BILL?: boolean | number
    IS_INNER?: boolean | number
    IS_ONLINE?: boolean | number
    IS_ONLINE_DELAYED?: boolean | number
    NAME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface PersonTypeGenqlSelection{
    CODE?: boolean | number
    ID?: boolean | number
    IS_COMPANY?: boolean | number
    NAME?: boolean | number
    RESTRICTED?: boolean | number
    SORT?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: ProductActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    BENEFITS?: ProductBenefitGenqlSelection
    BUILD?: ConstructorBuildGenqlSelection
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: ProductDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: ProductDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    FLAGS?: ProductFlagGenqlSelection
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    IMAGE?: ImageGenqlSelection
    IS_SALE_SPECIAL?: boolean | number
    MEASURE?: ProductMeasureGenqlSelection
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    OFFERS?: ProductGenqlSelection
    OFFER_PARENT_ELEMENT?: ProductGenqlSelection
    PARENT?: ProductGenqlSelection
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: ProductPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    PRICE?: ProductPriceGenqlSelection
    PROPERTIES?: ProductPropsGenqlSelection
    ROOT_SECTION?: SectionGenqlSelection
    SALES_COUNT?: boolean | number
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SET_ITEMS?: ProductSetItemGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: ProductShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: ProductTagGenqlSelection
    TIMESTAMP_X?: { __args: ProductTimestampXArgs } | boolean | number
    URL?: boolean | number
    WEIGHT?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductBenefitGenqlSelection{
    IS_GIFT?: boolean | number
    PRODUCT?: ProductGenqlSelection
    PRODUCT_ID?: boolean | number
    QUANTITY?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductFilterInput {ACTIVE?: (Scalars['Boolean'] | null),CODE?: (StringFilterInput | null),IBLOCK_CODE?: (StringFilterInput | null),IBLOCK_ID?: (IntFilterInput | null),IBLOCK_SECTION_ID?: (IntFilterInput | null),ID?: (IntFilterInput | null),NAME?: (StringFilterInput | null),SORT?: (IntFilterInput | null)}

export interface ProductFlagGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: ProductFlagActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: ProductFlagDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: ProductFlagDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: ProductFlagPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: ProductFlagShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: ProductFlagTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductMeasureGenqlSelection{
    NAME?: boolean | number
    RATIO?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductPriceGenqlSelection{
    DISCOUNTED?: boolean | number
    DISCOUNT_PERCENT?: boolean | number
    PRICE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductPropsGenqlSelection{
    ADDITIVES?: boolean | number
    ADDITIVES_ENTITIES?: ElementGenqlSelection
    AVAILABLE_SCHEDULE?: ProductPropsAVAILABLE_SCHEDULEGenqlSelection
    BELKI?: boolean | number
    BENEFITS?: ProductPropsBENEFITSGenqlSelection
    COMPONENT_IS?: ListValueGenqlSelection
    FLAG_ITEMS?: boolean | number
    FLAG_ITEMS_ENTITIES?: ElementGenqlSelection
    HOLIDAY?: ListValueGenqlSelection
    IS_HIT?: boolean | number
    KKAL?: boolean | number
    NEW?: ListValueGenqlSelection
    PHOTOV2?: ImageGenqlSelection
    ROLLS?: boolean | number
    ROLLS_ENTITIES?: ElementGenqlSelection
    SALE_SPECIAL?: boolean | number
    SERVICE_ID?: boolean | number
    SET_ITEMS?: ProductPropsSET_ITEMSGenqlSelection
    SOSTAV?: ElementPropValueWithDescGenqlSelection
    TAGS?: boolean | number
    TAGS_ENTITIES?: ElementGenqlSelection
    UGLEVODY?: boolean | number
    UPSALE_ELEMENTS?: boolean | number
    UPSALE_ELEMENTS_ENTITY?: ElementGenqlSelection
    UPSALE_SECTIONS?: boolean | number
    WEIGHT?: boolean | number
    ZHIRY?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductPropsAVAILABLE_SCHEDULEGenqlSelection{
    DAY?: ListValueGenqlSelection
    FROM?: boolean | number
    TO?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductPropsBENEFITSGenqlSelection{
    IS_GIFT?: boolean | number
    PRODUCT?: boolean | number
    PRODUCT_ENTITY?: ElementGenqlSelection
    QUANTITY?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductPropsSET_ITEMSGenqlSelection{
    PRODUCT_ID?: boolean | number
    PRODUCT_ID_ENTITY?: ElementGenqlSelection
    QUANTITY?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductSectionGenqlSelection{
    ACTIVE?: boolean | number
    CHILDREN?: SectionGenqlSelection
    CODE?: boolean | number
    DATE_CREATE?: { __args: ProductSectionDateCreateArgs } | boolean | number
    DEPTH_LEVEL?: boolean | number
    DESCRIPTION?: boolean | number
    DESCRIPTION_TYPE?: boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    ELEMENT_CNT?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_EXTERNAL_ID?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_TYPE_ID?: boolean | number
    ID?: boolean | number
    LEFT_MARGIN?: boolean | number
    META?: ElementMetaGenqlSelection
    NAME?: boolean | number
    PARENT?: ProductSectionGenqlSelection
    PARENTS?: ProductSectionGenqlSelection
    PICTURE?: ImageGenqlSelection
    PROPERTIES?: ProductSectionPropsGenqlSelection
    REPLACE_LINK?: boolean | number
    RIGHT_MARGIN?: boolean | number
    SEARCHABLE_CONTENT?: { __args: ProductSectionSearchableContentArgs } | boolean | number
    SECTION_PAGE_URL?: boolean | number
    SORT?: boolean | number
    TIMESTAMP_X?: { __args: ProductSectionTimestampXArgs } | boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductSectionPropsGenqlSelection{
    CATEGORY_CLASS?: boolean | number
    DATA?: boolean | number
    DATA_FORMATTED?: boolean | number
    DAY?: boolean | number
    ENUM?: boolean | number
    FLOAT?: boolean | number
    HINT?: boolean | number
    HTML?: boolean | number
    INT?: boolean | number
    LIST?: ListValueGenqlSelection
    LOGIC?: boolean | number
    MENU_BG_COLOR?: boolean | number
    MENU_COLOR?: boolean | number
    MENU_FOCUSED_ICON?: ImageGenqlSelection
    MENU_ICON?: ImageGenqlSelection
    M_CARD?: boolean | number
    NAV_TITLE?: boolean | number
    NAV_URL?: boolean | number
    REPLACE_LINK?: boolean | number
    SEARCH_IN?: boolean | number
    UPSALE_SECTIONS?: boolean | number
    UPSALE_SECTIONS_ENTITY?: SectionGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductSetItemGenqlSelection{
    PRODUCT?: ProductGenqlSelection
    PRODUCT_ID?: boolean | number
    QUANTITY?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ProductTagGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: ProductTagActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: ProductTagDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: ProductTagDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: ProductTagPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: ProductTagShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: ProductTagTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface QueryGenqlSelection{
    camera_element_list?: (WebcamGenqlSelection & { __args?: QueryCameraElementListArgs })
    catalog_product_all?: (ProductGenqlSelection & { __args?: QueryCatalogProductAllArgs })
    catalog_product_fav_list?: (ProductGenqlSelection & { __args?: QueryCatalogProductFavListArgs })
    catalog_product_fav_list2?: (ProductGenqlSelection & { __args?: QueryCatalogProductFavList2Args })
    catalog_product_list?: (ProductGenqlSelection & { __args?: QueryCatalogProductListArgs })
    catalog_product_recordset?: (AppProductRecordsetGenqlSelection & { __args?: QueryCatalogProductRecordsetArgs })
    catalog_product_search?: (SearchResultGenqlSelection & { __args?: QueryCatalogProductSearchArgs })
    catalog_product_search_new?: (ProductGenqlSelection & { __args?: QueryCatalogProductSearchNewArgs })
    catalog_product_section_list?: (ProductSectionGenqlSelection & { __args?: QueryCatalogProductSectionListArgs })
    catalog_product_single?: (ProductGenqlSelection & { __args?: QueryCatalogProductSingleArgs })
    company_office_list?: (CompanyOfficeGenqlSelection & { __args?: QueryCompanyOfficeListArgs })
    company_office_recordset?: (OfficeRecordsetGenqlSelection & { __args?: QueryCompanyOfficeRecordsetArgs })
    company_office_single?: (CompanyOfficeGenqlSelection & { __args?: QueryCompanyOfficeSingleArgs })
    company_vacancy_list?: (CompanyVacancyGenqlSelection & { __args?: QueryCompanyVacancyListArgs })
    company_vacancy_recordset?: (VacancyRecordsetGenqlSelection & { __args?: QueryCompanyVacancyRecordsetArgs })
    company_vacancy_single?: (CompanyVacancyGenqlSelection & { __args?: QueryCompanyVacancySingleArgs })
    entity_info_list?: EntityInfoGenqlSelection
    faq_element_list?: (FaqQuestionGenqlSelection & { __args?: QueryFaqElementListArgs })
    faq_element_recordset?: (FaqQuestionRecordsetGenqlSelection & { __args?: QueryFaqElementRecordsetArgs })
    faq_element_single?: (FaqQuestionGenqlSelection & { __args?: QueryFaqElementSingleArgs })
    geo_geocoder_location_by_address?: (GeoObjectGenqlSelection & { __args?: QueryGeoGeocoderLocationByAddressArgs })
    geo_geocoder_locations_by_coords?: (GeoObjectGenqlSelection & { __args?: QueryGeoGeocoderLocationsByCoordsArgs })
    list?: ClientEmitGenqlSelection
    menu_menus?: MenuGenqlSelection
    notice_pub_list?: ClientNoticeGenqlSelection
    offer_common_list?: OfferGenqlSelection
    offer_list?: (OfferGenqlSelection & { __args?: QueryOfferListArgs })
    offer_recordset?: (OfferRecordsetGenqlSelection & { __args?: QueryOfferRecordsetArgs })
    offer_single?: (OfferGenqlSelection & { __args?: QueryOfferSingleArgs })
    offer_user_list?: OfferGenqlSelection
    page_list?: (PageGenqlSelection & { __args?: QueryPageListArgs })
    page_recordset?: (PageRecordsetGenqlSelection & { __args?: QueryPageRecordsetArgs })
    page_route?: (PageGenqlSelection & { __args?: QueryPageRouteArgs })
    page_single?: (PageGenqlSelection & { __args?: QueryPageSingleArgs })
    review_list?: (ReviewGenqlSelection & { __args?: QueryReviewListArgs })
    sale_bonus_level_list?: (BonusLevelGenqlSelection & { __args?: QuerySaleBonusLevelListArgs })
    sale_client_card_apply_by_phone?: (SaleClientCardGenqlSelection & { __args?: QuerySaleClientCardApplyByPhoneArgs })
    sale_client_card_fetch?: (SaleClientCardGenqlSelection & { __args?: QuerySaleClientCardFetchArgs })
    sale_client_card_list?: (SaleClientCardGenqlSelection & { __args?: QuerySaleClientCardListArgs })
    sale_client_card_recordset?: (ClientCardRecordsetGenqlSelection & { __args?: QuerySaleClientCardRecordsetArgs })
    sale_client_card_single?: (SaleClientCardGenqlSelection & { __args?: QuerySaleClientCardSingleArgs })
    sale_delivery_zones?: DeliveryZoneGenqlSelection
    sale_order_active_list?: (OrderGenqlSelection & { __args?: QuerySaleOrderActiveListArgs })
    sale_order_ensure_payment?: (PaymentGenqlSelection & { __args?: QuerySaleOrderEnsurePaymentArgs })
    sale_order_statuses?: OrderStatusGenqlSelection
    sale_profile_list?: (OrderProfileGenqlSelection & { __args?: QuerySaleProfileListArgs })
    sale_profile_recordset?: (OrderProfileRecordsetGenqlSelection & { __args?: QuerySaleProfileRecordsetArgs })
    sale_profile_single?: (OrderProfileGenqlSelection & { __args?: QuerySaleProfileSingleArgs })
    sale_vorder_basket_products?: ProductGenqlSelection
    sale_vorder_current?: (VorderCurrentGenqlSelection & { __args?: QuerySaleVorderCurrentArgs })
    sale_vorder_summary?: VorderSummaryGenqlSelection
    search_suggestions?: (SearchSuggestionGenqlSelection & { __args?: QuerySearchSuggestionsArgs })
    search_suggestions_popular?: SearchSuggestionGenqlSelection
    user_app_client?: AppClientGenqlSelection
    user_fetch?: (UserGenqlSelection & { __args?: QueryUserFetchArgs })
    user_session?: UserSessionGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface QueryInfoGenqlSelection{
    limit?: boolean | number
    nextPage?: boolean | number
    page?: boolean | number
    pages?: boolean | number
    total?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface QueryNavInput {asc?: (Scalars['Boolean'] | null),limit?: (Scalars['Int'] | null),page?: (Scalars['Int'] | null),postLimit?: (Scalars['Int'] | null),postSort?: (Scalars['String'] | null),postSortAsc?: (Scalars['Boolean'] | null),sort?: (Scalars['String'] | null),sortAscending?: (Scalars['Boolean'] | null),sortField?: (Scalars['String'] | null)}

export interface RateErrorGenqlSelection{
    data?: boolean | number
    fieldMessage?: boolean | number
    message?: boolean | number
    messages?: boolean | number
    name?: boolean | number
    rel?: boolean | number
    ttl?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ResponseGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: boolean | number
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ResponseRateGenqlSelection{
    limited?: boolean | number
    ttl?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ResponseRedirectGenqlSelection{
    restricted?: boolean | number
    ttl?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ResponseStateGenqlSelection{
    events?: boolean | number
    messages?: MessageGenqlSelection
    rate?: ResponseRateGenqlSelection
    redirect?: ResponseRedirectGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ReviewGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: ReviewActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CHILDREN?: ReviewGenqlSelection
    CODE?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: ReviewDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: ReviewDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    ELEMENT?: ElementGenqlSelection
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    ORDER?: OrderGenqlSelection
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: ReviewPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: ReviewShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: ReviewTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ReviewOrderGuestReviewResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: ReviewOrderGuestReviewResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ReviewOrderGuestReviewResultPayloadGenqlSelection{
    departmentId?: boolean | number
    departmentName?: boolean | number
    redirectUrl?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface ReviewServiceReviewResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: ReviewGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleClientCardGenqlSelection{
    BONUSES?: boolean | number
    BONUSES_EXPIRE?: boolean | number
    BONUSES_EXPIRE_FORMATTED?: boolean | number
    BONUSES_PERCENT?: boolean | number
    CLIENT_PHONE?: boolean | number
    DISCOUNTS?: DiscountGenqlSelection
    DIS_FIRST_ORDER?: boolean | number
    DIS_SELF_PICKUP?: boolean | number
    EXPIRED?: boolean | number
    FETCHED?: boolean | number
    FETCHED_ACTUAL?: boolean | number
    FETCHED_TIME?: boolean | number
    FETCHED_TIME_FORMATTED?: boolean | number
    ID?: boolean | number
    LEVEL?: BonusLevelGenqlSelection
    LEVEL_CODE?: boolean | number
    LEVEL_NAME?: boolean | number
    MONTH_SPENT?: boolean | number
    TRANSPORT?: boolean | number
    USER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleOrderCancelResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: OrderGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleOrderPayOnlineResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleOrderPayOnlineResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleOrderPayOnlineResultPayloadGenqlSelection{
    action?: ActionWebGenqlSelection
    order?: OrderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleOrderRepeatResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: OrderGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleProfileCalcDeliveryResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: DeliveryCalculateGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleProfileDefaultResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: OrderProfileGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleProfileDeleteResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: boolean | number
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleProfileSaveResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: OrderProfileGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderApplyResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleVorderApplyResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderApplyResultPayloadGenqlSelection{
    vorder?: VorderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderBasketResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleVorderBasketResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderBasketResultPayloadGenqlSelection{
    vorder?: VorderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderCouponResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleVorderCouponResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderCouponResultPayloadGenqlSelection{
    coupon?: CouponGenqlSelection
    vorder?: VorderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderNewResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleVorderNewResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderNewResultPayloadGenqlSelection{
    vorder?: VorderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderReserveResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleVorderReserveResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderReserveResultPayloadGenqlSelection{
    calc?: DeliveryCalculateGenqlSelection
    vorder?: VorderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderSubmitResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleVorderSubmitResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderSubmitResultPayloadGenqlSelection{
    order?: OrderGenqlSelection
    orderId?: boolean | number
    orderUrl?: boolean | number
    vorder?: VorderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderSyncResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: SaleVorderSyncResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SaleVorderSyncResultPayloadGenqlSelection{
    vorder?: VorderGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SearchResultGenqlSelection{
    ELEMENTS?: ProductGenqlSelection
    SECTIONS?: SectionGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SearchSuggestionGenqlSelection{
    data?: SearchSuggestionDataGenqlSelection
    label?: boolean | number
    value?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SearchSuggestionDataGenqlSelection{
    entityId?: boolean | number
    entityRole?: boolean | number
    entityTitle?: boolean | number
    entityTypeCode?: boolean | number
    entityTypeId?: boolean | number
    entityTypeName?: boolean | number
    hint?: boolean | number
    type?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SectionGenqlSelection{
    ACTIVE?: boolean | number
    CHILDREN?: SectionGenqlSelection
    CODE?: boolean | number
    DATE_CREATE?: { __args: SectionDateCreateArgs } | boolean | number
    DEPTH_LEVEL?: boolean | number
    DESCRIPTION?: boolean | number
    DESCRIPTION_TYPE?: boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    ELEMENT_CNT?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_EXTERNAL_ID?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_TYPE_ID?: boolean | number
    ID?: boolean | number
    LEFT_MARGIN?: boolean | number
    META?: ElementMetaGenqlSelection
    NAME?: boolean | number
    PARENT?: SectionGenqlSelection
    PARENTS?: SectionGenqlSelection
    PICTURE?: ImageGenqlSelection
    REPLACE_LINK?: boolean | number
    RIGHT_MARGIN?: boolean | number
    SEARCHABLE_CONTENT?: { __args: SectionSearchableContentArgs } | boolean | number
    SECTION_PAGE_URL?: boolean | number
    SORT?: boolean | number
    TIMESTAMP_X?: { __args: SectionTimestampXArgs } | boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SectionFilterInput {ACTIVE?: (Scalars['Boolean'] | null),CATEGORY_CLASS?: (StringFilterInput | null),CODE?: (StringFilterInput | null),DATA?: (StringFilterInput | null),DAY?: (StringFilterInput | null),DEPTH_LEVEL?: (IntFilterInput | null),ENUM?: (StringFilterInput | null),FLOAT?: (StringFilterInput | null),GLOBAL_ACTIVE?: (Scalars['Boolean'] | null),HAS_ELEMENT?: (IntFilterInput | null),HINT?: (StringFilterInput | null),HTML?: (StringFilterInput | null),IBLOCK_ACTIVE?: (Scalars['Boolean'] | null),IBLOCK_ID?: (IntFilterInput | null),IBLOCK_NAME?: (StringFilterInput | null),IBLOCK_TYPE?: (StringFilterInput | null),ID?: (IntFilterInput | null),INT?: (StringFilterInput | null),LEFT_BORDER?: (IntFilterInput | null),LEFT_MARGIN?: (IntFilterInput | null),LIST?: (StringFilterInput | null),LOGIC?: (StringFilterInput | null),MENU_BG_COLOR?: (StringFilterInput | null),MENU_COLOR?: (StringFilterInput | null),MENU_FOCUSED_ICON?: (StringFilterInput | null),MENU_ICON?: (StringFilterInput | null),M_CARD?: (StringFilterInput | null),NAME?: (StringFilterInput | null),NAV_TITLE?: (StringFilterInput | null),NAV_URL?: (StringFilterInput | null),REPLACE_LINK?: (StringFilterInput | null),RIGHT_BORDER?: (IntFilterInput | null),RIGHT_MARGIN?: (IntFilterInput | null),SEARCH_IN?: (StringFilterInput | null),SECTION_ID?: (IntFilterInput | null),UPSALE_SECTIONS?: (StringFilterInput | null),XML_ID?: (StringFilterInput | null)}

export interface SpecialOfferGenqlSelection{
    ELEMENT?: ProductGenqlSelection
    ELEMENT_ID?: boolean | number
    MIN_PRICE?: boolean | number
    MODE?: boolean | number
    TYPE?: boolean | number
    TYPE_INFO?: SpecialOfferTypeGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface SpecialOfferTypeGenqlSelection{
    CODE?: boolean | number
    COLOR?: boolean | number
    NAME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface StringFilterInput {eq?: (Scalars['String'] | null),exists?: (Scalars['Boolean'] | null),in?: ((Scalars['String'] | null)[] | null),like?: (Scalars['String'] | null),not?: (Scalars['String'] | null)}

export interface UserGenqlSelection{
    AVATAR?: UserAvatarGenqlSelection
    EMAIL?: boolean | number
    FAMILY?: UserFamilyGenqlSelection
    GREETING_NAME?: boolean | number
    GROUPS_INFO?: UserGroupGenqlSelection
    ID?: boolean | number
    LAST_NAME?: boolean | number
    LOGIN?: boolean | number
    LOGIN_FORMAT?: boolean | number
    NAME?: boolean | number
    NAME_FULL?: boolean | number
    NAME_TEASER?: boolean | number
    PERSONAL_BIRTHDAY?: boolean | number
    PERSONAL_PHOTO?: ImageGenqlSelection
    PERSON_TYPE_ID?: boolean | number
    PHONE?: boolean | number
    PHONE_FORMATTED?: boolean | number
    PROFILE_FILLED?: boolean | number
    PROFILE_GIFT_USED?: boolean | number
    PROMOCODE?: boolean | number
    PROPS?: EntityPropGenqlSelection
    ROLES?: boolean | number
    SECOND_NAME?: boolean | number
    SESSION_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthConfirmGenqlSelection{
    CODE?: boolean | number
    COLOR?: boolean | number
    CONFIRM_CONTENT_MOBILE?: boolean | number
    CONFIRM_CONTENT_WEB?: boolean | number
    CONFIRM_STEPS?: UserAuthConfirmStepGenqlSelection
    ICON?: boolean | number
    LIST_BUTTON_MOBILE?: MenuItemMobileGenqlSelection
    LIST_BUTTON_WEB?: MenuItemGenqlSelection
    LIST_CAPTION?: boolean | number
    LIST_NAME?: boolean | number
    NAME?: boolean | number
    RESEND_TITLE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthConfirmStepGenqlSelection{
    CAPTION?: boolean | number
    CODE?: boolean | number
    NAME?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthLoginConfirmResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: UserAuthLoginConfirmResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthLoginConfirmResultPayloadGenqlSelection{
    appClient?: AppClientGenqlSelection
    redirect?: boolean | number
    sessionId?: boolean | number
    userId?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthLoginRequestResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: UserAuthLoginRequestResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthLoginRequestResultPayloadGenqlSelection{
    id?: boolean | number
    sid?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthLoginStartResultGenqlSelection{
    error?: ErrorInterfaceGenqlSelection
    errors?: ErrorInterfaceGenqlSelection
    payload?: UserAuthLoginStartResultPayloadGenqlSelection
    state?: ResponseStateGenqlSelection
    success?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAuthLoginStartResultPayloadGenqlSelection{
    confirmModes?: UserAuthConfirmGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserAvatarGenqlSelection{
    ELEMENT_ID?: boolean | number
    IMAGE?: ImageGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserFamilyGenqlSelection{
    BIRTHDAY?: boolean | number
    ID?: boolean | number
    NAME?: boolean | number
    RELATION?: boolean | number
    USER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserFamilyInput {BIRTHDAY?: (Scalars['String'] | null),ID?: (Scalars['Int'] | null),NAME?: (Scalars['String'] | null)}

export interface UserGroupGenqlSelection{
    ID?: boolean | number
    NAME?: boolean | number
    TYPE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface UserSessionGenqlSelection{
    FUSER_ID?: boolean | number
    SESSION_ID?: boolean | number
    USER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface VacancyRecordsetGenqlSelection{
    info?: QueryInfoGenqlSelection
    nodes?: CompanyVacancyGenqlSelection
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface VorderGenqlSelection{
    ATTR?: OrderAttributesValueGenqlSelection
    ATTRS?: OrderAttributeGenqlSelection
    BASKET?: (BasketGenqlSelection & { __args?: VorderBasketArgs })
    BONUSES?: boolean | number
    COUPONS?: CouponGenqlSelection
    DELIVERY_DEPARTMENT_ID?: boolean | number
    DEPARTMENT_ID?: boolean | number
    EMAIL?: boolean | number
    FIELDS_RAW?: boolean | number
    FUSER_ID?: boolean | number
    ID?: boolean | number
    ORDER?: OrderGenqlSelection
    ORDER_ID?: boolean | number
    PHONE?: boolean | number
    PICKUP_DEPARTMENT_ID?: boolean | number
    PROFILE_ID?: boolean | number
    PROPS_RAW?: boolean | number
    SESSION_ID?: boolean | number
    USER?: UserGenqlSelection
    USER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface VorderCurrentGenqlSelection{
    ATTR?: OrderAttributesValueGenqlSelection
    ATTRS?: OrderAttributeGenqlSelection
    BASKET?: (BasketGenqlSelection & { __args?: VorderCurrentBasketArgs })
    BASKET_RULES?: BasketRuleGenqlSelection
    BASKET_RULES_RESULT?: BasketRulesResultGenqlSelection
    BONUSES?: boolean | number
    BONUSES_AVAILABLE?: boolean | number
    BONUSES_PERCENT?: boolean | number
    COUPONS?: CouponGenqlSelection
    COUPON_CAN_ADD?: boolean | number
    DELIVERIES?: DeliveryComputedGenqlSelection
    DELIVERY_CALCULATED?: boolean | number
    DELIVERY_DEPARTMENT_ID?: boolean | number
    DELIVERY_FREE_FROM_PRICE?: boolean | number
    DEPARTMENTS?: CompanyOfficeGenqlSelection
    DEPARTMENT_ID?: boolean | number
    DISCOUNTS?: DiscountGenqlSelection
    EMAIL?: boolean | number
    FIELDS_RAW?: boolean | number
    FUSER_ID?: boolean | number
    ID?: boolean | number
    ORDER?: OrderGenqlSelection
    ORDER_ID?: boolean | number
    PAYMENT_TYPES?: PaymentTypeGenqlSelection
    PERSON_TYPES?: PersonTypeGenqlSelection
    PHONE?: boolean | number
    PICKUP_DEPARTMENT_ID?: boolean | number
    PROFILES?: OrderProfileGenqlSelection
    PROFILE_ID?: boolean | number
    PROPS_RAW?: boolean | number
    RESPONSE_STATE?: ResponseStateGenqlSelection
    SESSION_ID?: boolean | number
    TRANSPORT_TYPES?: boolean | number
    TS?: boolean | number
    USER?: UserGenqlSelection
    USER_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface VorderInput {attrs?: (OrderAttributesValueInput | null),basket?: (Scalars['Json'] | null)}

export interface VorderSummaryGenqlSelection{
    EMAIL?: boolean | number
    FUSER_ID?: boolean | number
    ID?: boolean | number
    PHONE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface WebcamGenqlSelection{
    ACTIVE?: boolean | number
    ACTIVE_FROM?: { __args: WebcamActiveFromArgs } | boolean | number
    ACTIVE_TO?: boolean | number
    CREATED_BY?: boolean | number
    CREATED_USER_NAME?: boolean | number
    DATE_CREATE?: { __args: WebcamDateCreateArgs } | boolean | number
    DETAIL_PICTURE?: ImageGenqlSelection
    DETAIL_TEXT?: { __args: WebcamDetailTextArgs } | boolean | number
    DETAIL_TEXT_TYPE?: boolean | number
    EXTERNAL_ID?: boolean | number
    IBLOCK_CODE?: boolean | number
    IBLOCK_ID?: boolean | number
    IBLOCK_SECTION_ID?: boolean | number
    IBLOCK_SECTION_IDS?: boolean | number
    ID?: boolean | number
    META?: ElementMetaGenqlSelection
    MODIFIED_BY?: boolean | number
    NAME?: boolean | number
    PATH?: SectionGenqlSelection
    PREVIEW_PICTURE?: ImageGenqlSelection
    PREVIEW_TEXT?: { __args: WebcamPreviewTextArgs } | boolean | number
    PREVIEW_TEXT_TYPE?: boolean | number
    PROPERTIES?: WebcamPropsGenqlSelection
    ROOT_SECTION?: SectionGenqlSelection
    SECTION?: SectionGenqlSelection
    SECTIONS?: SectionGenqlSelection
    SHOW_COUNTER?: boolean | number
    SHOW_COUNTER_START?: { __args: WebcamShowCounterStartArgs } | boolean | number
    SORT?: boolean | number
    TAGS?: boolean | number
    TIMESTAMP_X?: { __args: WebcamTimestampXArgs } | boolean | number
    URL?: boolean | number
    XML_ID?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}

export interface WebcamPropsGenqlSelection{
    CODE?: boolean | number
    __typename?: boolean | number
    __scalar?: boolean | number
    __fragment?: JsonFragmentName
}


    const AccessError_possibleTypes: string[] = ['AccessError']
    export const isAccessError = (obj?: { __typename?: any } | null): obj is AccessError => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isAccessError"')
      return AccessError_possibleTypes.includes(obj.__typename)
    }
    


    const ActionMobile_possibleTypes: string[] = ['ActionMobile']
    export const isActionMobile = (obj?: { __typename?: any } | null): obj is ActionMobile => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isActionMobile"')
      return ActionMobile_possibleTypes.includes(obj.__typename)
    }
    


    const ActionWeb_possibleTypes: string[] = ['ActionWeb']
    export const isActionWeb = (obj?: { __typename?: any } | null): obj is ActionWeb => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isActionWeb"')
      return ActionWeb_possibleTypes.includes(obj.__typename)
    }
    


    const AppClient_possibleTypes: string[] = ['AppClient']
    export const isAppClient = (obj?: { __typename?: any } | null): obj is AppClient => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isAppClient"')
      return AppClient_possibleTypes.includes(obj.__typename)
    }
    


    const AppClientDebugParams_possibleTypes: string[] = ['AppClientDebugParams']
    export const isAppClientDebugParams = (obj?: { __typename?: any } | null): obj is AppClientDebugParams => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isAppClientDebugParams"')
      return AppClientDebugParams_possibleTypes.includes(obj.__typename)
    }
    


    const AppProductRecordset_possibleTypes: string[] = ['AppProductRecordset']
    export const isAppProductRecordset = (obj?: { __typename?: any } | null): obj is AppProductRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isAppProductRecordset"')
      return AppProductRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const Basket_possibleTypes: string[] = ['Basket']
    export const isBasket = (obj?: { __typename?: any } | null): obj is Basket => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasket"')
      return Basket_possibleTypes.includes(obj.__typename)
    }
    


    const BasketBuildItem_possibleTypes: string[] = ['BasketBuildItem']
    export const isBasketBuildItem = (obj?: { __typename?: any } | null): obj is BasketBuildItem => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketBuildItem"')
      return BasketBuildItem_possibleTypes.includes(obj.__typename)
    }
    


    const BasketItem_possibleTypes: string[] = ['BasketItem']
    export const isBasketItem = (obj?: { __typename?: any } | null): obj is BasketItem => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketItem"')
      return BasketItem_possibleTypes.includes(obj.__typename)
    }
    


    const BasketItemDiscount_possibleTypes: string[] = ['BasketItemDiscount']
    export const isBasketItemDiscount = (obj?: { __typename?: any } | null): obj is BasketItemDiscount => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketItemDiscount"')
      return BasketItemDiscount_possibleTypes.includes(obj.__typename)
    }
    


    const BasketItemProp_possibleTypes: string[] = ['BasketItemProp']
    export const isBasketItemProp = (obj?: { __typename?: any } | null): obj is BasketItemProp => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketItemProp"')
      return BasketItemProp_possibleTypes.includes(obj.__typename)
    }
    


    const BasketRule_possibleTypes: string[] = ['BasketRule']
    export const isBasketRule = (obj?: { __typename?: any } | null): obj is BasketRule => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketRule"')
      return BasketRule_possibleTypes.includes(obj.__typename)
    }
    


    const BasketRuleActionDiscount_possibleTypes: string[] = ['BasketRuleActionDiscount']
    export const isBasketRuleActionDiscount = (obj?: { __typename?: any } | null): obj is BasketRuleActionDiscount => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketRuleActionDiscount"')
      return BasketRuleActionDiscount_possibleTypes.includes(obj.__typename)
    }
    


    const BasketRuleActionInterface_possibleTypes: string[] = ['BasketRuleActionDiscount']
    export const isBasketRuleActionInterface = (obj?: { __typename?: any } | null): obj is BasketRuleActionInterface => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketRuleActionInterface"')
      return BasketRuleActionInterface_possibleTypes.includes(obj.__typename)
    }
    


    const BasketRuleCondition_possibleTypes: string[] = ['BasketRuleCondition']
    export const isBasketRuleCondition = (obj?: { __typename?: any } | null): obj is BasketRuleCondition => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketRuleCondition"')
      return BasketRuleCondition_possibleTypes.includes(obj.__typename)
    }
    


    const BasketRulesResulBenefitProduct_possibleTypes: string[] = ['BasketRulesResulBenefitProduct']
    export const isBasketRulesResulBenefitProduct = (obj?: { __typename?: any } | null): obj is BasketRulesResulBenefitProduct => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketRulesResulBenefitProduct"')
      return BasketRulesResulBenefitProduct_possibleTypes.includes(obj.__typename)
    }
    


    const BasketRulesResult_possibleTypes: string[] = ['BasketRulesResult']
    export const isBasketRulesResult = (obj?: { __typename?: any } | null): obj is BasketRulesResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBasketRulesResult"')
      return BasketRulesResult_possibleTypes.includes(obj.__typename)
    }
    


    const BonusLevel_possibleTypes: string[] = ['BonusLevel']
    export const isBonusLevel = (obj?: { __typename?: any } | null): obj is BonusLevel => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBonusLevel"')
      return BonusLevel_possibleTypes.includes(obj.__typename)
    }
    


    const BonusLevelProps_possibleTypes: string[] = ['BonusLevelProps']
    export const isBonusLevelProps = (obj?: { __typename?: any } | null): obj is BonusLevelProps => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isBonusLevelProps"')
      return BonusLevelProps_possibleTypes.includes(obj.__typename)
    }
    


    const ClientCardRecordset_possibleTypes: string[] = ['ClientCardRecordset']
    export const isClientCardRecordset = (obj?: { __typename?: any } | null): obj is ClientCardRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isClientCardRecordset"')
      return ClientCardRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const ClientEmit_possibleTypes: string[] = ['ClientEmit']
    export const isClientEmit = (obj?: { __typename?: any } | null): obj is ClientEmit => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isClientEmit"')
      return ClientEmit_possibleTypes.includes(obj.__typename)
    }
    


    const ClientNotice_possibleTypes: string[] = ['ClientNotice']
    export const isClientNotice = (obj?: { __typename?: any } | null): obj is ClientNotice => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isClientNotice"')
      return ClientNotice_possibleTypes.includes(obj.__typename)
    }
    


    const Command_possibleTypes: string[] = ['Command']
    export const isCommand = (obj?: { __typename?: any } | null): obj is Command => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCommand"')
      return Command_possibleTypes.includes(obj.__typename)
    }
    


    const CommonError_possibleTypes: string[] = ['CommonError']
    export const isCommonError = (obj?: { __typename?: any } | null): obj is CommonError => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCommonError"')
      return CommonError_possibleTypes.includes(obj.__typename)
    }
    


    const CompanyOffice_possibleTypes: string[] = ['CompanyOffice']
    export const isCompanyOffice = (obj?: { __typename?: any } | null): obj is CompanyOffice => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCompanyOffice"')
      return CompanyOffice_possibleTypes.includes(obj.__typename)
    }
    


    const CompanyOfficeProps_possibleTypes: string[] = ['CompanyOfficeProps']
    export const isCompanyOfficeProps = (obj?: { __typename?: any } | null): obj is CompanyOfficeProps => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCompanyOfficeProps"')
      return CompanyOfficeProps_possibleTypes.includes(obj.__typename)
    }
    


    const CompanyVacancy_possibleTypes: string[] = ['CompanyVacancy']
    export const isCompanyVacancy = (obj?: { __typename?: any } | null): obj is CompanyVacancy => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCompanyVacancy"')
      return CompanyVacancy_possibleTypes.includes(obj.__typename)
    }
    


    const Condition_possibleTypes: string[] = ['Condition']
    export const isCondition = (obj?: { __typename?: any } | null): obj is Condition => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCondition"')
      return Condition_possibleTypes.includes(obj.__typename)
    }
    


    const ConstructorBuild_possibleTypes: string[] = ['ConstructorBuild']
    export const isConstructorBuild = (obj?: { __typename?: any } | null): obj is ConstructorBuild => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isConstructorBuild"')
      return ConstructorBuild_possibleTypes.includes(obj.__typename)
    }
    


    const ConstructorBuildItem_possibleTypes: string[] = ['ConstructorBuildItem']
    export const isConstructorBuildItem = (obj?: { __typename?: any } | null): obj is ConstructorBuildItem => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isConstructorBuildItem"')
      return ConstructorBuildItem_possibleTypes.includes(obj.__typename)
    }
    


    const ConstructorElement_possibleTypes: string[] = ['ConstructorElement']
    export const isConstructorElement = (obj?: { __typename?: any } | null): obj is ConstructorElement => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isConstructorElement"')
      return ConstructorElement_possibleTypes.includes(obj.__typename)
    }
    


    const Coordinates_possibleTypes: string[] = ['Coordinates']
    export const isCoordinates = (obj?: { __typename?: any } | null): obj is Coordinates => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCoordinates"')
      return Coordinates_possibleTypes.includes(obj.__typename)
    }
    


    const Coupon_possibleTypes: string[] = ['Coupon']
    export const isCoupon = (obj?: { __typename?: any } | null): obj is Coupon => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCoupon"')
      return Coupon_possibleTypes.includes(obj.__typename)
    }
    


    const CourierState_possibleTypes: string[] = ['CourierState']
    export const isCourierState = (obj?: { __typename?: any } | null): obj is CourierState => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isCourierState"')
      return CourierState_possibleTypes.includes(obj.__typename)
    }
    


    const DeliveryCalculate_possibleTypes: string[] = ['DeliveryCalculate']
    export const isDeliveryCalculate = (obj?: { __typename?: any } | null): obj is DeliveryCalculate => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isDeliveryCalculate"')
      return DeliveryCalculate_possibleTypes.includes(obj.__typename)
    }
    


    const DeliveryComputed_possibleTypes: string[] = ['DeliveryComputed']
    export const isDeliveryComputed = (obj?: { __typename?: any } | null): obj is DeliveryComputed => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isDeliveryComputed"')
      return DeliveryComputed_possibleTypes.includes(obj.__typename)
    }
    


    const DeliveryService_possibleTypes: string[] = ['DeliveryService']
    export const isDeliveryService = (obj?: { __typename?: any } | null): obj is DeliveryService => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isDeliveryService"')
      return DeliveryService_possibleTypes.includes(obj.__typename)
    }
    


    const DeliveryZone_possibleTypes: string[] = ['DeliveryZone']
    export const isDeliveryZone = (obj?: { __typename?: any } | null): obj is DeliveryZone => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isDeliveryZone"')
      return DeliveryZone_possibleTypes.includes(obj.__typename)
    }
    


    const Discount_possibleTypes: string[] = ['Discount']
    export const isDiscount = (obj?: { __typename?: any } | null): obj is Discount => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isDiscount"')
      return Discount_possibleTypes.includes(obj.__typename)
    }
    


    const DiscountProps_possibleTypes: string[] = ['DiscountProps']
    export const isDiscountProps = (obj?: { __typename?: any } | null): obj is DiscountProps => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isDiscountProps"')
      return DiscountProps_possibleTypes.includes(obj.__typename)
    }
    


    const DiscountPropsCONDITION_possibleTypes: string[] = ['DiscountPropsCONDITION']
    export const isDiscountPropsCONDITION = (obj?: { __typename?: any } | null): obj is DiscountPropsCONDITION => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isDiscountPropsCONDITION"')
      return DiscountPropsCONDITION_possibleTypes.includes(obj.__typename)
    }
    


    const Element_possibleTypes: string[] = ['Element']
    export const isElement = (obj?: { __typename?: any } | null): obj is Element => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isElement"')
      return Element_possibleTypes.includes(obj.__typename)
    }
    


    const ElementMeta_possibleTypes: string[] = ['ElementMeta']
    export const isElementMeta = (obj?: { __typename?: any } | null): obj is ElementMeta => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isElementMeta"')
      return ElementMeta_possibleTypes.includes(obj.__typename)
    }
    


    const ElementPropValueHtml_possibleTypes: string[] = ['ElementPropValueHtml']
    export const isElementPropValueHtml = (obj?: { __typename?: any } | null): obj is ElementPropValueHtml => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isElementPropValueHtml"')
      return ElementPropValueHtml_possibleTypes.includes(obj.__typename)
    }
    


    const ElementPropValueWithDesc_possibleTypes: string[] = ['ElementPropValueWithDesc']
    export const isElementPropValueWithDesc = (obj?: { __typename?: any } | null): obj is ElementPropValueWithDesc => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isElementPropValueWithDesc"')
      return ElementPropValueWithDesc_possibleTypes.includes(obj.__typename)
    }
    


    const EntityInfo_possibleTypes: string[] = ['EntityInfo']
    export const isEntityInfo = (obj?: { __typename?: any } | null): obj is EntityInfo => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isEntityInfo"')
      return EntityInfo_possibleTypes.includes(obj.__typename)
    }
    


    const EntityInfoUrls_possibleTypes: string[] = ['EntityInfoUrls']
    export const isEntityInfoUrls = (obj?: { __typename?: any } | null): obj is EntityInfoUrls => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isEntityInfoUrls"')
      return EntityInfoUrls_possibleTypes.includes(obj.__typename)
    }
    


    const EntityProp_possibleTypes: string[] = ['EntityProp']
    export const isEntityProp = (obj?: { __typename?: any } | null): obj is EntityProp => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isEntityProp"')
      return EntityProp_possibleTypes.includes(obj.__typename)
    }
    


    const ErrorInterface_possibleTypes: string[] = ['AccessError','CommonError','ExternalServiceError','FormError','OtpError','RateError']
    export const isErrorInterface = (obj?: { __typename?: any } | null): obj is ErrorInterface => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isErrorInterface"')
      return ErrorInterface_possibleTypes.includes(obj.__typename)
    }
    


    const ExternalServiceError_possibleTypes: string[] = ['ExternalServiceError']
    export const isExternalServiceError = (obj?: { __typename?: any } | null): obj is ExternalServiceError => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isExternalServiceError"')
      return ExternalServiceError_possibleTypes.includes(obj.__typename)
    }
    


    const FaqQuestion_possibleTypes: string[] = ['FaqQuestion']
    export const isFaqQuestion = (obj?: { __typename?: any } | null): obj is FaqQuestion => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isFaqQuestion"')
      return FaqQuestion_possibleTypes.includes(obj.__typename)
    }
    


    const FaqQuestionRecordset_possibleTypes: string[] = ['FaqQuestionRecordset']
    export const isFaqQuestionRecordset = (obj?: { __typename?: any } | null): obj is FaqQuestionRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isFaqQuestionRecordset"')
      return FaqQuestionRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const FormError_possibleTypes: string[] = ['FormError']
    export const isFormError = (obj?: { __typename?: any } | null): obj is FormError => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isFormError"')
      return FormError_possibleTypes.includes(obj.__typename)
    }
    


    const GeoObject_possibleTypes: string[] = ['GeoObject']
    export const isGeoObject = (obj?: { __typename?: any } | null): obj is GeoObject => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isGeoObject"')
      return GeoObject_possibleTypes.includes(obj.__typename)
    }
    


    const Image_possibleTypes: string[] = ['Image']
    export const isImage = (obj?: { __typename?: any } | null): obj is Image => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isImage"')
      return Image_possibleTypes.includes(obj.__typename)
    }
    


    const ListValue_possibleTypes: string[] = ['ListValue']
    export const isListValue = (obj?: { __typename?: any } | null): obj is ListValue => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isListValue"')
      return ListValue_possibleTypes.includes(obj.__typename)
    }
    


    const Menu_possibleTypes: string[] = ['Menu']
    export const isMenu = (obj?: { __typename?: any } | null): obj is Menu => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isMenu"')
      return Menu_possibleTypes.includes(obj.__typename)
    }
    


    const MenuItem_possibleTypes: string[] = ['MenuItem']
    export const isMenuItem = (obj?: { __typename?: any } | null): obj is MenuItem => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isMenuItem"')
      return MenuItem_possibleTypes.includes(obj.__typename)
    }
    


    const MenuItemMobile_possibleTypes: string[] = ['MenuItemMobile']
    export const isMenuItemMobile = (obj?: { __typename?: any } | null): obj is MenuItemMobile => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isMenuItemMobile"')
      return MenuItemMobile_possibleTypes.includes(obj.__typename)
    }
    


    const Message_possibleTypes: string[] = ['Message']
    export const isMessage = (obj?: { __typename?: any } | null): obj is Message => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isMessage"')
      return Message_possibleTypes.includes(obj.__typename)
    }
    


    const Mutation_possibleTypes: string[] = ['Mutation']
    export const isMutation = (obj?: { __typename?: any } | null): obj is Mutation => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isMutation"')
      return Mutation_possibleTypes.includes(obj.__typename)
    }
    


    const NoticePubSyncReadedResult_possibleTypes: string[] = ['NoticePubSyncReadedResult']
    export const isNoticePubSyncReadedResult = (obj?: { __typename?: any } | null): obj is NoticePubSyncReadedResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isNoticePubSyncReadedResult"')
      return NoticePubSyncReadedResult_possibleTypes.includes(obj.__typename)
    }
    


    const Offer_possibleTypes: string[] = ['Offer']
    export const isOffer = (obj?: { __typename?: any } | null): obj is Offer => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOffer"')
      return Offer_possibleTypes.includes(obj.__typename)
    }
    


    const OfferRecordset_possibleTypes: string[] = ['OfferRecordset']
    export const isOfferRecordset = (obj?: { __typename?: any } | null): obj is OfferRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOfferRecordset"')
      return OfferRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const OfferSlide_possibleTypes: string[] = ['OfferSlide']
    export const isOfferSlide = (obj?: { __typename?: any } | null): obj is OfferSlide => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOfferSlide"')
      return OfferSlide_possibleTypes.includes(obj.__typename)
    }
    


    const OfficeRecordset_possibleTypes: string[] = ['OfficeRecordset']
    export const isOfficeRecordset = (obj?: { __typename?: any } | null): obj is OfficeRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOfficeRecordset"')
      return OfficeRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const Order_possibleTypes: string[] = ['Order']
    export const isOrder = (obj?: { __typename?: any } | null): obj is Order => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrder"')
      return Order_possibleTypes.includes(obj.__typename)
    }
    


    const OrderAttribute_possibleTypes: string[] = ['OrderAttribute']
    export const isOrderAttribute = (obj?: { __typename?: any } | null): obj is OrderAttribute => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderAttribute"')
      return OrderAttribute_possibleTypes.includes(obj.__typename)
    }
    


    const OrderAttributeOption_possibleTypes: string[] = ['OrderAttributeOption']
    export const isOrderAttributeOption = (obj?: { __typename?: any } | null): obj is OrderAttributeOption => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderAttributeOption"')
      return OrderAttributeOption_possibleTypes.includes(obj.__typename)
    }
    


    const OrderAttributesValue_possibleTypes: string[] = ['OrderAttributesValue']
    export const isOrderAttributesValue = (obj?: { __typename?: any } | null): obj is OrderAttributesValue => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderAttributesValue"')
      return OrderAttributesValue_possibleTypes.includes(obj.__typename)
    }
    


    const OrderCancelReason_possibleTypes: string[] = ['OrderCancelReason']
    export const isOrderCancelReason = (obj?: { __typename?: any } | null): obj is OrderCancelReason => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderCancelReason"')
      return OrderCancelReason_possibleTypes.includes(obj.__typename)
    }
    


    const OrderData_possibleTypes: string[] = ['OrderData']
    export const isOrderData = (obj?: { __typename?: any } | null): obj is OrderData => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderData"')
      return OrderData_possibleTypes.includes(obj.__typename)
    }
    


    const OrderProfile_possibleTypes: string[] = ['OrderProfile']
    export const isOrderProfile = (obj?: { __typename?: any } | null): obj is OrderProfile => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderProfile"')
      return OrderProfile_possibleTypes.includes(obj.__typename)
    }
    


    const OrderProfileAttributesValue_possibleTypes: string[] = ['OrderProfileAttributesValue']
    export const isOrderProfileAttributesValue = (obj?: { __typename?: any } | null): obj is OrderProfileAttributesValue => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderProfileAttributesValue"')
      return OrderProfileAttributesValue_possibleTypes.includes(obj.__typename)
    }
    


    const OrderProfileRecordset_possibleTypes: string[] = ['OrderProfileRecordset']
    export const isOrderProfileRecordset = (obj?: { __typename?: any } | null): obj is OrderProfileRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderProfileRecordset"')
      return OrderProfileRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const OrderScope_possibleTypes: string[] = ['OrderScope']
    export const isOrderScope = (obj?: { __typename?: any } | null): obj is OrderScope => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderScope"')
      return OrderScope_possibleTypes.includes(obj.__typename)
    }
    


    const OrderStatus_possibleTypes: string[] = ['OrderStatus']
    export const isOrderStatus = (obj?: { __typename?: any } | null): obj is OrderStatus => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOrderStatus"')
      return OrderStatus_possibleTypes.includes(obj.__typename)
    }
    


    const OtpError_possibleTypes: string[] = ['OtpError']
    export const isOtpError = (obj?: { __typename?: any } | null): obj is OtpError => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isOtpError"')
      return OtpError_possibleTypes.includes(obj.__typename)
    }
    


    const Page_possibleTypes: string[] = ['Page']
    export const isPage = (obj?: { __typename?: any } | null): obj is Page => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPage"')
      return Page_possibleTypes.includes(obj.__typename)
    }
    


    const PageContentChunk_possibleTypes: string[] = ['PageContentChunk']
    export const isPageContentChunk = (obj?: { __typename?: any } | null): obj is PageContentChunk => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPageContentChunk"')
      return PageContentChunk_possibleTypes.includes(obj.__typename)
    }
    


    const PageDataChunk_possibleTypes: string[] = ['PageDataChunk']
    export const isPageDataChunk = (obj?: { __typename?: any } | null): obj is PageDataChunk => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPageDataChunk"')
      return PageDataChunk_possibleTypes.includes(obj.__typename)
    }
    


    const PageRecordset_possibleTypes: string[] = ['PageRecordset']
    export const isPageRecordset = (obj?: { __typename?: any } | null): obj is PageRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPageRecordset"')
      return PageRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const Payment_possibleTypes: string[] = ['Payment']
    export const isPayment = (obj?: { __typename?: any } | null): obj is Payment => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPayment"')
      return Payment_possibleTypes.includes(obj.__typename)
    }
    


    const PaymentType_possibleTypes: string[] = ['PaymentType']
    export const isPaymentType = (obj?: { __typename?: any } | null): obj is PaymentType => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPaymentType"')
      return PaymentType_possibleTypes.includes(obj.__typename)
    }
    


    const Paysystem_possibleTypes: string[] = ['Paysystem']
    export const isPaysystem = (obj?: { __typename?: any } | null): obj is Paysystem => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPaysystem"')
      return Paysystem_possibleTypes.includes(obj.__typename)
    }
    


    const PersonType_possibleTypes: string[] = ['PersonType']
    export const isPersonType = (obj?: { __typename?: any } | null): obj is PersonType => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isPersonType"')
      return PersonType_possibleTypes.includes(obj.__typename)
    }
    


    const Product_possibleTypes: string[] = ['Product']
    export const isProduct = (obj?: { __typename?: any } | null): obj is Product => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProduct"')
      return Product_possibleTypes.includes(obj.__typename)
    }
    


    const ProductBenefit_possibleTypes: string[] = ['ProductBenefit']
    export const isProductBenefit = (obj?: { __typename?: any } | null): obj is ProductBenefit => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductBenefit"')
      return ProductBenefit_possibleTypes.includes(obj.__typename)
    }
    


    const ProductFlag_possibleTypes: string[] = ['ProductFlag']
    export const isProductFlag = (obj?: { __typename?: any } | null): obj is ProductFlag => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductFlag"')
      return ProductFlag_possibleTypes.includes(obj.__typename)
    }
    


    const ProductMeasure_possibleTypes: string[] = ['ProductMeasure']
    export const isProductMeasure = (obj?: { __typename?: any } | null): obj is ProductMeasure => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductMeasure"')
      return ProductMeasure_possibleTypes.includes(obj.__typename)
    }
    


    const ProductPrice_possibleTypes: string[] = ['ProductPrice']
    export const isProductPrice = (obj?: { __typename?: any } | null): obj is ProductPrice => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductPrice"')
      return ProductPrice_possibleTypes.includes(obj.__typename)
    }
    


    const ProductProps_possibleTypes: string[] = ['ProductProps']
    export const isProductProps = (obj?: { __typename?: any } | null): obj is ProductProps => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductProps"')
      return ProductProps_possibleTypes.includes(obj.__typename)
    }
    


    const ProductPropsAVAILABLE_SCHEDULE_possibleTypes: string[] = ['ProductPropsAVAILABLE_SCHEDULE']
    export const isProductPropsAVAILABLE_SCHEDULE = (obj?: { __typename?: any } | null): obj is ProductPropsAVAILABLE_SCHEDULE => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductPropsAVAILABLE_SCHEDULE"')
      return ProductPropsAVAILABLE_SCHEDULE_possibleTypes.includes(obj.__typename)
    }
    


    const ProductPropsBENEFITS_possibleTypes: string[] = ['ProductPropsBENEFITS']
    export const isProductPropsBENEFITS = (obj?: { __typename?: any } | null): obj is ProductPropsBENEFITS => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductPropsBENEFITS"')
      return ProductPropsBENEFITS_possibleTypes.includes(obj.__typename)
    }
    


    const ProductPropsSET_ITEMS_possibleTypes: string[] = ['ProductPropsSET_ITEMS']
    export const isProductPropsSET_ITEMS = (obj?: { __typename?: any } | null): obj is ProductPropsSET_ITEMS => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductPropsSET_ITEMS"')
      return ProductPropsSET_ITEMS_possibleTypes.includes(obj.__typename)
    }
    


    const ProductSection_possibleTypes: string[] = ['ProductSection']
    export const isProductSection = (obj?: { __typename?: any } | null): obj is ProductSection => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductSection"')
      return ProductSection_possibleTypes.includes(obj.__typename)
    }
    


    const ProductSectionProps_possibleTypes: string[] = ['ProductSectionProps']
    export const isProductSectionProps = (obj?: { __typename?: any } | null): obj is ProductSectionProps => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductSectionProps"')
      return ProductSectionProps_possibleTypes.includes(obj.__typename)
    }
    


    const ProductSetItem_possibleTypes: string[] = ['ProductSetItem']
    export const isProductSetItem = (obj?: { __typename?: any } | null): obj is ProductSetItem => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductSetItem"')
      return ProductSetItem_possibleTypes.includes(obj.__typename)
    }
    


    const ProductTag_possibleTypes: string[] = ['ProductTag']
    export const isProductTag = (obj?: { __typename?: any } | null): obj is ProductTag => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isProductTag"')
      return ProductTag_possibleTypes.includes(obj.__typename)
    }
    


    const Query_possibleTypes: string[] = ['Query']
    export const isQuery = (obj?: { __typename?: any } | null): obj is Query => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isQuery"')
      return Query_possibleTypes.includes(obj.__typename)
    }
    


    const QueryInfo_possibleTypes: string[] = ['QueryInfo']
    export const isQueryInfo = (obj?: { __typename?: any } | null): obj is QueryInfo => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isQueryInfo"')
      return QueryInfo_possibleTypes.includes(obj.__typename)
    }
    


    const RateError_possibleTypes: string[] = ['RateError']
    export const isRateError = (obj?: { __typename?: any } | null): obj is RateError => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isRateError"')
      return RateError_possibleTypes.includes(obj.__typename)
    }
    


    const Response_possibleTypes: string[] = ['Response']
    export const isResponse = (obj?: { __typename?: any } | null): obj is Response => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isResponse"')
      return Response_possibleTypes.includes(obj.__typename)
    }
    


    const ResponseRate_possibleTypes: string[] = ['ResponseRate']
    export const isResponseRate = (obj?: { __typename?: any } | null): obj is ResponseRate => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isResponseRate"')
      return ResponseRate_possibleTypes.includes(obj.__typename)
    }
    


    const ResponseRedirect_possibleTypes: string[] = ['ResponseRedirect']
    export const isResponseRedirect = (obj?: { __typename?: any } | null): obj is ResponseRedirect => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isResponseRedirect"')
      return ResponseRedirect_possibleTypes.includes(obj.__typename)
    }
    


    const ResponseState_possibleTypes: string[] = ['ResponseState']
    export const isResponseState = (obj?: { __typename?: any } | null): obj is ResponseState => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isResponseState"')
      return ResponseState_possibleTypes.includes(obj.__typename)
    }
    


    const Review_possibleTypes: string[] = ['Review']
    export const isReview = (obj?: { __typename?: any } | null): obj is Review => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isReview"')
      return Review_possibleTypes.includes(obj.__typename)
    }
    


    const ReviewOrderGuestReviewResult_possibleTypes: string[] = ['ReviewOrderGuestReviewResult']
    export const isReviewOrderGuestReviewResult = (obj?: { __typename?: any } | null): obj is ReviewOrderGuestReviewResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isReviewOrderGuestReviewResult"')
      return ReviewOrderGuestReviewResult_possibleTypes.includes(obj.__typename)
    }
    


    const ReviewOrderGuestReviewResultPayload_possibleTypes: string[] = ['ReviewOrderGuestReviewResultPayload']
    export const isReviewOrderGuestReviewResultPayload = (obj?: { __typename?: any } | null): obj is ReviewOrderGuestReviewResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isReviewOrderGuestReviewResultPayload"')
      return ReviewOrderGuestReviewResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const ReviewServiceReviewResult_possibleTypes: string[] = ['ReviewServiceReviewResult']
    export const isReviewServiceReviewResult = (obj?: { __typename?: any } | null): obj is ReviewServiceReviewResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isReviewServiceReviewResult"')
      return ReviewServiceReviewResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleClientCard_possibleTypes: string[] = ['SaleClientCard']
    export const isSaleClientCard = (obj?: { __typename?: any } | null): obj is SaleClientCard => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleClientCard"')
      return SaleClientCard_possibleTypes.includes(obj.__typename)
    }
    


    const SaleOrderCancelResult_possibleTypes: string[] = ['SaleOrderCancelResult']
    export const isSaleOrderCancelResult = (obj?: { __typename?: any } | null): obj is SaleOrderCancelResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleOrderCancelResult"')
      return SaleOrderCancelResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleOrderPayOnlineResult_possibleTypes: string[] = ['SaleOrderPayOnlineResult']
    export const isSaleOrderPayOnlineResult = (obj?: { __typename?: any } | null): obj is SaleOrderPayOnlineResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleOrderPayOnlineResult"')
      return SaleOrderPayOnlineResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleOrderPayOnlineResultPayload_possibleTypes: string[] = ['SaleOrderPayOnlineResultPayload']
    export const isSaleOrderPayOnlineResultPayload = (obj?: { __typename?: any } | null): obj is SaleOrderPayOnlineResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleOrderPayOnlineResultPayload"')
      return SaleOrderPayOnlineResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SaleOrderRepeatResult_possibleTypes: string[] = ['SaleOrderRepeatResult']
    export const isSaleOrderRepeatResult = (obj?: { __typename?: any } | null): obj is SaleOrderRepeatResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleOrderRepeatResult"')
      return SaleOrderRepeatResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleProfileCalcDeliveryResult_possibleTypes: string[] = ['SaleProfileCalcDeliveryResult']
    export const isSaleProfileCalcDeliveryResult = (obj?: { __typename?: any } | null): obj is SaleProfileCalcDeliveryResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleProfileCalcDeliveryResult"')
      return SaleProfileCalcDeliveryResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleProfileDefaultResult_possibleTypes: string[] = ['SaleProfileDefaultResult']
    export const isSaleProfileDefaultResult = (obj?: { __typename?: any } | null): obj is SaleProfileDefaultResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleProfileDefaultResult"')
      return SaleProfileDefaultResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleProfileDeleteResult_possibleTypes: string[] = ['SaleProfileDeleteResult']
    export const isSaleProfileDeleteResult = (obj?: { __typename?: any } | null): obj is SaleProfileDeleteResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleProfileDeleteResult"')
      return SaleProfileDeleteResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleProfileSaveResult_possibleTypes: string[] = ['SaleProfileSaveResult']
    export const isSaleProfileSaveResult = (obj?: { __typename?: any } | null): obj is SaleProfileSaveResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleProfileSaveResult"')
      return SaleProfileSaveResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderApplyResult_possibleTypes: string[] = ['SaleVorderApplyResult']
    export const isSaleVorderApplyResult = (obj?: { __typename?: any } | null): obj is SaleVorderApplyResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderApplyResult"')
      return SaleVorderApplyResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderApplyResultPayload_possibleTypes: string[] = ['SaleVorderApplyResultPayload']
    export const isSaleVorderApplyResultPayload = (obj?: { __typename?: any } | null): obj is SaleVorderApplyResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderApplyResultPayload"')
      return SaleVorderApplyResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderBasketResult_possibleTypes: string[] = ['SaleVorderBasketResult']
    export const isSaleVorderBasketResult = (obj?: { __typename?: any } | null): obj is SaleVorderBasketResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderBasketResult"')
      return SaleVorderBasketResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderBasketResultPayload_possibleTypes: string[] = ['SaleVorderBasketResultPayload']
    export const isSaleVorderBasketResultPayload = (obj?: { __typename?: any } | null): obj is SaleVorderBasketResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderBasketResultPayload"')
      return SaleVorderBasketResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderCouponResult_possibleTypes: string[] = ['SaleVorderCouponResult']
    export const isSaleVorderCouponResult = (obj?: { __typename?: any } | null): obj is SaleVorderCouponResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderCouponResult"')
      return SaleVorderCouponResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderCouponResultPayload_possibleTypes: string[] = ['SaleVorderCouponResultPayload']
    export const isSaleVorderCouponResultPayload = (obj?: { __typename?: any } | null): obj is SaleVorderCouponResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderCouponResultPayload"')
      return SaleVorderCouponResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderNewResult_possibleTypes: string[] = ['SaleVorderNewResult']
    export const isSaleVorderNewResult = (obj?: { __typename?: any } | null): obj is SaleVorderNewResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderNewResult"')
      return SaleVorderNewResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderNewResultPayload_possibleTypes: string[] = ['SaleVorderNewResultPayload']
    export const isSaleVorderNewResultPayload = (obj?: { __typename?: any } | null): obj is SaleVorderNewResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderNewResultPayload"')
      return SaleVorderNewResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderReserveResult_possibleTypes: string[] = ['SaleVorderReserveResult']
    export const isSaleVorderReserveResult = (obj?: { __typename?: any } | null): obj is SaleVorderReserveResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderReserveResult"')
      return SaleVorderReserveResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderReserveResultPayload_possibleTypes: string[] = ['SaleVorderReserveResultPayload']
    export const isSaleVorderReserveResultPayload = (obj?: { __typename?: any } | null): obj is SaleVorderReserveResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderReserveResultPayload"')
      return SaleVorderReserveResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderSubmitResult_possibleTypes: string[] = ['SaleVorderSubmitResult']
    export const isSaleVorderSubmitResult = (obj?: { __typename?: any } | null): obj is SaleVorderSubmitResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderSubmitResult"')
      return SaleVorderSubmitResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderSubmitResultPayload_possibleTypes: string[] = ['SaleVorderSubmitResultPayload']
    export const isSaleVorderSubmitResultPayload = (obj?: { __typename?: any } | null): obj is SaleVorderSubmitResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderSubmitResultPayload"')
      return SaleVorderSubmitResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderSyncResult_possibleTypes: string[] = ['SaleVorderSyncResult']
    export const isSaleVorderSyncResult = (obj?: { __typename?: any } | null): obj is SaleVorderSyncResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderSyncResult"')
      return SaleVorderSyncResult_possibleTypes.includes(obj.__typename)
    }
    


    const SaleVorderSyncResultPayload_possibleTypes: string[] = ['SaleVorderSyncResultPayload']
    export const isSaleVorderSyncResultPayload = (obj?: { __typename?: any } | null): obj is SaleVorderSyncResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSaleVorderSyncResultPayload"')
      return SaleVorderSyncResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const SearchResult_possibleTypes: string[] = ['SearchResult']
    export const isSearchResult = (obj?: { __typename?: any } | null): obj is SearchResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSearchResult"')
      return SearchResult_possibleTypes.includes(obj.__typename)
    }
    


    const SearchSuggestion_possibleTypes: string[] = ['SearchSuggestion']
    export const isSearchSuggestion = (obj?: { __typename?: any } | null): obj is SearchSuggestion => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSearchSuggestion"')
      return SearchSuggestion_possibleTypes.includes(obj.__typename)
    }
    


    const SearchSuggestionData_possibleTypes: string[] = ['SearchSuggestionData']
    export const isSearchSuggestionData = (obj?: { __typename?: any } | null): obj is SearchSuggestionData => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSearchSuggestionData"')
      return SearchSuggestionData_possibleTypes.includes(obj.__typename)
    }
    


    const Section_possibleTypes: string[] = ['Section']
    export const isSection = (obj?: { __typename?: any } | null): obj is Section => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSection"')
      return Section_possibleTypes.includes(obj.__typename)
    }
    


    const SpecialOffer_possibleTypes: string[] = ['SpecialOffer']
    export const isSpecialOffer = (obj?: { __typename?: any } | null): obj is SpecialOffer => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSpecialOffer"')
      return SpecialOffer_possibleTypes.includes(obj.__typename)
    }
    


    const SpecialOfferType_possibleTypes: string[] = ['SpecialOfferType']
    export const isSpecialOfferType = (obj?: { __typename?: any } | null): obj is SpecialOfferType => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isSpecialOfferType"')
      return SpecialOfferType_possibleTypes.includes(obj.__typename)
    }
    


    const User_possibleTypes: string[] = ['User']
    export const isUser = (obj?: { __typename?: any } | null): obj is User => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUser"')
      return User_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthConfirm_possibleTypes: string[] = ['UserAuthConfirm']
    export const isUserAuthConfirm = (obj?: { __typename?: any } | null): obj is UserAuthConfirm => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthConfirm"')
      return UserAuthConfirm_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthConfirmStep_possibleTypes: string[] = ['UserAuthConfirmStep']
    export const isUserAuthConfirmStep = (obj?: { __typename?: any } | null): obj is UserAuthConfirmStep => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthConfirmStep"')
      return UserAuthConfirmStep_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthLoginConfirmResult_possibleTypes: string[] = ['UserAuthLoginConfirmResult']
    export const isUserAuthLoginConfirmResult = (obj?: { __typename?: any } | null): obj is UserAuthLoginConfirmResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthLoginConfirmResult"')
      return UserAuthLoginConfirmResult_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthLoginConfirmResultPayload_possibleTypes: string[] = ['UserAuthLoginConfirmResultPayload']
    export const isUserAuthLoginConfirmResultPayload = (obj?: { __typename?: any } | null): obj is UserAuthLoginConfirmResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthLoginConfirmResultPayload"')
      return UserAuthLoginConfirmResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthLoginRequestResult_possibleTypes: string[] = ['UserAuthLoginRequestResult']
    export const isUserAuthLoginRequestResult = (obj?: { __typename?: any } | null): obj is UserAuthLoginRequestResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthLoginRequestResult"')
      return UserAuthLoginRequestResult_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthLoginRequestResultPayload_possibleTypes: string[] = ['UserAuthLoginRequestResultPayload']
    export const isUserAuthLoginRequestResultPayload = (obj?: { __typename?: any } | null): obj is UserAuthLoginRequestResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthLoginRequestResultPayload"')
      return UserAuthLoginRequestResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthLoginStartResult_possibleTypes: string[] = ['UserAuthLoginStartResult']
    export const isUserAuthLoginStartResult = (obj?: { __typename?: any } | null): obj is UserAuthLoginStartResult => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthLoginStartResult"')
      return UserAuthLoginStartResult_possibleTypes.includes(obj.__typename)
    }
    


    const UserAuthLoginStartResultPayload_possibleTypes: string[] = ['UserAuthLoginStartResultPayload']
    export const isUserAuthLoginStartResultPayload = (obj?: { __typename?: any } | null): obj is UserAuthLoginStartResultPayload => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAuthLoginStartResultPayload"')
      return UserAuthLoginStartResultPayload_possibleTypes.includes(obj.__typename)
    }
    


    const UserAvatar_possibleTypes: string[] = ['UserAvatar']
    export const isUserAvatar = (obj?: { __typename?: any } | null): obj is UserAvatar => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserAvatar"')
      return UserAvatar_possibleTypes.includes(obj.__typename)
    }
    


    const UserFamily_possibleTypes: string[] = ['UserFamily']
    export const isUserFamily = (obj?: { __typename?: any } | null): obj is UserFamily => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserFamily"')
      return UserFamily_possibleTypes.includes(obj.__typename)
    }
    


    const UserGroup_possibleTypes: string[] = ['UserGroup']
    export const isUserGroup = (obj?: { __typename?: any } | null): obj is UserGroup => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserGroup"')
      return UserGroup_possibleTypes.includes(obj.__typename)
    }
    


    const UserSession_possibleTypes: string[] = ['UserSession']
    export const isUserSession = (obj?: { __typename?: any } | null): obj is UserSession => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isUserSession"')
      return UserSession_possibleTypes.includes(obj.__typename)
    }
    


    const VacancyRecordset_possibleTypes: string[] = ['VacancyRecordset']
    export const isVacancyRecordset = (obj?: { __typename?: any } | null): obj is VacancyRecordset => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isVacancyRecordset"')
      return VacancyRecordset_possibleTypes.includes(obj.__typename)
    }
    


    const Vorder_possibleTypes: string[] = ['Vorder']
    export const isVorder = (obj?: { __typename?: any } | null): obj is Vorder => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isVorder"')
      return Vorder_possibleTypes.includes(obj.__typename)
    }
    


    const VorderCurrent_possibleTypes: string[] = ['VorderCurrent']
    export const isVorderCurrent = (obj?: { __typename?: any } | null): obj is VorderCurrent => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isVorderCurrent"')
      return VorderCurrent_possibleTypes.includes(obj.__typename)
    }
    


    const VorderSummary_possibleTypes: string[] = ['VorderSummary']
    export const isVorderSummary = (obj?: { __typename?: any } | null): obj is VorderSummary => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isVorderSummary"')
      return VorderSummary_possibleTypes.includes(obj.__typename)
    }
    


    const Webcam_possibleTypes: string[] = ['Webcam']
    export const isWebcam = (obj?: { __typename?: any } | null): obj is Webcam => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isWebcam"')
      return Webcam_possibleTypes.includes(obj.__typename)
    }
    


    const WebcamProps_possibleTypes: string[] = ['WebcamProps']
    export const isWebcamProps = (obj?: { __typename?: any } | null): obj is WebcamProps => {
      if (!obj?.__typename) throw new Error('__typename is missing in "isWebcamProps"')
      return WebcamProps_possibleTypes.includes(obj.__typename)
    }
    

export const enumAttrPaymentTypeEnum = {
   card: 'card' as const,
   cash: 'cash' as const,
   online: 'online' as const
}

export const enumAttrTimeModeEnum = {
   custom: 'custom' as const,
   nearest: 'nearest' as const
}

export const enumAttrTransportTypeEnum = {
   courier: 'courier' as const,
   pickup: 'pickup' as const
}

export const enumBasketItemBenefitType = {
   gift: 'gift' as const,
   special: 'special' as const
}

export const enumBasketItemDiscountTarget = {
   basket: 'basket' as const,
   delivery: 'delivery' as const,
   product: 'product' as const,
   section: 'section' as const,
   total: 'total' as const
}

export const enumBasketItemDiscountType = {
   fixed: 'fixed' as const,
   percent: 'percent' as const
}

export const enumBasketRuleActionTypeEnum = {
   discount: 'discount' as const,
   gift: 'gift' as const
}

export const enumBasketRuleConditionTypeEnum = {
   attr_value: 'attr_value' as const,
   bonus_filled: 'bonus_filled' as const,
   coupons_filled: 'coupons_filled' as const,
   delivery_id: 'delivery_id' as const,
   group_and: 'group_and' as const,
   group_or: 'group_or' as const,
   order_price: 'order_price' as const,
   payment_type: 'payment_type' as const,
   paysystem_id: 'paysystem_id' as const,
   product_id: 'product_id' as const,
   section_id: 'section_id' as const,
   transport_type: 'transport_type' as const
}

export const enumBasketRuleType = {
   common: 'common' as const,
   discount: 'discount' as const,
   gift: 'gift' as const
}

export const enumBasketRulesResulBenefitProductType = {
   productGift: 'productGift' as const,
   productSpecial: 'productSpecial' as const
}

export const enumDateFormatEnum = {
   date: 'date' as const,
   datetime: 'datetime' as const
}

export const enumDeliveryTransportTypeEnum = {
   courier: 'courier' as const,
   pickup: 'pickup' as const
}

export const enumDiscountModeEnum = {
   fixed: 'fixed' as const,
   percent: 'percent' as const
}

export const enumDiscountTargetEnum = {
   basket: 'basket' as const,
   delivery: 'delivery' as const,
   product: 'product' as const,
   total: 'total' as const
}

export const enumErrorNameEnum = {
   access_not_authorized: 'access_not_authorized' as const,
   access_restricted: 'access_restricted' as const,
   common: 'common' as const,
   common_input_error: 'common_input_error' as const,
   common_invalid_format: 'common_invalid_format' as const,
   common_not_found: 'common_not_found' as const,
   es_blocked: 'es_blocked' as const,
   es_rate_limit: 'es_rate_limit' as const,
   es_rejected: 'es_rejected' as const,
   field_format_invalid: 'field_format_invalid' as const,
   field_required: 'field_required' as const,
   otp_attempts: 'otp_attempts' as const,
   otp_code_wrong: 'otp_code_wrong' as const,
   rate_limit: 'rate_limit' as const
}

export const enumErrorTypeEnum = {
   access: 'access' as const,
   common: 'common' as const,
   external_service: 'external_service' as const,
   form: 'form' as const,
   otp: 'otp' as const,
   rate: 'rate' as const
}

export const enumMessageTypeEnum = {
   error: 'error' as const,
   info: 'info' as const,
   success: 'success' as const,
   warning: 'warning' as const
}

export const enumOrderAttributeCodeEnum = {
   ADDRESS: 'ADDRESS' as const,
   ADDRESS_IS_CUSTOM: 'ADDRESS_IS_CUSTOM' as const,
   ADDRESS_SOURCE: 'ADDRESS_SOURCE' as const,
   APP_VERSION: 'APP_VERSION' as const,
   BENEFIT_TYPE: 'BENEFIT_TYPE' as const,
   BONUSES: 'BONUSES' as const,
   CASH_SUM: 'CASH_SUM' as const,
   CITY: 'CITY' as const,
   CITY_FIAS_ID: 'CITY_FIAS_ID' as const,
   COMMENT: 'COMMENT' as const,
   DATA: 'DATA' as const,
   DATE: 'DATE' as const,
   DATETIME: 'DATETIME' as const,
   DELIVERY_DEPARTMENT: 'DELIVERY_DEPARTMENT' as const,
   DELIVERY_FREE_FROM_PRICE: 'DELIVERY_FREE_FROM_PRICE' as const,
   DELIVERY_FREE_UPDATED_TIME: 'DELIVERY_FREE_UPDATED_TIME' as const,
   DELIVERY_ID: 'DELIVERY_ID' as const,
   DELIVERY_PRICE: 'DELIVERY_PRICE' as const,
   DEPARTMENT_SERVICE_ID: 'DEPARTMENT_SERVICE_ID' as const,
   DETAILS: 'DETAILS' as const,
   DISCOUNT_PERCENT: 'DISCOUNT_PERCENT' as const,
   DISCOUNT_REASON: 'DISCOUNT_REASON' as const,
   EMAIL: 'EMAIL' as const,
   ENTRANCE: 'ENTRANCE' as const,
   FIO: 'FIO' as const,
   FLAT: 'FLAT' as const,
   FLOOR: 'FLOOR' as const,
   GIFTS_LIST: 'GIFTS_LIST' as const,
   HOUSE: 'HOUSE' as const,
   HOUSE_COORDS: 'HOUSE_COORDS' as const,
   HOUSE_FIAS_ID: 'HOUSE_FIAS_ID' as const,
   INTERCOM: 'INTERCOM' as const,
   LIFT: 'LIFT' as const,
   LOCATION: 'LOCATION' as const,
   NEED_CONFIRM: 'NEED_CONFIRM' as const,
   PAYMENT_TYPE: 'PAYMENT_TYPE' as const,
   PAY_SYSTEM_ID: 'PAY_SYSTEM_ID' as const,
   PERSONS_NUMBER: 'PERSONS_NUMBER' as const,
   PHONE: 'PHONE' as const,
   PICKUP_DEPARTMENT: 'PICKUP_DEPARTMENT' as const,
   PRIVATE_HOUSE: 'PRIVATE_HOUSE' as const,
   PROFILE_COMMENT: 'PROFILE_COMMENT' as const,
   PROFILE_DEFAULT: 'PROFILE_DEFAULT' as const,
   PROFILE_ID: 'PROFILE_ID' as const,
   PROMOCODE: 'PROMOCODE' as const,
   RECEIVER_ANOTHER: 'RECEIVER_ANOTHER' as const,
   RECEIVER_NAME: 'RECEIVER_NAME' as const,
   RECEIVER_PHONE: 'RECEIVER_PHONE' as const,
   RESERVE_AVAILABLE_TIME: 'RESERVE_AVAILABLE_TIME' as const,
   RESERVE_NEED_TIME: 'RESERVE_NEED_TIME' as const,
   RESERVE_REQUEST_TIME: 'RESERVE_REQUEST_TIME' as const,
   RESERVE_STATUS: 'RESERVE_STATUS' as const,
   RESERVE_SUCCESS_HASH: 'RESERVE_SUCCESS_HASH' as const,
   RESERVE_SUCCESS_REQUEST_TIME: 'RESERVE_SUCCESS_REQUEST_TIME' as const,
   ROISTAT: 'ROISTAT' as const,
   SERVICE_SEND: 'SERVICE_SEND' as const,
   SERVICE_SEND_START: 'SERVICE_SEND_START' as const,
   SETTLEMENT: 'SETTLEMENT' as const,
   SETTLEMENT_FIAS_ID: 'SETTLEMENT_FIAS_ID' as const,
   SOURCE: 'SOURCE' as const,
   STREET: 'STREET' as const,
   STREET_COORDS: 'STREET_COORDS' as const,
   STREET_FIAS_ID: 'STREET_FIAS_ID' as const,
   STREET_PATH: 'STREET_PATH' as const,
   STRUCT: 'STRUCT' as const,
   TEST_TIME: 'TEST_TIME' as const,
   TIME: 'TIME' as const,
   TIME_MODE: 'TIME_MODE' as const,
   TRANSPORT_TYPE: 'TRANSPORT_TYPE' as const,
   USER_DESCRIPTION: 'USER_DESCRIPTION' as const,
   UUID: 'UUID' as const,
   VORDER_ID: 'VORDER_ID' as const,
   WITH_OPERATOR: 'WITH_OPERATOR' as const
}

export const enumOrderAttributeKindEnum = {
   field: 'field' as const,
   prop: 'prop' as const
}

export const enumOrderAttributeTypeEnum = {
   boolean: 'boolean' as const,
   date: 'date' as const,
   enum: 'enum' as const,
   json: 'json' as const,
   location: 'location' as const,
   number: 'number' as const,
   string: 'string' as const
}

export const enumTimeModeEnum = {
   custom: 'custom' as const,
   nearest: 'nearest' as const
}


export interface BonusLevelActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface BonusLevelDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface BonusLevelDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface BonusLevelPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface BonusLevelShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface BonusLevelTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ClientEmitCreatedAtArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface ClientNoticeCreatedAtArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface CompanyOfficeActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface CompanyOfficeDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface CompanyOfficeDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface CompanyOfficePreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface CompanyOfficeShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface CompanyOfficeTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface CompanyVacancyActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface CompanyVacancyDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface CompanyVacancyDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface CompanyVacancyPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface CompanyVacancyShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface CompanyVacancyTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ConstructorElementActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ConstructorElementDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ConstructorElementDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ConstructorElementPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ConstructorElementShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ConstructorElementTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface DeliveryZonePreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface DiscountActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface DiscountDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface DiscountDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface DiscountPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface DiscountShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface DiscountTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ElementActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ElementDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ElementDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ElementPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ElementShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ElementTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface FaqQuestionActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface FaqQuestionDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface FaqQuestionDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface FaqQuestionPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface FaqQuestionShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface FaqQuestionTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface MutationCatalogProductOrderArgs { 
    elementId?: (Scalars['Int'] | null), model?: (Scalars['Json'] | null) 
}
export interface MutationCompanyOfficeUpdateArgs { 
    id: Scalars['Int'], model: CompanyOfficeInput 
}
export interface MutationCompanyVacancyOrderArgs { 
    id?: (Scalars['Int'] | null), model?: (Scalars['Json'] | null) 
}
export interface MutationNoticePubPushUpdateTokenArgs { 
    pushToken?: (Scalars['String'] | null) 
}
export interface MutationNoticePubSyncReadedArgs { 
    ids?: (Scalars['Json'] | null) 
}
export interface MutationReviewOrderGuestReviewArgs { 
    
    /** Order service id */
    orderServiceId?: (Scalars['Int'] | null), type?: (Scalars['String'] | null) 
}
export interface MutationReviewServiceReviewArgs { 
    model?: (Scalars['Json'] | null) 
}
export interface MutationSaleClientCardCreateArgs { 
    model: ElementInput 
}
export interface MutationSaleClientCardDeleteArgs { 
    id?: (Scalars['Int'] | null) 
}
export interface MutationSaleClientCardUpdateArgs { 
    id: Scalars['Int'], model: ElementInput 
}
export interface MutationSaleOrderCancelArgs { 
    comment?: (Scalars['String'] | null), hash?: (Scalars['String'] | null), id?: (Scalars['Int'] | null), reason?: (Scalars['String'] | null) 
}
export interface MutationSaleOrderPayOnlineArgs { 
    hash?: (Scalars['String'] | null), id?: (Scalars['Int'] | null), savePaymentType?: (Scalars['Boolean'] | null), type?: (Scalars['String'] | null) 
}
export interface MutationSaleOrderRepeatArgs { 
    hash?: (Scalars['String'] | null), id?: (Scalars['Int'] | null) 
}
export interface MutationSaleProfileCalcDeliveryArgs { 
    attrs?: (OrderAttributesValueInput | null), basket?: (Scalars['Json'] | null), profileId?: (Scalars['Int'] | null), timeMode?: (TimeModeEnum | null), timeNeed?: (Scalars['Int'] | null) 
}
export interface MutationSaleProfileDefaultArgs { 
    id?: (Scalars['Int'] | null) 
}
export interface MutationSaleProfileDeleteArgs { 
    id?: (Scalars['Int'] | null) 
}
export interface MutationSaleProfileSaveArgs { 
    attrs?: (OrderAttributesValueInput | null), id?: (Scalars['Int'] | null) 
}
export interface MutationSaleVorderApplyArgs { 
    order?: (VorderInput | null), params?: (Scalars['Json'] | null) 
}
export interface MutationSaleVorderBasketArgs { 
    action?: (Scalars['String'] | null), build?: (Scalars['Json'] | null), order?: (VorderInput | null) 
}
export interface MutationSaleVorderCouponArgs { 
    action?: (Scalars['String'] | null), couponCode?: (Scalars['String'] | null), order?: (VorderInput | null) 
}
export interface MutationSaleVorderReserveArgs { 
    order?: (VorderInput | null) 
}
export interface MutationSaleVorderSubmitArgs { 
    order?: (VorderInput | null) 
}
export interface MutationSaleVorderSyncArgs { 
    order?: (VorderInput | null) 
}
export interface MutationUserAppClientArgs { 
    debugParams?: (Scalars['Json'] | null), mobilePushToken?: (Scalars['String'] | null), webPushToken?: (Scalars['String'] | null) 
}
export interface MutationUserAuthCreateSaArgs { 
    clientIds?: (Scalars['Json'] | null), code?: (Scalars['String'] | null), phone?: (Scalars['String'] | null) 
}
export interface MutationUserAuthLoginConfirmArgs { 
    captcha?: (CaptchaInput | null), clientId?: (Scalars['String'] | null), code?: (Scalars['String'] | null), confirmMode?: (Scalars['String'] | null), disableBasketTransfer?: (Scalars['Boolean'] | null), phone?: (Scalars['String'] | null), pushToken?: (Scalars['String'] | null), sid?: (Scalars['String'] | null) 
}
export interface MutationUserAuthLoginRequestArgs { 
    captcha?: (CaptchaInput | null), confirmMode?: (Scalars['String'] | null), phone?: (Scalars['String'] | null) 
}
export interface MutationUserAuthLoginStartArgs { 
    captcha?: (CaptchaInput | null), phone?: (Scalars['String'] | null) 
}
export interface MutationUserProfileBirthdayArgs { 
    birthday?: (Scalars['String'] | null) 
}
export interface MutationUserProfileChildArgs { 
    action?: (Scalars['String'] | null), child?: (UserFamilyInput | null) 
}
export interface MutationUserProfileEmailArgs { 
    action?: (Scalars['String'] | null), code?: (Scalars['String'] | null), email?: (Scalars['String'] | null) 
}
export interface MutationUserProfileNameArgs { 
    name?: (Scalars['String'] | null) 
}
export interface MutationUserProfileSaveArgs { 
    form?: (Scalars['Json'] | null) 
}
export interface OfferActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface OfferDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface OfferDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface OfferPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface OfferShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface OfferTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface OrderDateFormattedArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface OrderDateInsertArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface OrderDatePayedArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface OrderDateTimeFormattedArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface OrderDateUpdateArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface OrderDeliveryDatetimeArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface PageActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface PageDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface PageDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface PagePreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface PageShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface PageTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface PaymentDatePaidArgs { 
    format?: (Scalars['String'] | null), formatType?: (DateFormatEnum | null) 
}
export interface ProductActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ProductPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ProductShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductFlagActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductFlagDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductFlagDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ProductFlagPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ProductFlagShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductFlagTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductSectionDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductSectionSearchableContentArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ProductSectionTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductTagActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductTagDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductTagDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ProductTagPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ProductTagShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ProductTagTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface QueryCameraElementListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCatalogProductAllArgs { 
    filter?: (ProductFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCatalogProductFavListArgs { 
    filter?: (ProductFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCatalogProductFavList2Args { 
    type?: (Scalars['String'] | null) 
}
export interface QueryCatalogProductListArgs { 
    filter?: (ProductFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCatalogProductRecordsetArgs { 
    filter?: (ProductFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCatalogProductSearchArgs { 
    elementDetail?: (Scalars['Boolean'] | null), query?: (Scalars['String'] | null) 
}
export interface QueryCatalogProductSearchNewArgs { 
    elementDetail?: (Scalars['Boolean'] | null), filterSuggestion?: (Scalars['Json'] | null), query?: (Scalars['String'] | null) 
}
export interface QueryCatalogProductSectionListArgs { 
    filter?: (SectionFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCatalogProductSingleArgs { 
    filter?: (ProductFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCompanyOfficeListArgs { 
    filter?: (OfficeFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCompanyOfficeRecordsetArgs { 
    filter?: (OfficeFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCompanyOfficeSingleArgs { 
    filter?: (OfficeFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCompanyVacancyListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCompanyVacancyRecordsetArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryCompanyVacancySingleArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QueryFaqElementListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryFaqElementRecordsetArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryFaqElementSingleArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QueryGeoGeocoderLocationByAddressArgs { 
    address?: (Scalars['String'] | null) 
}
export interface QueryGeoGeocoderLocationsByCoordsArgs { 
    lat?: (Scalars['Float'] | null), lon?: (Scalars['Float'] | null) 
}
export interface QueryOfferListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryOfferRecordsetArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryOfferSingleArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QueryPageListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryPageRecordsetArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QueryPageRouteArgs { 
    URL?: (Scalars['String'] | null) 
}
export interface QueryPageSingleArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QueryReviewListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleBonusLevelListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleClientCardApplyByPhoneArgs { 
    phone?: (Scalars['String'] | null) 
}
export interface QuerySaleClientCardFetchArgs { 
    isScope?: (Scalars['Boolean'] | null), refetch?: (Scalars['Boolean'] | null) 
}
export interface QuerySaleClientCardListArgs { 
    filter?: (ClientCardFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleClientCardRecordsetArgs { 
    filter?: (ClientCardFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleClientCardSingleArgs { 
    filter?: (ClientCardFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleOrderActiveListArgs { 
    filter?: (OrderFilter | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleOrderEnsurePaymentArgs { 
    hash?: (Scalars['String'] | null), id?: (Scalars['Int'] | null), paymentId?: (Scalars['Int'] | null) 
}
export interface QuerySaleProfileListArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleProfileRecordsetArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), nocache?: (Scalars['Boolean'] | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleProfileSingleArgs { 
    filter?: (ElementListFilterInput | null), id?: (Scalars['Int'] | null), nav?: (QueryNavInput | null), where?: (Scalars['Json'] | null) 
}
export interface QuerySaleVorderCurrentArgs { 
    check?: (Scalars['Boolean'] | null) 
}
export interface QuerySearchSuggestionsArgs { 
    query?: (Scalars['String'] | null) 
}
export interface QueryUserFetchArgs { 
    sessionWrite?: (Scalars['Boolean'] | null) 
}
export interface ReviewActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ReviewDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ReviewDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ReviewPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface ReviewShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface ReviewTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface SectionDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface SectionSearchableContentArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface SectionTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}
export interface VorderBasketArgs { 
    recalc?: (Scalars['Boolean'] | null) 
}
export interface VorderCurrentBasketArgs { 
    recalc?: (Scalars['Boolean'] | null) 
}
export interface WebcamActiveFromArgs { 
    format?: (Scalars['String'] | null) 
}
export interface WebcamDateCreateArgs { 
    format?: (Scalars['String'] | null) 
}
export interface WebcamDetailTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface WebcamPreviewTextArgs { 
    format?: (Scalars['Boolean'] | null) 
}
export interface WebcamShowCounterStartArgs { 
    format?: (Scalars['String'] | null) 
}
export interface WebcamTimestampXArgs { 
    format?: (Scalars['String'] | null) 
}