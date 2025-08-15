const AccessError = {
  "authRedirect": true,
  "data": true,
  "fieldMessage": true,
  "message": true,
  "messages": true,
  "name": {},
  "rel": true,
  "type": {}
}
const ActionMobile = {
  "addBacklink": true,
  "addSession": true,
  "await": true,
  "code": true,
  "replace": true,
  "title": true,
  "titleAuto": true,
  "url": true
}
const ActionWeb = {
  "code": true,
  "title": true,
  "url": true
}
const AppClient = {
  "CLIENT_ID": true,
  "CURRENT_SESSION_ID": true,
  "DEBUG_PARAMS": {
    "__fragment": "AppClientDebugParams"
  },
  "ID": true,
  "MOBILE_PUSH_TOKEN": true,
  "SESSION_ID": true,
  "TOKEN": true,
  "USER_ID": true,
  "WEB_PUSH_TOKEN": true
}
const AppClientDebugParams = {
  "eventsTransport": true
}
const AppProductRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "Product"
  }
}
const Basket = {
  "BASE_PRICE": true,
  "CLIENT_CHANGED_AT": true,
  "COUNT": true,
  "GIFTS": true,
  "HASH": true,
  "ITEMS": {
    "__fragment": "BasketItem"
  },
  "MIN_PRICE": true,
  "MIN_PRICE_REACHED": true,
  "OFFERS": {
    "__fragment": "SpecialOffer"
  },
  "PRICE": true,
  "QUANTITY": true,
  "SYNCED": true,
  "TS": true,
  "WEIGHT": true
}
const BasketBuildItem = {
  "ELEMENT": {
    "__fragment": "Product"
  },
  "PRODUCT_ID": true,
  "QUANTITY": true
}
const BasketItem = {
  "BASE_PRICE": true,
  "BENEFIT": {},
  "BUILD": {
    "__fragment": "BasketBuildItem"
  },
  "CLIENT_CHANGED_AT": true,
  "CLIENT_ID": true,
  "COMMENT": true,
  "DESC": true,
  "DISABLE": true,
  "DISABLE_REASON": true,
  "DISCOUNTS": {
    "__fragment": "BasketItemDiscount"
  },
  "ELEMENT": {
    "__fragment": "Product"
  },
  "FINAL_PRICE": true,
  "FINAL_PRICE_BASE": true,
  "ID": true,
  "INPUT_PROPS_HASH": true,
  "MEASURE_NAME": true,
  "NAME": true,
  "ORDER_ID": true,
  "PAID": true,
  "PARENT_ID": true,
  "PRICE": true,
  "PRICE_BASE": true,
  "PRODUCT_ID": true,
  "PROPS": {
    "__fragment": "BasketItemProp"
  },
  "QUANTITY": true,
  "UUID": true
}
const BasketItemDiscount = {
  "AMOUNT": true,
  "BASE_PRICE": true,
  "DISCOUNTED_PRICE": true,
  "RULE": true,
  "TARGET": {},
  "TYPE": {}
}
const BasketItemProp = {
  "CODE": true,
  "NAME": true,
  "VALUE": true,
  "XML_ID": true
}
const BasketRule = {
  "ACTIONS": {},
  "ALL_STOP": true,
  "CAPTION": true,
  "CHILDREN": {
    "__fragment": "BasketRule"
  },
  "CODE": true,
  "CONDITIONS": {
    "__fragment": "BasketRuleCondition"
  },
  "HOTEST": true,
  "ID": true,
  "LEVEL_STOP": true,
  "NAME": true,
  "NAME_TEMPLATE": true,
  "PARENT": true,
  "PERCENT": true,
  "TYPE": {}
}
const BasketRuleActionDiscount = {
  "AMOUNT": true,
  "AMOUNT_SURCHARGE": true,
  "ID": true,
  "MODE": {},
  "NAME": true,
  "PRODUCT_IDS": true,
  "SECTION_IDS": true,
  "TARGET": {},
  "TYPE": {}
}
const BasketRuleCondition = {
  "CHILDREN": {
    "__fragment": "BasketRuleCondition"
  },
  "CODE": true,
  "ID": true,
  "IN": true,
  "MAX": true,
  "MIN": true,
  "NAME": true,
  "NOT": true,
  "TYPE": {},
  "VALUE": true
}
const BasketRulesResulBenefitProduct = {
  "PRODUCT_ID": true,
  "QUANTITY": true,
  "TYPE": {}
}
const BasketRulesResult = {
  "ALLOW": true,
  "BENEFIT_PRODUCTS": {
    "__fragment": "BasketRulesResulBenefitProduct"
  },
  "DENY": true,
  "DISCOUNTS_BASKET": {
    "__fragment": "BasketRuleActionDiscount"
  },
  "DISCOUNTS_DELIVERY": {
    "__fragment": "BasketRuleActionDiscount"
  },
  "DISCOUNTS_PRODUCT": {
    "__fragment": "BasketRuleActionDiscount"
  },
  "DISCOUNTS_SECTION": {
    "__fragment": "BasketRuleActionDiscount"
  },
  "DISCOUNTS_TOTAL": {
    "__fragment": "BasketRuleActionDiscount"
  }
}
const BonusLevel = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "PROPERTIES": {
    "__fragment": "BonusLevelProps"
  },
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const BonusLevelProps = {
  "ACCUMULATION_PERCENT": true,
  "COLOR": true,
  "MAX_USE_PERCENT": true,
  "MONTH_SPENT_MAX": true,
  "MONTH_SPENT_MIN": true,
  "ORDERS_SUMM": true
}
const ClientCardRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "SaleClientCard"
  }
}
const ClientEmit = {
  "body": true,
  "cls": true,
  "createdAt": true,
  "eventData": true,
  "eventGroup": true,
  "eventName": true,
  "id": true,
  "message": true,
  "targetClientId": true,
  "targetUserId": true,
  "title": true
}
const ClientNotice = {
  "actionItems": {
    "__fragment": "MenuItem"
  },
  "body": true,
  "bodyHtml": true,
  "cls": true,
  "createdAt": true,
  "eventData": true,
  "eventGroup": true,
  "eventName": true,
  "haveBody": true,
  "id": true,
  "image": true,
  "isReaded": true,
  "message": true,
  "offerId": true,
  "showAs": true,
  "targetClientId": true,
  "targetCode": true,
  "targetUserId": true,
  "title": true
}
const Command = {
  "code": true,
  "confirm": true,
  "params": true,
  "path": true,
  "type": true
}
const CommonError = {
  "data": true,
  "fieldMessage": true,
  "message": true,
  "messages": true,
  "name": {},
  "rel": true,
  "type": {}
}
const CompanyOffice = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "PROPERTIES": {
    "__fragment": "CompanyOfficeProps"
  },
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const CompanyOfficeProps = {
  "ADDRESS": true,
  "COORDINATES": {
    "__fragment": "Coordinates"
  },
  "EMAIL": true,
  "PHONES": true,
  "REQUISITES": {
    "__fragment": "ElementPropValueHtml"
  },
  "ROLES": {
    "__fragment": "ListValue"
  },
  "SERVICE_ID": true,
  "WORKTIME": true
}
const CompanyVacancy = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const Condition = {
  "eq": true,
  "gt": true,
  "lt": true,
  "path": true
}
const ConstructorBuild = {
  "CONSTRUCTOR_CODE": true,
  "CONSTRUCTOR_URL": true,
  "SOSTAV": {
    "__fragment": "ConstructorBuildItem"
  }
}
const ConstructorBuildItem = {
  "ELEMENT": {
    "__fragment": "ConstructorElement"
  },
  "ELEMENT_ID": true,
  "QUANTITY": true
}
const ConstructorElement = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "BENEFITS": {
    "__fragment": "ProductBenefit"
  },
  "BUILD": {
    "__fragment": "ConstructorBuild"
  },
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "FLAGS": {
    "__fragment": "ProductFlag"
  },
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "IS_SALE_SPECIAL": true,
  "MEASURE": {
    "__fragment": "ProductMeasure"
  },
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "OFFERS": {
    "__fragment": "Product"
  },
  "OFFER_PARENT_ELEMENT": {
    "__fragment": "Product"
  },
  "PARENT": {
    "__fragment": "Product"
  },
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "PRICE": {
    "__fragment": "ProductPrice"
  },
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SALES_COUNT": true,
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SET_ITEMS": {
    "__fragment": "ProductSetItem"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": {
    "__fragment": "ProductTag"
  },
  "TIMESTAMP_X": true,
  "URL": true,
  "WEIGHT": true,
  "XML_ID": true
}
const Coordinates = {
  "LAT": true,
  "LON": true
}
const Coupon = {
  "COUPON": true,
  "ID": true,
  "NAME": true,
  "PRODUCT": {
    "__fragment": "Product"
  },
  "PRODUCT_ID": true
}
const CourierState = {
  "ARRIVAL_TIME": true,
  "ARRIVAL_TIME_CAPTION": true,
  "CAR_COLOR": true,
  "CAR_NUMBER": true,
  "COORDS": true
}
const DeliveryCalculate = {
  "ADDRESS_COORDS": true,
  "DELIVERY_ADDRESS": true,
  "DELIVERY_HASH": true,
  "ID": true,
  "NEED_TIME": true,
  "NEED_TIME_FORMATTED": true,
  "ORDER_ID": true,
  "ORDER_PRICE": true,
  "PHONE": true,
  "REQUEST_DELTA": true,
  "REQUEST_TIME": true,
  "REQUEST_TIME_FORMATTED": true,
  "RES_DELIVER_PRICE": true,
  "RES_OFFICE_1C_ID": true,
  "RES_OFFICE_ID": true,
  "RES_STATUS": {},
  "RES_TIME": true,
  "RES_TIME_FORMATTED": true,
  "TIME_MODE": {},
  "TRANSPORT_TYPE": true,
  "VORDER_ID": true
}
const DeliveryComputed = {
  "CALCULATE_DESCRIPTION": true,
  "CALCULATE_ERRORS": true,
  "CALC_TIMESTAMP": true,
  "DELIVERY_DISCOUNT_PRICE": true,
  "DELIVERY_DISCOUNT_PRICE_FORMATED": true,
  "ID": true,
  "NAME": true,
  "PERIOD_TEXT": true,
  "PRICE": true,
  "PRICE_FORMATED": true,
  "SERVICE": {
    "__fragment": "DeliveryService"
  }
}
const DeliveryService = {
  "ID": true,
  "NAME": true,
  "PARENT_ID": true,
  "TRANSPORT_TYPE": {}
}
const DeliveryZone = {
  "ACTIVE": true,
  "CODE": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "ID": true,
  "NAME": true,
  "PREVIEW_TEXT": true,
  "SORT": true
}
const Discount = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "PROPERTIES": {
    "__fragment": "DiscountProps"
  },
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const DiscountProps = {
  "ACTION_DISCOUNT_PERCENT": true,
  "ACTION_PRODUCT_IDS": true,
  "ACTION_PRODUCT_IDS_ENTITIES": {
    "__fragment": "Element"
  },
  "ACTION_SECTION_IDS": true,
  "CONDITION": {
    "__fragment": "DiscountPropsCONDITION"
  },
  "CONDITION_ORDER_PRICE_FROM": true,
  "CONDITION_TRANSPORT_TYPE": true,
  "DATE": true,
  "HOTEST": true,
  "NAME_TEMPLATE": true
}
const DiscountPropsCONDITION = {
  "DATE": true,
  "ORDER_PRICE_FROM": true,
  "TRANSPORT_TYPE": true
}
const Element = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const ElementMeta = {
  "DESCRIPTION": true,
  "KEYWORDS": true,
  "PAGE_TITLE": true,
  "TITLE": true
}
const ElementPropValueHtml = {
  "TEXT": true,
  "TYPE": true
}
const ElementPropValueWithDesc = {
  "DESC": true,
  "VALUE": true
}
const EntityInfo = {
  "name": true,
  "role": true,
  "sectionPaths": true,
  "type": true,
  "urls": {
    "__fragment": "EntityInfoUrls"
  }
}
const EntityInfoUrls = {
  "index": true,
  "section": true,
  "view": true
}
const EntityProp = {
  "CODE": true,
  "DESC": true,
  "FILE": {
    "__fragment": "Image"
  },
  "FILES": {
    "__fragment": "Image"
  },
  "ID": true,
  "MUL": true,
  "NAME": true,
  "TYPE": true,
  "VAL": true,
  "VAL_ENUM_ID": true,
  "VAL_ID": true
}
const ExternalServiceError = {
  "authRedirect": true,
  "data": true,
  "fieldMessage": true,
  "message": true,
  "messages": true,
  "name": {},
  "rel": true,
  "type": {}
}
const FaqQuestion = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const FaqQuestionRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "FaqQuestion"
  }
}
const FormError = {
  "data": true,
  "fieldLabel": true,
  "fieldMessage": true,
  "fieldName": true,
  "message": true,
  "messages": true,
  "name": {},
  "rel": true,
  "type": {}
}
const GeoObject = {
  "address_full": true,
  "address_original": true,
  "address_short": true,
  "area": true,
  "area_fias_id": true,
  "area_format": true,
  "area_original": true,
  "city": true,
  "city_fias_id": true,
  "city_format": true,
  "city_original": true,
  "district": true,
  "district_fias_id": true,
  "district_format": true,
  "district_original": true,
  "geo_lat": true,
  "geo_lon": true,
  "house": true,
  "house_fias_id": true,
  "house_format": true,
  "house_original": true,
  "region": true,
  "region_fias_id": true,
  "region_format": true,
  "region_original": true,
  "street": true,
  "street_fias_id": true,
  "street_format": true,
  "street_original": true,
  "street_path_full": true,
  "street_path_short": true
}
const Image = {
  "FILE_SIZE": true,
  "ID": true,
  "ORIGINAL_NAME": true,
  "SRC": true,
  "SRC_DEFAULT": true
}
const ListValue = {
  "CODE": true,
  "ID": true,
  "VALUE": true
}
const Menu = {
  "children": {
    "__fragment": "MenuItem"
  },
  "code": true,
  "id": true
}
const MenuItem = {
  "badge": true,
  "bgColor": true,
  "blank": true,
  "children": {
    "__fragment": "MenuItem"
  },
  "color": true,
  "command": {
    "__fragment": "Command"
  },
  "dense": true,
  "disable": true,
  "display": true,
  "entityId": true,
  "entityType": true,
  "flat": true,
  "icon": true,
  "id": true,
  "image": {
    "__fragment": "Image"
  },
  "imageId": true,
  "infoLabel": true,
  "label": true,
  "loading": true,
  "native": true,
  "onClick": true,
  "outline": true,
  "params": true,
  "parent": true,
  "roles": true,
  "textColor": true,
  "url": true,
  "width": true
}
const MenuItemMobile = {
  "action": {
    "__fragment": "ActionMobile"
  },
  "backgroundColor": true,
  "badge": true,
  "color": true,
  "condition": {
    "__fragment": "Condition"
  },
  "icon": true,
  "id": true,
  "image": {
    "__fragment": "Image"
  },
  "imageId": true,
  "label": true,
  "labelColor": true,
  "link": true,
  "outline": true,
  "outlineColor": true,
  "outlineWidth": true,
  "params": true,
  "parent": true,
  "roles": true,
  "templatable": true,
  "templatableProps": true
}
const Message = {
  "actions": {
    "__fragment": "MenuItem"
  },
  "category": true,
  "code": true,
  "data": true,
  "duration": true,
  "id": true,
  "message": true,
  "messages": true,
  "name": true,
  "notify": true,
  "rel": true,
  "status": true,
  "title": true,
  "type": {}
}
const NoticePubSyncReadedResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "ClientNotice"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const Offer = {
  "ACTIONS_MOBILE": {
    "__fragment": "MenuItemMobile"
  },
  "ACTIONS_WEB": {
    "__fragment": "MenuItem"
  },
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "BANNER_HOR_DESKTOP": {
    "__fragment": "Image"
  },
  "BANNER_HOR_MOBILE": {
    "__fragment": "Image"
  },
  "BANNER_INTERNAL_TEXT": true,
  "BANNER_SQUARE": {
    "__fragment": "Image"
  },
  "CODE": true,
  "CONTENT_IMAGE": {
    "__fragment": "Image"
  },
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "DISCOUNT_ID": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "IS_HOT": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "OFFER_NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SLIDES": {
    "__fragment": "OfferSlide"
  },
  "SORT": true,
  "STARTUP_SHOW": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "VARS": true,
  "VID": true,
  "VIEW_MODE": true,
  "XML_ID": true
}
const OfferRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "Offer"
  }
}
const OfferSlide = {
  "BG_COLOR": true,
  "CODE": true,
  "CONTENT_HTML": true,
  "CONTENT_IMAGE": {
    "__fragment": "Image"
  },
  "CONTENT_TYPE": true,
  "ID": true,
  "NAME": true
}
const OfficeRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "CompanyOffice"
  }
}
const Order = {
  "ACCESS_HASH": true,
  "ACCOUNT_NUMBER": true,
  "ACTIONS": true,
  "ADDRESS_FOR_1C": true,
  "ATTR": {
    "__fragment": "OrderAttributesValue"
  },
  "ATTRS": {
    "__fragment": "OrderAttribute"
  },
  "BASKET": {
    "__fragment": "BasketItem"
  },
  "BONUSES": true,
  "BUYER_NAME": true,
  "CANCEL_REASONS": {
    "__fragment": "OrderCancelReason"
  },
  "CONTRACT_NUM": true,
  "COUPONS": {
    "__fragment": "Coupon"
  },
  "COURIER_STATE": {
    "__fragment": "CourierState"
  },
  "CSTATUS_COLOR": true,
  "CSTATUS_ID": true,
  "CSTATUS_NAME": true,
  "DATE_FORMATTED": true,
  "DATE_INSERT": true,
  "DATE_PAYED": true,
  "DATE_TIME_FORMATTED": true,
  "DATE_UPDATE": true,
  "DELIVERY": {
    "__fragment": "DeliveryService"
  },
  "DELIVERY_ADDRESS_FULL": true,
  "DELIVERY_CALCULATED": true,
  "DELIVERY_DATETIME": true,
  "DELIVERY_DEPARTMENT": {
    "__fragment": "CompanyOffice"
  },
  "DELIVERY_FREE_FROM_PRICE": true,
  "DELIVERY_ID": true,
  "DISCOUNT_PERCENT": true,
  "DISCOUNT_REASON": true,
  "EDU_GROUP_NUM": true,
  "ID": true,
  "IS_ACTIVE": true,
  "IS_CANCELED": true,
  "IS_CAN_CANCEL": true,
  "IS_CAN_PAY": true,
  "IS_CAN_PAY_BILL": true,
  "IS_CAN_PAY_ONLINE": true,
  "IS_FINISHED": true,
  "IS_PAID": true,
  "PAYMENTS": {
    "__fragment": "Payment"
  },
  "PAYSYSTEM": {
    "__fragment": "Paysystem"
  },
  "PAYSYSTEM_ID": true,
  "PAYSYSTEM_IS_ONLINE": true,
  "PAY_LINK": true,
  "PERSON_TYPE_ID": true,
  "PICKUP_DEPARTMENT": {
    "__fragment": "CompanyOffice"
  },
  "PRICE": true,
  "PRICE_BASKET": true,
  "PRICE_BASKET_BASE": true,
  "PRICE_DELIVERY": true,
  "PRICE_DELIVERY_BASE": true,
  "PRICE_DISCOUNT": true,
  "PRICE_PAY": true,
  "PRICE_PAY_BASE": true,
  "PRICE_TOTAL": true,
  "PRICE_TOTAL_BASE": true,
  "SCOPE": {
    "__fragment": "OrderScope"
  },
  "SCOPE_ENTITY": {
    "__fragment": "Command"
  },
  "SECRET_URL": true,
  "SERVICE_ID": true,
  "STATUS": {
    "__fragment": "OrderStatus"
  },
  "STATUS_COLOR": true,
  "STATUS_ID": true,
  "STATUS_NAME": true,
  "STUDENT_FIO": true,
  "SYNCED": true,
  "TS": true,
  "URL": true,
  "USER": {
    "__fragment": "User"
  },
  "USER_DESCRIPTION": true,
  "USER_ID": true
}
const OrderAttribute = {
  "CODE": {},
  "DEFAULT_VALUE": true,
  "KIND": {},
  "NAME": true,
  "OPTIONS": {
    "__fragment": "OrderAttributeOption"
  },
  "TYPE": {},
  "VALUE": true,
  "VALUE_VIEW": true
}
const OrderAttributeOption = {
  "DESCRIPTION": true,
  "DISABLE": true,
  "ICON": true,
  "ID": true,
  "NAME": true,
  "NAME_SHORT": true,
  "SORT": true,
  "VALUE": true,
  "XML_ID": true
}
const OrderAttributesValue = {
  "ADDRESS": true,
  "ADDRESS_IS_CUSTOM": true,
  "ADDRESS_IS_CUSTOM_STRING": true,
  "ADDRESS_SOURCE": true,
  "APP_VERSION": true,
  "BENEFIT_TYPE": true,
  "BENEFIT_TYPE_STRING": true,
  "BONUSES": true,
  "CASH_SUM": true,
  "CITY": true,
  "CITY_FIAS_ID": true,
  "COMMENT": true,
  "DATA": {
    "__fragment": "OrderData"
  },
  "DATE": true,
  "DATETIME": true,
  "DELIVERY_DEPARTMENT": true,
  "DELIVERY_FREE_FROM_PRICE": true,
  "DELIVERY_FREE_UPDATED_TIME": true,
  "DELIVERY_ID": true,
  "DELIVERY_ID_STRING": true,
  "DELIVERY_PRICE": true,
  "DEPARTMENT_SERVICE_ID": true,
  "DETAILS": true,
  "DETAILS_STRING": true,
  "DISCOUNT_PERCENT": true,
  "DISCOUNT_REASON": true,
  "EMAIL": true,
  "ENTRANCE": true,
  "FIO": true,
  "FLAT": true,
  "FLOOR": true,
  "GIFTS_LIST": true,
  "HOUSE": true,
  "HOUSE_COORDS": {
    "__fragment": "Coordinates"
  },
  "HOUSE_COORDS_STRING": true,
  "HOUSE_FIAS_ID": true,
  "INTERCOM": true,
  "LIFT": true,
  "LIFT_STRING": true,
  "LOCATION": true,
  "NEED_CONFIRM": true,
  "NEED_CONFIRM_STRING": true,
  "PAYMENT_TYPE": {},
  "PAYMENT_TYPE_STRING": true,
  "PAY_SYSTEM_ID": true,
  "PAY_SYSTEM_ID_STRING": true,
  "PERSONS_NUMBER": true,
  "PHONE": true,
  "PICKUP_DEPARTMENT": true,
  "PRIVATE_HOUSE": true,
  "PRIVATE_HOUSE_STRING": true,
  "PROFILE_COMMENT": true,
  "PROFILE_DEFAULT": true,
  "PROFILE_DEFAULT_STRING": true,
  "PROFILE_ID": true,
  "PROMOCODE": true,
  "RECEIVER_ANOTHER": true,
  "RECEIVER_ANOTHER_STRING": true,
  "RECEIVER_NAME": true,
  "RECEIVER_PHONE": true,
  "RESERVE_AVAILABLE_TIME": true,
  "RESERVE_NEED_TIME": true,
  "RESERVE_REQUEST_TIME": true,
  "RESERVE_STATUS": true,
  "RESERVE_SUCCESS_HASH": true,
  "RESERVE_SUCCESS_REQUEST_TIME": true,
  "ROISTAT": true,
  "SERVICE_SEND": true,
  "SERVICE_SEND_START": true,
  "SERVICE_SEND_STRING": true,
  "SETTLEMENT": true,
  "SETTLEMENT_FIAS_ID": true,
  "SOURCE": true,
  "STREET": true,
  "STREET_COORDS": {
    "__fragment": "Coordinates"
  },
  "STREET_COORDS_STRING": true,
  "STREET_FIAS_ID": true,
  "STREET_PATH": true,
  "STRUCT": true,
  "TEST_TIME": true,
  "TEST_TIME_STRING": true,
  "TIME": true,
  "TIME_MODE": {},
  "TIME_MODE_STRING": true,
  "TRANSPORT_TYPE": {},
  "USER_DESCRIPTION": true,
  "UUID": true,
  "VORDER_ID": true,
  "WITH_OPERATOR": true,
  "WITH_OPERATOR_STRING": true
}
const OrderCancelReason = {
  "CODE": true,
  "NAME": true
}
const OrderData = {
  "paramArray": true,
  "paramInt": true,
  "paramString": true
}
const OrderProfile = {
  "ATTR": {
    "__fragment": "OrderProfileAttributesValue"
  },
  "ATTRS": {
    "__fragment": "OrderAttribute"
  },
  "CAPTION": true,
  "COMPANY_ID": true,
  "COORDS": {
    "__fragment": "Coordinates"
  },
  "DELIVERY_FREE_FROM_PRICE": true,
  "ID": true,
  "IS_DEFAULT": true,
  "NAME": true,
  "PERSON_TYPE": {
    "__fragment": "PersonType"
  },
  "PERSON_TYPE_ID": true,
  "USER": {
    "__fragment": "User"
  },
  "USER_ID": true
}
const OrderProfileAttributesValue = {
  "ADDRESS": true,
  "ADDRESS_IS_CUSTOM": true,
  "ADDRESS_SOURCE": true,
  "CITY": true,
  "CITY_FIAS_ID": true,
  "ENTRANCE": true,
  "FLAT": true,
  "FLOOR": true,
  "HOUSE": true,
  "HOUSE_COORDS": {
    "__fragment": "Coordinates"
  },
  "HOUSE_FIAS_ID": true,
  "LIFT": true,
  "PRIVATE_HOUSE": true,
  "PROFILE_COMMENT": true,
  "PROFILE_DEFAULT": true,
  "RECEIVER_ANOTHER": true,
  "RECEIVER_NAME": true,
  "RECEIVER_PHONE": true,
  "SETTLEMENT": true,
  "SETTLEMENT_FIAS_ID": true,
  "STREET": true,
  "STREET_COORDS": {
    "__fragment": "Coordinates"
  },
  "STREET_FIAS_ID": true,
  "STREET_PATH": true,
  "STRUCT": true,
  "TEST_TIME": true
}
const OrderProfileRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "OrderProfile"
  }
}
const OrderScope = {
  "CONTRACT_NUM": true,
  "ENTITY_ID": true,
  "ENTITY_TYPE": true
}
const OrderStatus = {
  "COLOR": true,
  "ID": true,
  "NAME": true,
  "SORT": true,
  "TYPE": true
}
const OtpError = {
  "data": true,
  "fieldMessage": true,
  "message": true,
  "messages": true,
  "name": {},
  "param": true,
  "rel": true,
  "type": {}
}
const Page = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CONTENT_CHUNKS": {
    "__fragment": "PageContentChunk"
  },
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATA_CHUNKS": {
    "__fragment": "PageDataChunk"
  },
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const PageContentChunk = {
  "CODE": true,
  "GROUP": true,
  "NAME": true,
  "TYPE": true,
  "VALUE": true
}
const PageDataChunk = {
  "CODE": true,
  "TYPE": true,
  "VALUE": true
}
const PageRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "Page"
  }
}
const Payment = {
  "DATE_PAID": true,
  "ID": true,
  "IS_PAID": true,
  "ORDER_ID": true,
  "ORDER_URL": true,
  "PAYSYSTEM": {
    "__fragment": "Paysystem"
  },
  "PAYSYSTEM_ID": true,
  "PAY_NAV": true,
  "PS_INVOICE_ID": true,
  "PS_STATUS": true,
  "PS_STATUS_CODE": true,
  "PS_STATUS_ID": true,
  "PS_STATUS_NAME": true,
  "SUM": true,
  "SUM_PAID": true
}
const PaymentType = {
  "CODE": true,
  "ICON": true,
  "NAME": true
}
const Paysystem = {
  "CODE": true,
  "DESCRIPTION": true,
  "ID": true,
  "IS_BILL": true,
  "IS_INNER": true,
  "IS_ONLINE": true,
  "IS_ONLINE_DELAYED": true,
  "NAME": true
}
const PersonType = {
  "CODE": true,
  "ID": true,
  "IS_COMPANY": true,
  "NAME": true,
  "RESTRICTED": true,
  "SORT": true
}
const Product = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "BENEFITS": {
    "__fragment": "ProductBenefit"
  },
  "BUILD": {
    "__fragment": "ConstructorBuild"
  },
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "FLAGS": {
    "__fragment": "ProductFlag"
  },
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "IMAGE": {
    "__fragment": "Image"
  },
  "IS_SALE_SPECIAL": true,
  "MEASURE": {
    "__fragment": "ProductMeasure"
  },
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "OFFERS": {
    "__fragment": "Product"
  },
  "OFFER_PARENT_ELEMENT": {
    "__fragment": "Product"
  },
  "PARENT": {
    "__fragment": "Product"
  },
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "PRICE": {
    "__fragment": "ProductPrice"
  },
  "PROPERTIES": {
    "__fragment": "ProductProps"
  },
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SALES_COUNT": true,
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SET_ITEMS": {
    "__fragment": "ProductSetItem"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": {
    "__fragment": "ProductTag"
  },
  "TIMESTAMP_X": true,
  "URL": true,
  "WEIGHT": true,
  "XML_ID": true
}
const ProductBenefit = {
  "IS_GIFT": true,
  "PRODUCT": {
    "__fragment": "Product"
  },
  "PRODUCT_ID": true,
  "QUANTITY": true
}
const ProductFlag = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const ProductMeasure = {
  "NAME": true,
  "RATIO": true
}
const ProductPrice = {
  "DISCOUNTED": true,
  "DISCOUNT_PERCENT": true,
  "PRICE": true
}
const ProductProps = {
  "ADDITIVES": true,
  "ADDITIVES_ENTITIES": {
    "__fragment": "Element"
  },
  "AVAILABLE_SCHEDULE": {
    "__fragment": "ProductPropsAVAILABLE_SCHEDULE"
  },
  "BELKI": true,
  "BENEFITS": {
    "__fragment": "ProductPropsBENEFITS"
  },
  "COMPONENT_IS": {
    "__fragment": "ListValue"
  },
  "FLAG_ITEMS": true,
  "FLAG_ITEMS_ENTITIES": {
    "__fragment": "Element"
  },
  "HOLIDAY": {
    "__fragment": "ListValue"
  },
  "IS_HIT": true,
  "KKAL": true,
  "NEW": {
    "__fragment": "ListValue"
  },
  "PHOTOV2": {
    "__fragment": "Image"
  },
  "ROLLS": true,
  "ROLLS_ENTITIES": {
    "__fragment": "Element"
  },
  "SALE_SPECIAL": true,
  "SERVICE_ID": true,
  "SET_ITEMS": {
    "__fragment": "ProductPropsSET_ITEMS"
  },
  "SOSTAV": {
    "__fragment": "ElementPropValueWithDesc"
  },
  "TAGS": true,
  "TAGS_ENTITIES": {
    "__fragment": "Element"
  },
  "UGLEVODY": true,
  "UPSALE_ELEMENTS": true,
  "UPSALE_ELEMENTS_ENTITY": {
    "__fragment": "Element"
  },
  "UPSALE_SECTIONS": true,
  "WEIGHT": true,
  "ZHIRY": true
}
const ProductPropsAVAILABLE_SCHEDULE = {
  "DAY": {
    "__fragment": "ListValue"
  },
  "FROM": true,
  "TO": true
}
const ProductPropsBENEFITS = {
  "IS_GIFT": true,
  "PRODUCT": true,
  "PRODUCT_ENTITY": {
    "__fragment": "Element"
  },
  "QUANTITY": true
}
const ProductPropsSET_ITEMS = {
  "PRODUCT_ID": true,
  "PRODUCT_ID_ENTITY": {
    "__fragment": "Element"
  },
  "QUANTITY": true
}
const ProductSection = {
  "ACTIVE": true,
  "CHILDREN": {
    "__fragment": "Section"
  },
  "CODE": true,
  "DATE_CREATE": true,
  "DEPTH_LEVEL": true,
  "DESCRIPTION": true,
  "DESCRIPTION_TYPE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "ELEMENT_CNT": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_EXTERNAL_ID": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_TYPE_ID": true,
  "ID": true,
  "LEFT_MARGIN": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "NAME": true,
  "PARENT": {
    "__fragment": "ProductSection"
  },
  "PARENTS": {
    "__fragment": "ProductSection"
  },
  "PICTURE": {
    "__fragment": "Image"
  },
  "PROPERTIES": {
    "__fragment": "ProductSectionProps"
  },
  "REPLACE_LINK": true,
  "RIGHT_MARGIN": true,
  "SEARCHABLE_CONTENT": true,
  "SECTION_PAGE_URL": true,
  "SORT": true,
  "TIMESTAMP_X": true
}
const ProductSectionProps = {
  "CATEGORY_CLASS": true,
  "DATA": true,
  "DATA_FORMATTED": true,
  "DAY": true,
  "ENUM": {},
  "FLOAT": true,
  "HINT": true,
  "HTML": true,
  "INT": true,
  "LIST": {
    "__fragment": "ListValue"
  },
  "LOGIC": true,
  "MENU_BG_COLOR": true,
  "MENU_COLOR": true,
  "MENU_FOCUSED_ICON": {
    "__fragment": "Image"
  },
  "MENU_ICON": {
    "__fragment": "Image"
  },
  "M_CARD": true,
  "NAV_TITLE": true,
  "NAV_URL": true,
  "REPLACE_LINK": true,
  "SEARCH_IN": true,
  "UPSALE_SECTIONS": true,
  "UPSALE_SECTIONS_ENTITY": {
    "__fragment": "Section"
  }
}
const ProductSetItem = {
  "PRODUCT": {
    "__fragment": "Product"
  },
  "PRODUCT_ID": true,
  "QUANTITY": true
}
const ProductTag = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const QueryInfo = {
  "limit": true,
  "nextPage": true,
  "page": true,
  "pages": true,
  "total": true
}
const RateError = {
  "data": true,
  "fieldMessage": true,
  "message": true,
  "messages": true,
  "name": {},
  "rel": true,
  "ttl": true,
  "type": {}
}
const Response = {
  "error": {},
  "errors": {},
  "payload": true,
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const ResponseRate = {
  "limited": true,
  "ttl": true
}
const ResponseRedirect = {
  "restricted": true,
  "ttl": true
}
const ResponseState = {
  "events": true,
  "messages": {
    "__fragment": "Message"
  },
  "rate": {
    "__fragment": "ResponseRate"
  },
  "redirect": {
    "__fragment": "ResponseRedirect"
  }
}
const Review = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CHILDREN": {
    "__fragment": "Review"
  },
  "CODE": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "ELEMENT": {
    "__fragment": "Element"
  },
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "ORDER": {
    "__fragment": "Order"
  },
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const ReviewOrderGuestReviewResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "ReviewOrderGuestReviewResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const ReviewOrderGuestReviewResultPayload = {
  "departmentId": true,
  "departmentName": true,
  "redirectUrl": true
}
const ReviewServiceReviewResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "Review"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleClientCard = {
  "BONUSES": true,
  "BONUSES_EXPIRE": true,
  "BONUSES_EXPIRE_FORMATTED": true,
  "BONUSES_PERCENT": true,
  "CLIENT_PHONE": true,
  "DISCOUNTS": {
    "__fragment": "Discount"
  },
  "DIS_FIRST_ORDER": true,
  "DIS_SELF_PICKUP": true,
  "EXPIRED": true,
  "FETCHED": true,
  "FETCHED_ACTUAL": true,
  "FETCHED_TIME": true,
  "FETCHED_TIME_FORMATTED": true,
  "ID": true,
  "LEVEL": {
    "__fragment": "BonusLevel"
  },
  "LEVEL_CODE": true,
  "LEVEL_NAME": true,
  "MONTH_SPENT": true,
  "TRANSPORT": {},
  "USER_ID": true
}
const SaleOrderCancelResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "Order"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleOrderPayOnlineResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleOrderPayOnlineResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleOrderPayOnlineResultPayload = {
  "action": {
    "__fragment": "ActionWeb"
  },
  "order": {
    "__fragment": "Order"
  }
}
const SaleOrderRepeatResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "Order"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleProfileCalcDeliveryResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "DeliveryCalculate"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleProfileDefaultResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "OrderProfile"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleProfileDeleteResult = {
  "error": {},
  "errors": {},
  "payload": true,
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleProfileSaveResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "OrderProfile"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderApplyResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleVorderApplyResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderApplyResultPayload = {
  "vorder": {
    "__fragment": "Vorder"
  }
}
const SaleVorderBasketResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleVorderBasketResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderBasketResultPayload = {
  "vorder": {
    "__fragment": "Vorder"
  }
}
const SaleVorderCouponResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleVorderCouponResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderCouponResultPayload = {
  "coupon": {
    "__fragment": "Coupon"
  },
  "vorder": {
    "__fragment": "Vorder"
  }
}
const SaleVorderNewResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleVorderNewResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderNewResultPayload = {
  "vorder": {
    "__fragment": "Vorder"
  }
}
const SaleVorderReserveResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleVorderReserveResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderReserveResultPayload = {
  "calc": {
    "__fragment": "DeliveryCalculate"
  },
  "vorder": {
    "__fragment": "Vorder"
  }
}
const SaleVorderSubmitResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleVorderSubmitResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderSubmitResultPayload = {
  "order": {
    "__fragment": "Order"
  },
  "orderId": true,
  "orderUrl": true,
  "vorder": {
    "__fragment": "Vorder"
  }
}
const SaleVorderSyncResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "SaleVorderSyncResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const SaleVorderSyncResultPayload = {
  "vorder": {
    "__fragment": "Vorder"
  }
}
const SearchResult = {
  "ELEMENTS": {
    "__fragment": "Product"
  },
  "SECTIONS": {
    "__fragment": "Section"
  }
}
const SearchSuggestion = {
  "data": {
    "__fragment": "SearchSuggestionData"
  },
  "label": true,
  "value": true
}
const SearchSuggestionData = {
  "entityId": true,
  "entityRole": true,
  "entityTitle": true,
  "entityTypeCode": true,
  "entityTypeId": true,
  "entityTypeName": true,
  "hint": true,
  "type": true
}
const Section = {
  "ACTIVE": true,
  "CHILDREN": {
    "__fragment": "Section"
  },
  "CODE": true,
  "DATE_CREATE": true,
  "DEPTH_LEVEL": true,
  "DESCRIPTION": true,
  "DESCRIPTION_TYPE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "ELEMENT_CNT": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_EXTERNAL_ID": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_TYPE_ID": true,
  "ID": true,
  "LEFT_MARGIN": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "NAME": true,
  "PARENT": {
    "__fragment": "Section"
  },
  "PARENTS": {
    "__fragment": "Section"
  },
  "PICTURE": {
    "__fragment": "Image"
  },
  "REPLACE_LINK": true,
  "RIGHT_MARGIN": true,
  "SEARCHABLE_CONTENT": true,
  "SECTION_PAGE_URL": true,
  "SORT": true,
  "TIMESTAMP_X": true
}
const SpecialOffer = {
  "ELEMENT": {
    "__fragment": "Product"
  },
  "ELEMENT_ID": true,
  "MIN_PRICE": true,
  "MODE": true,
  "TYPE": true,
  "TYPE_INFO": {
    "__fragment": "SpecialOfferType"
  }
}
const SpecialOfferType = {
  "CODE": true,
  "COLOR": true,
  "NAME": true
}
const User = {
  "AVATAR": {
    "__fragment": "UserAvatar"
  },
  "EMAIL": true,
  "FAMILY": {
    "__fragment": "UserFamily"
  },
  "GREETING_NAME": true,
  "GROUPS_INFO": {
    "__fragment": "UserGroup"
  },
  "ID": true,
  "LAST_NAME": true,
  "LOGIN": true,
  "LOGIN_FORMAT": true,
  "NAME": true,
  "NAME_FULL": true,
  "NAME_TEASER": true,
  "PERSONAL_BIRTHDAY": true,
  "PERSONAL_PHOTO": {
    "__fragment": "Image"
  },
  "PERSON_TYPE_ID": true,
  "PHONE": true,
  "PHONE_FORMATTED": true,
  "PROFILE_FILLED": true,
  "PROFILE_GIFT_USED": true,
  "PROMOCODE": true,
  "PROPS": {
    "__fragment": "EntityProp"
  },
  "ROLES": true,
  "SECOND_NAME": true,
  "SESSION_ID": true
}
const UserAuthConfirm = {
  "CODE": true,
  "COLOR": true,
  "CONFIRM_CONTENT_MOBILE": true,
  "CONFIRM_CONTENT_WEB": true,
  "CONFIRM_STEPS": {
    "__fragment": "UserAuthConfirmStep"
  },
  "ICON": true,
  "LIST_BUTTON_MOBILE": {
    "__fragment": "MenuItemMobile"
  },
  "LIST_BUTTON_WEB": {
    "__fragment": "MenuItem"
  },
  "LIST_CAPTION": true,
  "LIST_NAME": true,
  "NAME": true,
  "RESEND_TITLE": true
}
const UserAuthConfirmStep = {
  "CAPTION": true,
  "CODE": true,
  "NAME": true
}
const UserAuthLoginConfirmResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "UserAuthLoginConfirmResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const UserAuthLoginConfirmResultPayload = {
  "appClient": {
    "__fragment": "AppClient"
  },
  "redirect": true,
  "sessionId": true,
  "userId": true
}
const UserAuthLoginRequestResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "UserAuthLoginRequestResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const UserAuthLoginRequestResultPayload = {
  "id": true,
  "sid": true
}
const UserAuthLoginStartResult = {
  "error": {},
  "errors": {},
  "payload": {
    "__fragment": "UserAuthLoginStartResultPayload"
  },
  "state": {
    "__fragment": "ResponseState"
  },
  "success": true
}
const UserAuthLoginStartResultPayload = {
  "confirmModes": {
    "__fragment": "UserAuthConfirm"
  }
}
const UserAvatar = {
  "ELEMENT_ID": true,
  "IMAGE": {
    "__fragment": "Image"
  }
}
const UserFamily = {
  "BIRTHDAY": true,
  "ID": true,
  "NAME": true,
  "RELATION": true,
  "USER_ID": true
}
const UserGroup = {
  "ID": true,
  "NAME": true,
  "TYPE": true
}
const UserSession = {
  "FUSER_ID": true,
  "SESSION_ID": true,
  "USER_ID": true
}
const VacancyRecordset = {
  "info": {
    "__fragment": "QueryInfo"
  },
  "nodes": {
    "__fragment": "CompanyVacancy"
  }
}
const Vorder = {
  "ATTR": {
    "__fragment": "OrderAttributesValue"
  },
  "ATTRS": {
    "__fragment": "OrderAttribute"
  },
  "BASKET": {
    "__fragment": "Basket"
  },
  "BONUSES": true,
  "COUPONS": {
    "__fragment": "Coupon"
  },
  "DELIVERY_DEPARTMENT_ID": true,
  "DEPARTMENT_ID": true,
  "EMAIL": true,
  "FIELDS_RAW": true,
  "FUSER_ID": true,
  "ID": true,
  "ORDER": {
    "__fragment": "Order"
  },
  "ORDER_ID": true,
  "PHONE": true,
  "PICKUP_DEPARTMENT_ID": true,
  "PROFILE_ID": true,
  "PROPS_RAW": true,
  "SESSION_ID": true,
  "USER": {
    "__fragment": "User"
  },
  "USER_ID": true
}
const VorderCurrent = {
  "ATTR": {
    "__fragment": "OrderAttributesValue"
  },
  "ATTRS": {
    "__fragment": "OrderAttribute"
  },
  "BASKET": {
    "__fragment": "Basket"
  },
  "BASKET_RULES": {
    "__fragment": "BasketRule"
  },
  "BASKET_RULES_RESULT": {
    "__fragment": "BasketRulesResult"
  },
  "BONUSES": true,
  "BONUSES_AVAILABLE": true,
  "BONUSES_PERCENT": true,
  "COUPONS": {
    "__fragment": "Coupon"
  },
  "COUPON_CAN_ADD": true,
  "DELIVERIES": {
    "__fragment": "DeliveryComputed"
  },
  "DELIVERY_CALCULATED": true,
  "DELIVERY_DEPARTMENT_ID": true,
  "DELIVERY_FREE_FROM_PRICE": true,
  "DEPARTMENTS": {
    "__fragment": "CompanyOffice"
  },
  "DEPARTMENT_ID": true,
  "DISCOUNTS": {
    "__fragment": "Discount"
  },
  "EMAIL": true,
  "FIELDS_RAW": true,
  "FUSER_ID": true,
  "ID": true,
  "ORDER": {
    "__fragment": "Order"
  },
  "ORDER_ID": true,
  "PAYMENT_TYPES": {
    "__fragment": "PaymentType"
  },
  "PERSON_TYPES": {
    "__fragment": "PersonType"
  },
  "PHONE": true,
  "PICKUP_DEPARTMENT_ID": true,
  "PROFILES": {
    "__fragment": "OrderProfile"
  },
  "PROFILE_ID": true,
  "PROPS_RAW": true,
  "RESPONSE_STATE": {
    "__fragment": "ResponseState"
  },
  "SESSION_ID": true,
  "TRANSPORT_TYPES": {},
  "TS": true,
  "USER": {
    "__fragment": "User"
  },
  "USER_ID": true
}
const VorderSummary = {
  "EMAIL": true,
  "FUSER_ID": true,
  "ID": true,
  "PHONE": true
}
const Webcam = {
  "ACTIVE": true,
  "ACTIVE_FROM": true,
  "ACTIVE_TO": true,
  "CREATED_BY": true,
  "CREATED_USER_NAME": true,
  "DATE_CREATE": true,
  "DETAIL_PICTURE": {
    "__fragment": "Image"
  },
  "DETAIL_TEXT": true,
  "DETAIL_TEXT_TYPE": true,
  "EXTERNAL_ID": true,
  "IBLOCK_CODE": true,
  "IBLOCK_ID": true,
  "IBLOCK_SECTION_ID": true,
  "IBLOCK_SECTION_IDS": true,
  "ID": true,
  "META": {
    "__fragment": "ElementMeta"
  },
  "MODIFIED_BY": true,
  "NAME": true,
  "PATH": {
    "__fragment": "Section"
  },
  "PREVIEW_PICTURE": {
    "__fragment": "Image"
  },
  "PREVIEW_TEXT": true,
  "PREVIEW_TEXT_TYPE": true,
  "PROPERTIES": {
    "__fragment": "WebcamProps"
  },
  "ROOT_SECTION": {
    "__fragment": "Section"
  },
  "SECTION": {
    "__fragment": "Section"
  },
  "SECTIONS": {
    "__fragment": "Section"
  },
  "SHOW_COUNTER": true,
  "SHOW_COUNTER_START": true,
  "SORT": true,
  "TAGS": true,
  "TIMESTAMP_X": true,
  "URL": true,
  "XML_ID": true
}
const WebcamProps = {
  "CODE": true
}
import {BonusLevelFields} from '@/gql/fragments/BonusLevelFields'
import {CameraElementFields} from '@/gql/fragments/CameraElementFields'
import {CertificateFields} from '@/gql/fragments/CertificateFields'
import {ClientCardFields} from '@/gql/fragments/ClientCardFields'
import {DeliveryCalculateFields} from '@/gql/fragments/DeliveryCalculateFields'
import {DeliveryZoneFields} from '@/gql/fragments/DeliveryZoneFields'
import {DiscountFields} from '@/gql/fragments/DiscountFields'
import {ErrorFields} from '@/gql/fragments/ErrorFields'
import {FaqElementFields} from '@/gql/fragments/FaqElementFields'
import {GalleryFields} from '@/gql/fragments/GalleryFields'
import {MenuFields} from '@/gql/fragments/MenuFields'
import {MenuItemBaseFields} from '@/gql/fragments/MenuItemBaseFields'
import {MenuItemFields} from '@/gql/fragments/MenuItemFields'
import {NewsFields} from '@/gql/fragments/NewsFields'
import {NoticeFields} from '@/gql/fragments/NoticeFields'
import {OfferFields} from '@/gql/fragments/OfferFields'
import {OfficeFields} from '@/gql/fragments/OfficeFields'
import {OrderAttrsFields} from '@/gql/fragments/OrderAttrsFields'
import {OrderFields} from '@/gql/fragments/OrderFields'
import {OrderProfileFields} from '@/gql/fragments/OrderProfileFields'
import {PageFields} from '@/gql/fragments/PageFields'
import {ProductElementFields} from '@/gql/fragments/ProductElementFields'
import {ProductElementPropsFields} from '@/gql/fragments/ProductElementPropsFields'
import {ProductSectionFields} from '@/gql/fragments/ProductSectionFields'
import {ProductSectionPropsFields} from '@/gql/fragments/ProductSectionPropsFields'
import {ReviewFields} from '@/gql/fragments/ReviewFields'
import {UserAuthConfirmFields} from '@/gql/fragments/UserAuthConfirmFields'
import {UserAuthConfirmStepFields} from '@/gql/fragments/UserAuthConfirmStepFields'
import {UserFields} from '@/gql/fragments/UserFields'
import {VacancyFields} from '@/gql/fragments/VacancyFields'
import {VorderCurrentFields} from '@/gql/fragments/VorderCurrentFields'
export const AccessErrorFragment = AccessError
export const ActionMobileFragment = ActionMobile
export const ActionWebFragment = ActionWeb
export const AppClientFragment = AppClient
export const AppClientDebugParamsFragment = AppClientDebugParams
export const AppProductRecordsetFragment = AppProductRecordset
export const BasketFragment = Basket
export const BasketBuildItemFragment = BasketBuildItem
export const BasketItemFragment = BasketItem
export const BasketItemDiscountFragment = BasketItemDiscount
export const BasketItemPropFragment = BasketItemProp
export const BasketRuleFragment = BasketRule
export const BasketRuleActionDiscountFragment = BasketRuleActionDiscount
export const BasketRuleConditionFragment = BasketRuleCondition
export const BasketRulesResulBenefitProductFragment = BasketRulesResulBenefitProduct
export const BasketRulesResultFragment = BasketRulesResult
export const BonusLevelFragment = BonusLevel
export const BonusLevelPropsFragment = BonusLevelProps
export const ClientCardRecordsetFragment = ClientCardRecordset
export const ClientEmitFragment = ClientEmit
export const ClientNoticeFragment = ClientNotice
export const CommandFragment = Command
export const CommonErrorFragment = CommonError
export const CompanyOfficeFragment = CompanyOffice
export const CompanyOfficePropsFragment = CompanyOfficeProps
export const CompanyVacancyFragment = CompanyVacancy
export const ConditionFragment = Condition
export const ConstructorBuildFragment = ConstructorBuild
export const ConstructorBuildItemFragment = ConstructorBuildItem
export const ConstructorElementFragment = ConstructorElement
export const CoordinatesFragment = Coordinates
export const CouponFragment = Coupon
export const CourierStateFragment = CourierState
export const DeliveryCalculateFragment = DeliveryCalculate
export const DeliveryComputedFragment = DeliveryComputed
export const DeliveryServiceFragment = DeliveryService
export const DeliveryZoneFragment = DeliveryZone
export const DiscountFragment = Discount
export const DiscountPropsFragment = DiscountProps
export const DiscountPropsCONDITIONFragment = DiscountPropsCONDITION
export const ElementFragment = Element
export const ElementMetaFragment = ElementMeta
export const ElementPropValueHtmlFragment = ElementPropValueHtml
export const ElementPropValueWithDescFragment = ElementPropValueWithDesc
export const EntityInfoFragment = EntityInfo
export const EntityInfoUrlsFragment = EntityInfoUrls
export const EntityPropFragment = EntityProp
export const ExternalServiceErrorFragment = ExternalServiceError
export const FaqQuestionFragment = FaqQuestion
export const FaqQuestionRecordsetFragment = FaqQuestionRecordset
export const FormErrorFragment = FormError
export const GeoObjectFragment = GeoObject
export const ImageFragment = Image
export const ListValueFragment = ListValue
export const MenuFragment = Menu
export const MenuItemFragment = MenuItem
export const MenuItemMobileFragment = MenuItemMobile
export const MessageFragment = Message
export const NoticePubSyncReadedResultFragment = NoticePubSyncReadedResult
export const OfferFragment = Offer
export const OfferRecordsetFragment = OfferRecordset
export const OfferSlideFragment = OfferSlide
export const OfficeRecordsetFragment = OfficeRecordset
export const OrderFragment = Order
export const OrderAttributeFragment = OrderAttribute
export const OrderAttributeOptionFragment = OrderAttributeOption
export const OrderAttributesValueFragment = OrderAttributesValue
export const OrderCancelReasonFragment = OrderCancelReason
export const OrderDataFragment = OrderData
export const OrderProfileFragment = OrderProfile
export const OrderProfileAttributesValueFragment = OrderProfileAttributesValue
export const OrderProfileRecordsetFragment = OrderProfileRecordset
export const OrderScopeFragment = OrderScope
export const OrderStatusFragment = OrderStatus
export const OtpErrorFragment = OtpError
export const PageFragment = Page
export const PageContentChunkFragment = PageContentChunk
export const PageDataChunkFragment = PageDataChunk
export const PageRecordsetFragment = PageRecordset
export const PaymentFragment = Payment
export const PaymentTypeFragment = PaymentType
export const PaysystemFragment = Paysystem
export const PersonTypeFragment = PersonType
export const ProductFragment = Product
export const ProductBenefitFragment = ProductBenefit
export const ProductFlagFragment = ProductFlag
export const ProductMeasureFragment = ProductMeasure
export const ProductPriceFragment = ProductPrice
export const ProductPropsFragment = ProductProps
export const ProductPropsAVAILABLE_SCHEDULEFragment = ProductPropsAVAILABLE_SCHEDULE
export const ProductPropsBENEFITSFragment = ProductPropsBENEFITS
export const ProductPropsSET_ITEMSFragment = ProductPropsSET_ITEMS
export const ProductSectionFragment = ProductSection
export const ProductSectionPropsFragment = ProductSectionProps
export const ProductSetItemFragment = ProductSetItem
export const ProductTagFragment = ProductTag
export const QueryInfoFragment = QueryInfo
export const RateErrorFragment = RateError
export const ResponseFragment = Response
export const ResponseRateFragment = ResponseRate
export const ResponseRedirectFragment = ResponseRedirect
export const ResponseStateFragment = ResponseState
export const ReviewFragment = Review
export const ReviewOrderGuestReviewResultFragment = ReviewOrderGuestReviewResult
export const ReviewOrderGuestReviewResultPayloadFragment = ReviewOrderGuestReviewResultPayload
export const ReviewServiceReviewResultFragment = ReviewServiceReviewResult
export const SaleClientCardFragment = SaleClientCard
export const SaleOrderCancelResultFragment = SaleOrderCancelResult
export const SaleOrderPayOnlineResultFragment = SaleOrderPayOnlineResult
export const SaleOrderPayOnlineResultPayloadFragment = SaleOrderPayOnlineResultPayload
export const SaleOrderRepeatResultFragment = SaleOrderRepeatResult
export const SaleProfileCalcDeliveryResultFragment = SaleProfileCalcDeliveryResult
export const SaleProfileDefaultResultFragment = SaleProfileDefaultResult
export const SaleProfileDeleteResultFragment = SaleProfileDeleteResult
export const SaleProfileSaveResultFragment = SaleProfileSaveResult
export const SaleVorderApplyResultFragment = SaleVorderApplyResult
export const SaleVorderApplyResultPayloadFragment = SaleVorderApplyResultPayload
export const SaleVorderBasketResultFragment = SaleVorderBasketResult
export const SaleVorderBasketResultPayloadFragment = SaleVorderBasketResultPayload
export const SaleVorderCouponResultFragment = SaleVorderCouponResult
export const SaleVorderCouponResultPayloadFragment = SaleVorderCouponResultPayload
export const SaleVorderNewResultFragment = SaleVorderNewResult
export const SaleVorderNewResultPayloadFragment = SaleVorderNewResultPayload
export const SaleVorderReserveResultFragment = SaleVorderReserveResult
export const SaleVorderReserveResultPayloadFragment = SaleVorderReserveResultPayload
export const SaleVorderSubmitResultFragment = SaleVorderSubmitResult
export const SaleVorderSubmitResultPayloadFragment = SaleVorderSubmitResultPayload
export const SaleVorderSyncResultFragment = SaleVorderSyncResult
export const SaleVorderSyncResultPayloadFragment = SaleVorderSyncResultPayload
export const SearchResultFragment = SearchResult
export const SearchSuggestionFragment = SearchSuggestion
export const SearchSuggestionDataFragment = SearchSuggestionData
export const SectionFragment = Section
export const SpecialOfferFragment = SpecialOffer
export const SpecialOfferTypeFragment = SpecialOfferType
export const UserFragment = User
export const UserAuthConfirmFragment = UserAuthConfirm
export const UserAuthConfirmStepFragment = UserAuthConfirmStep
export const UserAuthLoginConfirmResultFragment = UserAuthLoginConfirmResult
export const UserAuthLoginConfirmResultPayloadFragment = UserAuthLoginConfirmResultPayload
export const UserAuthLoginRequestResultFragment = UserAuthLoginRequestResult
export const UserAuthLoginRequestResultPayloadFragment = UserAuthLoginRequestResultPayload
export const UserAuthLoginStartResultFragment = UserAuthLoginStartResult
export const UserAuthLoginStartResultPayloadFragment = UserAuthLoginStartResultPayload
export const UserAvatarFragment = UserAvatar
export const UserFamilyFragment = UserFamily
export const UserGroupFragment = UserGroup
export const UserSessionFragment = UserSession
export const VacancyRecordsetFragment = VacancyRecordset
export const VorderFragment = Vorder
export const VorderCurrentFragment = VorderCurrent
export const VorderSummaryFragment = VorderSummary
export const WebcamFragment = Webcam
export const WebcamPropsFragment = WebcamProps
export const BonusLevelFieldsFragment = BonusLevelFields
export const CameraElementFieldsFragment = CameraElementFields
export const CertificateFieldsFragment = CertificateFields
export const ClientCardFieldsFragment = ClientCardFields
export const DeliveryCalculateFieldsFragment = DeliveryCalculateFields
export const DeliveryZoneFieldsFragment = DeliveryZoneFields
export const DiscountFieldsFragment = DiscountFields
export const ErrorFieldsFragment = ErrorFields
export const FaqElementFieldsFragment = FaqElementFields
export const GalleryFieldsFragment = GalleryFields
export const MenuFieldsFragment = MenuFields
export const MenuItemBaseFieldsFragment = MenuItemBaseFields
export const MenuItemFieldsFragment = MenuItemFields
export const NewsFieldsFragment = NewsFields
export const NoticeFieldsFragment = NoticeFields
export const OfferFieldsFragment = OfferFields
export const OfficeFieldsFragment = OfficeFields
export const OrderAttrsFieldsFragment = OrderAttrsFields
export const OrderFieldsFragment = OrderFields
export const OrderProfileFieldsFragment = OrderProfileFields
export const PageFieldsFragment = PageFields
export const ProductElementFieldsFragment = ProductElementFields
export const ProductElementPropsFieldsFragment = ProductElementPropsFields
export const ProductSectionFieldsFragment = ProductSectionFields
export const ProductSectionPropsFieldsFragment = ProductSectionPropsFields
export const ReviewFieldsFragment = ReviewFields
export const UserAuthConfirmFieldsFragment = UserAuthConfirmFields
export const UserAuthConfirmStepFieldsFragment = UserAuthConfirmStepFields
export const UserFieldsFragment = UserFields
export const VacancyFieldsFragment = VacancyFields
export const VorderCurrentFieldsFragment = VorderCurrentFields
export const fragments = {
    AccessError,
    ActionMobile,
    ActionWeb,
    AppClient,
    AppClientDebugParams,
    AppProductRecordset,
    Basket,
    BasketBuildItem,
    BasketItem,
    BasketItemDiscount,
    BasketItemProp,
    BasketRule,
    BasketRuleActionDiscount,
    BasketRuleCondition,
    BasketRulesResulBenefitProduct,
    BasketRulesResult,
    BonusLevel,
    BonusLevelProps,
    ClientCardRecordset,
    ClientEmit,
    ClientNotice,
    Command,
    CommonError,
    CompanyOffice,
    CompanyOfficeProps,
    CompanyVacancy,
    Condition,
    ConstructorBuild,
    ConstructorBuildItem,
    ConstructorElement,
    Coordinates,
    Coupon,
    CourierState,
    DeliveryCalculate,
    DeliveryComputed,
    DeliveryService,
    DeliveryZone,
    Discount,
    DiscountProps,
    DiscountPropsCONDITION,
    Element,
    ElementMeta,
    ElementPropValueHtml,
    ElementPropValueWithDesc,
    EntityInfo,
    EntityInfoUrls,
    EntityProp,
    ExternalServiceError,
    FaqQuestion,
    FaqQuestionRecordset,
    FormError,
    GeoObject,
    Image,
    ListValue,
    Menu,
    MenuItem,
    MenuItemMobile,
    Message,
    NoticePubSyncReadedResult,
    Offer,
    OfferRecordset,
    OfferSlide,
    OfficeRecordset,
    Order,
    OrderAttribute,
    OrderAttributeOption,
    OrderAttributesValue,
    OrderCancelReason,
    OrderData,
    OrderProfile,
    OrderProfileAttributesValue,
    OrderProfileRecordset,
    OrderScope,
    OrderStatus,
    OtpError,
    Page,
    PageContentChunk,
    PageDataChunk,
    PageRecordset,
    Payment,
    PaymentType,
    Paysystem,
    PersonType,
    Product,
    ProductBenefit,
    ProductFlag,
    ProductMeasure,
    ProductPrice,
    ProductProps,
    ProductPropsAVAILABLE_SCHEDULE,
    ProductPropsBENEFITS,
    ProductPropsSET_ITEMS,
    ProductSection,
    ProductSectionProps,
    ProductSetItem,
    ProductTag,
    QueryInfo,
    RateError,
    Response,
    ResponseRate,
    ResponseRedirect,
    ResponseState,
    Review,
    ReviewOrderGuestReviewResult,
    ReviewOrderGuestReviewResultPayload,
    ReviewServiceReviewResult,
    SaleClientCard,
    SaleOrderCancelResult,
    SaleOrderPayOnlineResult,
    SaleOrderPayOnlineResultPayload,
    SaleOrderRepeatResult,
    SaleProfileCalcDeliveryResult,
    SaleProfileDefaultResult,
    SaleProfileDeleteResult,
    SaleProfileSaveResult,
    SaleVorderApplyResult,
    SaleVorderApplyResultPayload,
    SaleVorderBasketResult,
    SaleVorderBasketResultPayload,
    SaleVorderCouponResult,
    SaleVorderCouponResultPayload,
    SaleVorderNewResult,
    SaleVorderNewResultPayload,
    SaleVorderReserveResult,
    SaleVorderReserveResultPayload,
    SaleVorderSubmitResult,
    SaleVorderSubmitResultPayload,
    SaleVorderSyncResult,
    SaleVorderSyncResultPayload,
    SearchResult,
    SearchSuggestion,
    SearchSuggestionData,
    Section,
    SpecialOffer,
    SpecialOfferType,
    User,
    UserAuthConfirm,
    UserAuthConfirmStep,
    UserAuthLoginConfirmResult,
    UserAuthLoginConfirmResultPayload,
    UserAuthLoginRequestResult,
    UserAuthLoginRequestResultPayload,
    UserAuthLoginStartResult,
    UserAuthLoginStartResultPayload,
    UserAvatar,
    UserFamily,
    UserGroup,
    UserSession,
    VacancyRecordset,
    Vorder,
    VorderCurrent,
    VorderSummary,
    Webcam,
    WebcamProps,
    BonusLevelFields,
    CameraElementFields,
    CertificateFields,
    ClientCardFields,
    DeliveryCalculateFields,
    DeliveryZoneFields,
    DiscountFields,
    ErrorFields,
    FaqElementFields,
    GalleryFields,
    MenuFields,
    MenuItemBaseFields,
    MenuItemFields,
    NewsFields,
    NoticeFields,
    OfferFields,
    OfficeFields,
    OrderAttrsFields,
    OrderFields,
    OrderProfileFields,
    PageFields,
    ProductElementFields,
    ProductElementPropsFields,
    ProductSectionFields,
    ProductSectionPropsFields,
    ReviewFields,
    UserAuthConfirmFields,
    UserAuthConfirmStepFields,
    UserFields,
    VacancyFields,
    VorderCurrentFields
}
export type JsonFragmentName = 'AccessError' | 'ActionMobile' | 'ActionWeb' | 'AppClient' | 'AppClientDebugParams' | 'AppProductRecordset' | 'Basket' | 'BasketBuildItem' | 'BasketItem' | 'BasketItemDiscount' | 'BasketItemProp' | 'BasketRule' | 'BasketRuleActionDiscount' | 'BasketRuleCondition' | 'BasketRulesResulBenefitProduct' | 'BasketRulesResult' | 'BonusLevel' | 'BonusLevelProps' | 'ClientCardRecordset' | 'ClientEmit' | 'ClientNotice' | 'Command' | 'CommonError' | 'CompanyOffice' | 'CompanyOfficeProps' | 'CompanyVacancy' | 'Condition' | 'ConstructorBuild' | 'ConstructorBuildItem' | 'ConstructorElement' | 'Coordinates' | 'Coupon' | 'CourierState' | 'DeliveryCalculate' | 'DeliveryComputed' | 'DeliveryService' | 'DeliveryZone' | 'Discount' | 'DiscountProps' | 'DiscountPropsCONDITION' | 'Element' | 'ElementMeta' | 'ElementPropValueHtml' | 'ElementPropValueWithDesc' | 'EntityInfo' | 'EntityInfoUrls' | 'EntityProp' | 'ExternalServiceError' | 'FaqQuestion' | 'FaqQuestionRecordset' | 'FormError' | 'GeoObject' | 'Image' | 'ListValue' | 'Menu' | 'MenuItem' | 'MenuItemMobile' | 'Message' | 'NoticePubSyncReadedResult' | 'Offer' | 'OfferRecordset' | 'OfferSlide' | 'OfficeRecordset' | 'Order' | 'OrderAttribute' | 'OrderAttributeOption' | 'OrderAttributesValue' | 'OrderCancelReason' | 'OrderData' | 'OrderProfile' | 'OrderProfileAttributesValue' | 'OrderProfileRecordset' | 'OrderScope' | 'OrderStatus' | 'OtpError' | 'Page' | 'PageContentChunk' | 'PageDataChunk' | 'PageRecordset' | 'Payment' | 'PaymentType' | 'Paysystem' | 'PersonType' | 'Product' | 'ProductBenefit' | 'ProductFlag' | 'ProductMeasure' | 'ProductPrice' | 'ProductProps' | 'ProductPropsAVAILABLE_SCHEDULE' | 'ProductPropsBENEFITS' | 'ProductPropsSET_ITEMS' | 'ProductSection' | 'ProductSectionProps' | 'ProductSetItem' | 'ProductTag' | 'QueryInfo' | 'RateError' | 'Response' | 'ResponseRate' | 'ResponseRedirect' | 'ResponseState' | 'Review' | 'ReviewOrderGuestReviewResult' | 'ReviewOrderGuestReviewResultPayload' | 'ReviewServiceReviewResult' | 'SaleClientCard' | 'SaleOrderCancelResult' | 'SaleOrderPayOnlineResult' | 'SaleOrderPayOnlineResultPayload' | 'SaleOrderRepeatResult' | 'SaleProfileCalcDeliveryResult' | 'SaleProfileDefaultResult' | 'SaleProfileDeleteResult' | 'SaleProfileSaveResult' | 'SaleVorderApplyResult' | 'SaleVorderApplyResultPayload' | 'SaleVorderBasketResult' | 'SaleVorderBasketResultPayload' | 'SaleVorderCouponResult' | 'SaleVorderCouponResultPayload' | 'SaleVorderNewResult' | 'SaleVorderNewResultPayload' | 'SaleVorderReserveResult' | 'SaleVorderReserveResultPayload' | 'SaleVorderSubmitResult' | 'SaleVorderSubmitResultPayload' | 'SaleVorderSyncResult' | 'SaleVorderSyncResultPayload' | 'SearchResult' | 'SearchSuggestion' | 'SearchSuggestionData' | 'Section' | 'SpecialOffer' | 'SpecialOfferType' | 'User' | 'UserAuthConfirm' | 'UserAuthConfirmStep' | 'UserAuthLoginConfirmResult' | 'UserAuthLoginConfirmResultPayload' | 'UserAuthLoginRequestResult' | 'UserAuthLoginRequestResultPayload' | 'UserAuthLoginStartResult' | 'UserAuthLoginStartResultPayload' | 'UserAvatar' | 'UserFamily' | 'UserGroup' | 'UserSession' | 'VacancyRecordset' | 'Vorder' | 'VorderCurrent' | 'VorderSummary' | 'Webcam' | 'WebcamProps' | 'BonusLevelFields' | 'CameraElementFields' | 'CertificateFields' | 'ClientCardFields' | 'DeliveryCalculateFields' | 'DeliveryZoneFields' | 'DiscountFields' | 'ErrorFields' | 'FaqElementFields' | 'GalleryFields' | 'MenuFields' | 'MenuItemBaseFields' | 'MenuItemFields' | 'NewsFields' | 'NoticeFields' | 'OfferFields' | 'OfficeFields' | 'OrderAttrsFields' | 'OrderFields' | 'OrderProfileFields' | 'PageFields' | 'ProductElementFields' | 'ProductElementPropsFields' | 'ProductSectionFields' | 'ProductSectionPropsFields' | 'ReviewFields' | 'UserAuthConfirmFields' | 'UserAuthConfirmStepFields' | 'UserFields' | 'VacancyFields' | 'VorderCurrentFields'