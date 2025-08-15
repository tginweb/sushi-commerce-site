export default {
    "scalars": [
        1,
        2,
        6,
        9,
        10,
        11,
        13,
        16,
        18,
        19,
        24,
        26,
        27,
        29,
        54,
        58,
        61,
        64,
        75,
        76,
        83,
        85,
        91,
        101,
        102,
        104,
        182
    ],
    "types": {
        "AccessError": {
            "authRedirect": [
                1
            ],
            "data": [
                85
            ],
            "fieldMessage": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                75
            ],
            "rel": [
                85
            ],
            "type": [
                76
            ],
            "__typename": [
                2
            ]
        },
        "Boolean": {},
        "String": {},
        "ActionMobile": {
            "addBacklink": [
                1
            ],
            "addSession": [
                1
            ],
            "await": [
                1
            ],
            "code": [
                2
            ],
            "replace": [
                1
            ],
            "title": [
                2
            ],
            "titleAuto": [
                1
            ],
            "url": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ActionWeb": {
            "code": [
                2
            ],
            "title": [
                2
            ],
            "url": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "AppClient": {
            "CLIENT_ID": [
                2
            ],
            "CURRENT_SESSION_ID": [
                2
            ],
            "DEBUG_PARAMS": [
                7
            ],
            "ID": [
                6
            ],
            "MOBILE_PUSH_TOKEN": [
                2
            ],
            "SESSION_ID": [
                2
            ],
            "TOKEN": [
                2
            ],
            "USER_ID": [
                6
            ],
            "WEB_PUSH_TOKEN": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Int": {},
        "AppClientDebugParams": {
            "eventsTransport": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "AppProductRecordset": {
            "info": [
                140
            ],
            "nodes": [
                125
            ],
            "__typename": [
                2
            ]
        },
        "AttrPaymentTypeEnum": {},
        "AttrTimeModeEnum": {},
        "AttrTransportTypeEnum": {},
        "Basket": {
            "BASE_PRICE": [
                13
            ],
            "CLIENT_CHANGED_AT": [
                6
            ],
            "COUNT": [
                6
            ],
            "GIFTS": [
                6
            ],
            "HASH": [
                2
            ],
            "ITEMS": [
                15
            ],
            "MIN_PRICE": [
                13
            ],
            "MIN_PRICE_REACHED": [
                1
            ],
            "OFFERS": [
                179
            ],
            "PRICE": [
                13
            ],
            "QUANTITY": [
                13
            ],
            "SYNCED": [
                1
            ],
            "TS": [
                2
            ],
            "WEIGHT": [
                13
            ],
            "__typename": [
                2
            ]
        },
        "Float": {},
        "BasketBuildItem": {
            "ELEMENT": [
                125
            ],
            "PRODUCT_ID": [
                6
            ],
            "QUANTITY": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "BasketItem": {
            "BASE_PRICE": [
                13
            ],
            "BENEFIT": [
                16
            ],
            "BUILD": [
                14
            ],
            "CLIENT_CHANGED_AT": [
                6
            ],
            "CLIENT_ID": [
                2
            ],
            "COMMENT": [
                2
            ],
            "DESC": [
                2
            ],
            "DISABLE": [
                1
            ],
            "DISABLE_REASON": [
                2
            ],
            "DISCOUNTS": [
                17
            ],
            "ELEMENT": [
                125
            ],
            "FINAL_PRICE": [
                13
            ],
            "FINAL_PRICE_BASE": [
                13
            ],
            "ID": [
                6
            ],
            "INPUT_PROPS_HASH": [
                2
            ],
            "MEASURE_NAME": [
                2
            ],
            "NAME": [
                2
            ],
            "ORDER_ID": [
                6
            ],
            "PAID": [
                1
            ],
            "PARENT_ID": [
                6
            ],
            "PRICE": [
                13
            ],
            "PRICE_BASE": [
                13
            ],
            "PRODUCT_ID": [
                6
            ],
            "PROPS": [
                20
            ],
            "QUANTITY": [
                13
            ],
            "UUID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "BasketItemBenefitType": {},
        "BasketItemDiscount": {
            "AMOUNT": [
                13
            ],
            "BASE_PRICE": [
                13
            ],
            "DISCOUNTED_PRICE": [
                13
            ],
            "RULE": [
                2
            ],
            "TARGET": [
                18
            ],
            "TYPE": [
                19
            ],
            "__typename": [
                2
            ]
        },
        "BasketItemDiscountTarget": {},
        "BasketItemDiscountType": {},
        "BasketItemProp": {
            "CODE": [
                2
            ],
            "NAME": [
                2
            ],
            "VALUE": [
                85
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "BasketRule": {
            "ACTIONS": [
                23
            ],
            "ALL_STOP": [
                1
            ],
            "CAPTION": [
                2
            ],
            "CHILDREN": [
                21
            ],
            "CODE": [
                2
            ],
            "CONDITIONS": [
                25
            ],
            "HOTEST": [
                1
            ],
            "ID": [
                6
            ],
            "LEVEL_STOP": [
                1
            ],
            "NAME": [
                2
            ],
            "NAME_TEMPLATE": [
                2
            ],
            "PARENT": [
                2
            ],
            "PERCENT": [
                6
            ],
            "TYPE": [
                27
            ],
            "__typename": [
                2
            ]
        },
        "BasketRuleActionDiscount": {
            "AMOUNT": [
                6
            ],
            "AMOUNT_SURCHARGE": [
                6
            ],
            "ID": [
                6
            ],
            "MODE": [
                61
            ],
            "NAME": [
                2
            ],
            "PRODUCT_IDS": [
                6
            ],
            "SECTION_IDS": [
                6
            ],
            "TARGET": [
                64
            ],
            "TYPE": [
                24
            ],
            "__typename": [
                2
            ]
        },
        "BasketRuleActionInterface": {
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "TYPE": [
                24
            ],
            "on_BasketRuleActionDiscount": [
                22
            ],
            "__typename": [
                2
            ]
        },
        "BasketRuleActionTypeEnum": {},
        "BasketRuleCondition": {
            "CHILDREN": [
                25
            ],
            "CODE": [
                2
            ],
            "ID": [
                6
            ],
            "IN": [
                85
            ],
            "MAX": [
                6
            ],
            "MIN": [
                6
            ],
            "NAME": [
                2
            ],
            "NOT": [
                1
            ],
            "TYPE": [
                26
            ],
            "VALUE": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "BasketRuleConditionTypeEnum": {},
        "BasketRuleType": {},
        "BasketRulesResulBenefitProduct": {
            "PRODUCT_ID": [
                6
            ],
            "QUANTITY": [
                6
            ],
            "TYPE": [
                29
            ],
            "__typename": [
                2
            ]
        },
        "BasketRulesResulBenefitProductType": {},
        "BasketRulesResult": {
            "ALLOW": [
                2
            ],
            "BENEFIT_PRODUCTS": [
                28
            ],
            "DENY": [
                2
            ],
            "DISCOUNTS_BASKET": [
                22
            ],
            "DISCOUNTS_DELIVERY": [
                22
            ],
            "DISCOUNTS_PRODUCT": [
                22
            ],
            "DISCOUNTS_SECTION": [
                22
            ],
            "DISCOUNTS_TOTAL": [
                22
            ],
            "__typename": [
                2
            ]
        },
        "BonusLevel": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "PROPERTIES": [
                32
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "BonusLevelProps": {
            "ACCUMULATION_PERCENT": [
                6
            ],
            "COLOR": [
                2
            ],
            "MAX_USE_PERCENT": [
                6
            ],
            "MONTH_SPENT_MAX": [
                6
            ],
            "MONTH_SPENT_MIN": [
                6
            ],
            "ORDERS_SUMM": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "CaptchaInput": {
            "ANSWER": [
                2
            ],
            "PROVIDER": [
                2
            ],
            "SID": [
                2
            ],
            "VALUE": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "ClientCardFilterInput": {
            "BONUSES": [
                84
            ],
            "BONUSES_EXPIRE": [
                53
            ],
            "BONUSES_PERCENT": [
                84
            ],
            "CLIENT_PHONE": [
                181
            ],
            "DIS_FIRST_ORDER": [
                84
            ],
            "DIS_SELF_PICKUP": [
                84
            ],
            "FETCHED": [
                1
            ],
            "FETCHED_TIME": [
                53
            ],
            "ID": [
                84
            ],
            "LEVEL_CODE": [
                181
            ],
            "LEVEL_NAME": [
                181
            ],
            "MONTH_SPENT": [
                84
            ],
            "USER_ID": [
                84
            ],
            "__typename": [
                2
            ]
        },
        "ClientCardRecordset": {
            "info": [
                140
            ],
            "nodes": [
                151
            ],
            "__typename": [
                2
            ]
        },
        "ClientEmit": {
            "body": [
                2
            ],
            "cls": [
                2
            ],
            "createdAt": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "eventData": [
                85
            ],
            "eventGroup": [
                2
            ],
            "eventName": [
                2
            ],
            "id": [
                6
            ],
            "message": [
                2
            ],
            "targetClientId": [
                2
            ],
            "targetUserId": [
                6
            ],
            "title": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ClientNotice": {
            "actionItems": [
                88
            ],
            "body": [
                2
            ],
            "bodyHtml": [
                2
            ],
            "cls": [
                2
            ],
            "createdAt": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "eventData": [
                85
            ],
            "eventGroup": [
                2
            ],
            "eventName": [
                2
            ],
            "haveBody": [
                1
            ],
            "id": [
                6
            ],
            "image": [
                2
            ],
            "isReaded": [
                1
            ],
            "message": [
                2
            ],
            "offerId": [
                6
            ],
            "showAs": [
                2
            ],
            "targetClientId": [
                2
            ],
            "targetCode": [
                2
            ],
            "targetUserId": [
                6
            ],
            "title": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Command": {
            "code": [
                2
            ],
            "confirm": [
                1
            ],
            "params": [
                85
            ],
            "path": [
                2
            ],
            "type": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "CommonError": {
            "data": [
                85
            ],
            "fieldMessage": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                75
            ],
            "rel": [
                85
            ],
            "type": [
                76
            ],
            "__typename": [
                2
            ]
        },
        "CompanyOffice": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "PROPERTIES": [
                43
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "CompanyOfficeInput": {
            "CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "NAME": [
                2
            ],
            "PROPERTIES": [
                42
            ],
            "__typename": [
                2
            ]
        },
        "CompanyOfficeInputProps": {
            "ADDRESS": [
                2
            ],
            "COORDINATES": [
                50
            ],
            "EMAIL": [
                2
            ],
            "GIS_URL": [
                2
            ],
            "MAIN": [
                2
            ],
            "PAYSYSTEM_ID": [
                6
            ],
            "PHONES": [
                2
            ],
            "REQUISITES": [
                2
            ],
            "ROLES": [
                6
            ],
            "SERVICE_ID": [
                2
            ],
            "WORKTIME": [
                2
            ],
            "WORKTIME_FRI": [
                2
            ],
            "WORKTIME_MON": [
                2
            ],
            "WORKTIME_SAT": [
                2
            ],
            "WORKTIME_SUN": [
                2
            ],
            "WORKTIME_THU": [
                2
            ],
            "WORKTIME_TUE": [
                2
            ],
            "WORKTIME_WED": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "CompanyOfficeProps": {
            "ADDRESS": [
                2
            ],
            "COORDINATES": [
                49
            ],
            "EMAIL": [
                2
            ],
            "PHONES": [
                2
            ],
            "REQUISITES": [
                69
            ],
            "ROLES": [
                86
            ],
            "SERVICE_ID": [
                2
            ],
            "WORKTIME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "CompanyVacancy": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Condition": {
            "eq": [
                85
            ],
            "gt": [
                85
            ],
            "lt": [
                85
            ],
            "path": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ConstructorBuild": {
            "CONSTRUCTOR_CODE": [
                2
            ],
            "CONSTRUCTOR_URL": [
                2
            ],
            "SOSTAV": [
                47
            ],
            "__typename": [
                2
            ]
        },
        "ConstructorBuildItem": {
            "ELEMENT": [
                48
            ],
            "ELEMENT_ID": [
                6
            ],
            "QUANTITY": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "ConstructorElement": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "BENEFITS": [
                126
            ],
            "BUILD": [
                46
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "FLAGS": [
                128
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "IS_SALE_SPECIAL": [
                1
            ],
            "MEASURE": [
                129
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "OFFERS": [
                125
            ],
            "OFFER_PARENT_ELEMENT": [
                125
            ],
            "PARENT": [
                125
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "PRICE": [
                130
            ],
            "ROOT_SECTION": [
                177
            ],
            "SALES_COUNT": [
                6
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SET_ITEMS": [
                137
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                138
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "WEIGHT": [
                6
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Coordinates": {
            "LAT": [
                13
            ],
            "LON": [
                13
            ],
            "__typename": [
                2
            ]
        },
        "CoordinatesInput": {
            "LAT": [
                13
            ],
            "LON": [
                13
            ],
            "__typename": [
                2
            ]
        },
        "Coupon": {
            "COUPON": [
                2
            ],
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "PRODUCT": [
                125
            ],
            "PRODUCT_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "CourierState": {
            "ARRIVAL_TIME": [
                2
            ],
            "ARRIVAL_TIME_CAPTION": [
                2
            ],
            "CAR_COLOR": [
                2
            ],
            "CAR_NUMBER": [
                2
            ],
            "COORDS": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "DateFilterInput": {
            "eq": [
                2
            ],
            "gt": [
                2
            ],
            "in": [
                2
            ],
            "lt": [
                2
            ],
            "not": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "DateFormatEnum": {},
        "DeliveryCalculate": {
            "ADDRESS_COORDS": [
                2
            ],
            "DELIVERY_ADDRESS": [
                2
            ],
            "DELIVERY_HASH": [
                2
            ],
            "ID": [
                6
            ],
            "NEED_TIME": [
                6
            ],
            "NEED_TIME_FORMATTED": [
                2
            ],
            "ORDER_ID": [
                13
            ],
            "ORDER_PRICE": [
                6
            ],
            "PHONE": [
                2
            ],
            "REQUEST_DELTA": [
                6
            ],
            "REQUEST_TIME": [
                6
            ],
            "REQUEST_TIME_FORMATTED": [
                2
            ],
            "RES_DELIVER_PRICE": [
                13
            ],
            "RES_OFFICE_1C_ID": [
                6
            ],
            "RES_OFFICE_ID": [
                6
            ],
            "RES_STATUS": [
                91
            ],
            "RES_TIME": [
                6
            ],
            "RES_TIME_FORMATTED": [
                2
            ],
            "TIME_MODE": [
                182
            ],
            "TRANSPORT_TYPE": [
                2
            ],
            "VORDER_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "DeliveryComputed": {
            "CALCULATE_DESCRIPTION": [
                85
            ],
            "CALCULATE_ERRORS": [
                85
            ],
            "CALC_TIMESTAMP": [
                6
            ],
            "DELIVERY_DISCOUNT_PRICE": [
                2
            ],
            "DELIVERY_DISCOUNT_PRICE_FORMATED": [
                2
            ],
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "PERIOD_TEXT": [
                2
            ],
            "PRICE": [
                13
            ],
            "PRICE_FORMATED": [
                2
            ],
            "SERVICE": [
                57
            ],
            "__typename": [
                2
            ]
        },
        "DeliveryService": {
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "PARENT_ID": [
                6
            ],
            "TRANSPORT_TYPE": [
                58
            ],
            "__typename": [
                2
            ]
        },
        "DeliveryTransportTypeEnum": {},
        "DeliveryZone": {
            "ACTIVE": [
                1
            ],
            "CODE": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "PREVIEW_TEXT": [
                85,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "Discount": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "PROPERTIES": [
                62
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "DiscountModeEnum": {},
        "DiscountProps": {
            "ACTION_DISCOUNT_PERCENT": [
                6
            ],
            "ACTION_PRODUCT_IDS": [
                6
            ],
            "ACTION_PRODUCT_IDS_ENTITIES": [
                65
            ],
            "ACTION_SECTION_IDS": [
                2
            ],
            "CONDITION": [
                63
            ],
            "CONDITION_ORDER_PRICE_FROM": [
                6
            ],
            "CONDITION_TRANSPORT_TYPE": [
                2
            ],
            "DATE": [
                2
            ],
            "HOTEST": [
                1
            ],
            "NAME_TEMPLATE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "DiscountPropsCONDITION": {
            "DATE": [
                2
            ],
            "ORDER_PRICE_FROM": [
                6
            ],
            "TRANSPORT_TYPE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "DiscountTargetEnum": {},
        "Element": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ElementInput": {
            "CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ElementListFilterInput": {
            "ACTIVE": [
                1
            ],
            "CODE": [
                181
            ],
            "IBLOCK_CODE": [
                181
            ],
            "IBLOCK_ID": [
                84
            ],
            "IBLOCK_SECTION_ID": [
                84
            ],
            "ID": [
                84
            ],
            "NAME": [
                181
            ],
            "SORT": [
                84
            ],
            "__typename": [
                2
            ]
        },
        "ElementMeta": {
            "DESCRIPTION": [
                2
            ],
            "KEYWORDS": [
                2
            ],
            "PAGE_TITLE": [
                2
            ],
            "TITLE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ElementPropValueHtml": {
            "TEXT": [
                2
            ],
            "TYPE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ElementPropValueWithDesc": {
            "DESC": [
                2
            ],
            "VALUE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "EntityInfo": {
            "name": [
                2
            ],
            "role": [
                2
            ],
            "sectionPaths": [
                2
            ],
            "type": [
                2
            ],
            "urls": [
                72
            ],
            "__typename": [
                2
            ]
        },
        "EntityInfoUrls": {
            "index": [
                2
            ],
            "section": [
                2
            ],
            "view": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "EntityProp": {
            "CODE": [
                2
            ],
            "DESC": [
                85
            ],
            "FILE": [
                82
            ],
            "FILES": [
                82
            ],
            "ID": [
                6
            ],
            "MUL": [
                1
            ],
            "NAME": [
                2
            ],
            "TYPE": [
                2
            ],
            "VAL": [
                85
            ],
            "VAL_ENUM_ID": [
                85
            ],
            "VAL_ID": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "ErrorInterface": {
            "data": [
                85
            ],
            "fieldMessage": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                75
            ],
            "rel": [
                85
            ],
            "type": [
                76
            ],
            "on_AccessError": [
                0
            ],
            "on_CommonError": [
                39
            ],
            "on_ExternalServiceError": [
                77
            ],
            "on_FormError": [
                80
            ],
            "on_OtpError": [
                116
            ],
            "on_RateError": [
                142
            ],
            "__typename": [
                2
            ]
        },
        "ErrorNameEnum": {},
        "ErrorTypeEnum": {},
        "ExternalServiceError": {
            "authRedirect": [
                1
            ],
            "data": [
                85
            ],
            "fieldMessage": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                75
            ],
            "rel": [
                85
            ],
            "type": [
                76
            ],
            "__typename": [
                2
            ]
        },
        "FaqQuestion": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "FaqQuestionRecordset": {
            "info": [
                140
            ],
            "nodes": [
                78
            ],
            "__typename": [
                2
            ]
        },
        "FormError": {
            "data": [
                85
            ],
            "fieldLabel": [
                2
            ],
            "fieldMessage": [
                2
            ],
            "fieldName": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                75
            ],
            "rel": [
                85
            ],
            "type": [
                76
            ],
            "__typename": [
                2
            ]
        },
        "GeoObject": {
            "address_full": [
                2
            ],
            "address_original": [
                2
            ],
            "address_short": [
                2
            ],
            "area": [
                2
            ],
            "area_fias_id": [
                2
            ],
            "area_format": [
                2
            ],
            "area_original": [
                2
            ],
            "city": [
                2
            ],
            "city_fias_id": [
                2
            ],
            "city_format": [
                2
            ],
            "city_original": [
                2
            ],
            "district": [
                2
            ],
            "district_fias_id": [
                2
            ],
            "district_format": [
                2
            ],
            "district_original": [
                2
            ],
            "geo_lat": [
                13
            ],
            "geo_lon": [
                13
            ],
            "house": [
                2
            ],
            "house_fias_id": [
                2
            ],
            "house_format": [
                2
            ],
            "house_original": [
                2
            ],
            "region": [
                2
            ],
            "region_fias_id": [
                2
            ],
            "region_format": [
                2
            ],
            "region_original": [
                2
            ],
            "street": [
                2
            ],
            "street_fias_id": [
                2
            ],
            "street_format": [
                2
            ],
            "street_original": [
                2
            ],
            "street_path_full": [
                2
            ],
            "street_path_short": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Image": {
            "FILE_SIZE": [
                2
            ],
            "ID": [
                83
            ],
            "ORIGINAL_NAME": [
                2
            ],
            "SRC": [
                2
            ],
            "SRC_DEFAULT": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ID": {},
        "IntFilterInput": {
            "eq": [
                6
            ],
            "exists": [
                1
            ],
            "gt": [
                6
            ],
            "in": [
                6
            ],
            "lt": [
                6
            ],
            "not": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "Json": {},
        "ListValue": {
            "CODE": [
                2
            ],
            "ID": [
                6
            ],
            "VALUE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Menu": {
            "children": [
                88
            ],
            "code": [
                2
            ],
            "id": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "MenuItem": {
            "badge": [
                85
            ],
            "bgColor": [
                2
            ],
            "blank": [
                1
            ],
            "children": [
                88
            ],
            "color": [
                2
            ],
            "command": [
                38
            ],
            "dense": [
                1
            ],
            "disable": [
                1
            ],
            "display": [
                2
            ],
            "entityId": [
                2
            ],
            "entityType": [
                2
            ],
            "flat": [
                1
            ],
            "icon": [
                2
            ],
            "id": [
                2
            ],
            "image": [
                82
            ],
            "imageId": [
                6
            ],
            "infoLabel": [
                2
            ],
            "label": [
                2
            ],
            "loading": [
                1
            ],
            "native": [
                1
            ],
            "onClick": [
                85
            ],
            "outline": [
                1
            ],
            "params": [
                85
            ],
            "parent": [
                2
            ],
            "roles": [
                2
            ],
            "textColor": [
                2
            ],
            "url": [
                2
            ],
            "width": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "MenuItemMobile": {
            "action": [
                3
            ],
            "backgroundColor": [
                2
            ],
            "badge": [
                85
            ],
            "color": [
                2
            ],
            "condition": [
                45
            ],
            "icon": [
                2
            ],
            "id": [
                2
            ],
            "image": [
                82
            ],
            "imageId": [
                6
            ],
            "label": [
                2
            ],
            "labelColor": [
                2
            ],
            "link": [
                1
            ],
            "outline": [
                1
            ],
            "outlineColor": [
                2
            ],
            "outlineWidth": [
                6
            ],
            "params": [
                85
            ],
            "parent": [
                2
            ],
            "roles": [
                85
            ],
            "templatable": [
                1
            ],
            "templatableProps": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Message": {
            "actions": [
                88
            ],
            "category": [
                2
            ],
            "code": [
                2
            ],
            "data": [
                85
            ],
            "duration": [
                6
            ],
            "id": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                2
            ],
            "notify": [
                1
            ],
            "rel": [
                2
            ],
            "status": [
                2
            ],
            "title": [
                2
            ],
            "type": [
                91
            ],
            "__typename": [
                2
            ]
        },
        "MessageTypeEnum": {},
        "Mutation": {
            "catalog_product_order": [
                143,
                {
                    "elementId": [
                        6
                    ],
                    "model": [
                        85
                    ]
                }
            ],
            "company_office_update": [
                143,
                {
                    "id": [
                        6,
                        "Int!"
                    ],
                    "model": [
                        41,
                        "CompanyOfficeInput!"
                    ]
                }
            ],
            "company_vacancy_order": [
                143,
                {
                    "id": [
                        6
                    ],
                    "model": [
                        85
                    ]
                }
            ],
            "notice_pub_push_send_queue": [
                143
            ],
            "notice_pub_push_update_token": [
                143,
                {
                    "pushToken": [
                        2
                    ]
                }
            ],
            "notice_pub_sync_readed": [
                93,
                {
                    "ids": [
                        85
                    ]
                }
            ],
            "review_order_guest_review": [
                148,
                {
                    "orderServiceId": [
                        6
                    ],
                    "type": [
                        2
                    ]
                }
            ],
            "review_service_review": [
                150,
                {
                    "model": [
                        85
                    ]
                }
            ],
            "sale_client_card_create": [
                143,
                {
                    "model": [
                        66,
                        "ElementInput!"
                    ]
                }
            ],
            "sale_client_card_delete": [
                143,
                {
                    "id": [
                        6
                    ]
                }
            ],
            "sale_client_card_update": [
                143,
                {
                    "id": [
                        6,
                        "Int!"
                    ],
                    "model": [
                        66,
                        "ElementInput!"
                    ]
                }
            ],
            "sale_order_cancel": [
                152,
                {
                    "comment": [
                        2
                    ],
                    "hash": [
                        2
                    ],
                    "id": [
                        6
                    ],
                    "reason": [
                        2
                    ]
                }
            ],
            "sale_order_pay_online": [
                153,
                {
                    "hash": [
                        2
                    ],
                    "id": [
                        6
                    ],
                    "savePaymentType": [
                        1
                    ],
                    "type": [
                        2
                    ]
                }
            ],
            "sale_order_repeat": [
                155,
                {
                    "hash": [
                        2
                    ],
                    "id": [
                        6
                    ]
                }
            ],
            "sale_profile_calc_delivery": [
                156,
                {
                    "attrs": [
                        106
                    ],
                    "basket": [
                        85
                    ],
                    "profileId": [
                        6
                    ],
                    "timeMode": [
                        182
                    ],
                    "timeNeed": [
                        6
                    ]
                }
            ],
            "sale_profile_default": [
                157,
                {
                    "id": [
                        6
                    ]
                }
            ],
            "sale_profile_delete": [
                158,
                {
                    "id": [
                        6
                    ]
                }
            ],
            "sale_profile_save": [
                159,
                {
                    "attrs": [
                        106
                    ],
                    "id": [
                        6
                    ]
                }
            ],
            "sale_vorder_apply": [
                160,
                {
                    "order": [
                        200
                    ],
                    "params": [
                        85
                    ]
                }
            ],
            "sale_vorder_basket": [
                162,
                {
                    "action": [
                        2
                    ],
                    "build": [
                        85
                    ],
                    "order": [
                        200
                    ]
                }
            ],
            "sale_vorder_coupon": [
                164,
                {
                    "action": [
                        2
                    ],
                    "couponCode": [
                        2
                    ],
                    "order": [
                        200
                    ]
                }
            ],
            "sale_vorder_new": [
                166
            ],
            "sale_vorder_reserve": [
                168,
                {
                    "order": [
                        200
                    ]
                }
            ],
            "sale_vorder_submit": [
                170,
                {
                    "order": [
                        200
                    ]
                }
            ],
            "sale_vorder_sync": [
                172,
                {
                    "order": [
                        200
                    ]
                }
            ],
            "user_app_client": [
                143,
                {
                    "debugParams": [
                        85
                    ],
                    "mobilePushToken": [
                        2
                    ],
                    "webPushToken": [
                        2
                    ]
                }
            ],
            "user_auth_create_sa": [
                143,
                {
                    "clientIds": [
                        85
                    ],
                    "code": [
                        2
                    ],
                    "phone": [
                        2
                    ]
                }
            ],
            "user_auth_login_confirm": [
                186,
                {
                    "captcha": [
                        33
                    ],
                    "clientId": [
                        2
                    ],
                    "code": [
                        2
                    ],
                    "confirmMode": [
                        2
                    ],
                    "disableBasketTransfer": [
                        1
                    ],
                    "phone": [
                        2
                    ],
                    "pushToken": [
                        2
                    ],
                    "sid": [
                        2
                    ]
                }
            ],
            "user_auth_login_request": [
                188,
                {
                    "captcha": [
                        33
                    ],
                    "confirmMode": [
                        2
                    ],
                    "phone": [
                        2
                    ]
                }
            ],
            "user_auth_login_start": [
                190,
                {
                    "captcha": [
                        33
                    ],
                    "phone": [
                        2
                    ]
                }
            ],
            "user_logout": [
                143
            ],
            "user_profile_all_filled": [
                143
            ],
            "user_profile_birthday": [
                143,
                {
                    "birthday": [
                        2
                    ]
                }
            ],
            "user_profile_child": [
                143,
                {
                    "action": [
                        2
                    ],
                    "child": [
                        194
                    ]
                }
            ],
            "user_profile_email": [
                143,
                {
                    "action": [
                        2
                    ],
                    "code": [
                        2
                    ],
                    "email": [
                        2
                    ]
                }
            ],
            "user_profile_name": [
                143,
                {
                    "name": [
                        2
                    ]
                }
            ],
            "user_profile_save": [
                143,
                {
                    "form": [
                        85
                    ]
                }
            ],
            "__typename": [
                2
            ]
        },
        "NoticePubSyncReadedResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                37
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "Offer": {
            "ACTIONS_MOBILE": [
                89
            ],
            "ACTIONS_WEB": [
                88
            ],
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "BANNER_HOR_DESKTOP": [
                82
            ],
            "BANNER_HOR_MOBILE": [
                82
            ],
            "BANNER_INTERNAL_TEXT": [
                2
            ],
            "BANNER_SQUARE": [
                82
            ],
            "CODE": [
                2
            ],
            "CONTENT_IMAGE": [
                82
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "DISCOUNT_ID": [
                6
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "IS_HOT": [
                1
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "OFFER_NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SLIDES": [
                96
            ],
            "SORT": [
                6
            ],
            "STARTUP_SHOW": [
                2
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "VARS": [
                85
            ],
            "VID": [
                2
            ],
            "VIEW_MODE": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OfferRecordset": {
            "info": [
                140
            ],
            "nodes": [
                94
            ],
            "__typename": [
                2
            ]
        },
        "OfferSlide": {
            "BG_COLOR": [
                2
            ],
            "CODE": [
                2
            ],
            "CONTENT_HTML": [
                2
            ],
            "CONTENT_IMAGE": [
                82
            ],
            "CONTENT_TYPE": [
                2
            ],
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OfficeFilterInput": {
            "ACTIVE": [
                1
            ],
            "CODE": [
                181
            ],
            "IBLOCK_CODE": [
                181
            ],
            "IBLOCK_ID": [
                84
            ],
            "IBLOCK_SECTION_ID": [
                84
            ],
            "ID": [
                84
            ],
            "NAME": [
                181
            ],
            "SORT": [
                84
            ],
            "__typename": [
                2
            ]
        },
        "OfficeRecordset": {
            "info": [
                140
            ],
            "nodes": [
                40
            ],
            "__typename": [
                2
            ]
        },
        "Order": {
            "ACCESS_HASH": [
                2
            ],
            "ACCOUNT_NUMBER": [
                2
            ],
            "ACTIONS": [
                85
            ],
            "ADDRESS_FOR_1C": [
                2
            ],
            "ATTR": [
                105
            ],
            "ATTRS": [
                100
            ],
            "BASKET": [
                15
            ],
            "BONUSES": [
                6
            ],
            "BUYER_NAME": [
                2
            ],
            "CANCEL_REASONS": [
                107
            ],
            "CONTRACT_NUM": [
                2
            ],
            "COUPONS": [
                51
            ],
            "COURIER_STATE": [
                52
            ],
            "CSTATUS_COLOR": [
                2
            ],
            "CSTATUS_ID": [
                2
            ],
            "CSTATUS_NAME": [
                2
            ],
            "DATE_FORMATTED": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "DATE_INSERT": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "DATE_PAYED": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "DATE_TIME_FORMATTED": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "DATE_UPDATE": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "DELIVERY": [
                57
            ],
            "DELIVERY_ADDRESS_FULL": [
                2
            ],
            "DELIVERY_CALCULATED": [
                1
            ],
            "DELIVERY_DATETIME": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "DELIVERY_DEPARTMENT": [
                40
            ],
            "DELIVERY_FREE_FROM_PRICE": [
                6
            ],
            "DELIVERY_ID": [
                6
            ],
            "DISCOUNT_PERCENT": [
                13
            ],
            "DISCOUNT_REASON": [
                2
            ],
            "EDU_GROUP_NUM": [
                2
            ],
            "ID": [
                6
            ],
            "IS_ACTIVE": [
                1
            ],
            "IS_CANCELED": [
                1
            ],
            "IS_CAN_CANCEL": [
                1
            ],
            "IS_CAN_PAY": [
                1
            ],
            "IS_CAN_PAY_BILL": [
                1
            ],
            "IS_CAN_PAY_ONLINE": [
                1
            ],
            "IS_FINISHED": [
                1
            ],
            "IS_PAID": [
                1
            ],
            "PAYMENTS": [
                121
            ],
            "PAYSYSTEM": [
                123
            ],
            "PAYSYSTEM_ID": [
                6
            ],
            "PAYSYSTEM_IS_ONLINE": [
                1
            ],
            "PAY_LINK": [
                2
            ],
            "PERSON_TYPE_ID": [
                6
            ],
            "PICKUP_DEPARTMENT": [
                40
            ],
            "PRICE": [
                13
            ],
            "PRICE_BASKET": [
                13
            ],
            "PRICE_BASKET_BASE": [
                13
            ],
            "PRICE_DELIVERY": [
                13
            ],
            "PRICE_DELIVERY_BASE": [
                13
            ],
            "PRICE_DISCOUNT": [
                13
            ],
            "PRICE_PAY": [
                13
            ],
            "PRICE_PAY_BASE": [
                13
            ],
            "PRICE_TOTAL": [
                13
            ],
            "PRICE_TOTAL_BASE": [
                13
            ],
            "SCOPE": [
                114
            ],
            "SCOPE_ENTITY": [
                38
            ],
            "SECRET_URL": [
                2
            ],
            "SERVICE_ID": [
                6
            ],
            "STATUS": [
                115
            ],
            "STATUS_COLOR": [
                2
            ],
            "STATUS_ID": [
                2
            ],
            "STATUS_NAME": [
                2
            ],
            "STUDENT_FIO": [
                2
            ],
            "SYNCED": [
                1
            ],
            "TS": [
                2
            ],
            "URL": [
                2
            ],
            "USER": [
                183
            ],
            "USER_DESCRIPTION": [
                2
            ],
            "USER_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "OrderAttribute": {
            "CODE": [
                101
            ],
            "DEFAULT_VALUE": [
                85
            ],
            "KIND": [
                102
            ],
            "NAME": [
                2
            ],
            "OPTIONS": [
                103
            ],
            "TYPE": [
                104
            ],
            "VALUE": [
                85
            ],
            "VALUE_VIEW": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "OrderAttributeCodeEnum": {},
        "OrderAttributeKindEnum": {},
        "OrderAttributeOption": {
            "DESCRIPTION": [
                2
            ],
            "DISABLE": [
                1
            ],
            "ICON": [
                2
            ],
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "NAME_SHORT": [
                2
            ],
            "SORT": [
                6
            ],
            "VALUE": [
                85
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OrderAttributeTypeEnum": {},
        "OrderAttributesValue": {
            "ADDRESS": [
                2
            ],
            "ADDRESS_IS_CUSTOM": [
                1
            ],
            "ADDRESS_IS_CUSTOM_STRING": [
                2
            ],
            "ADDRESS_SOURCE": [
                2
            ],
            "APP_VERSION": [
                2
            ],
            "BENEFIT_TYPE": [
                2
            ],
            "BENEFIT_TYPE_STRING": [
                2
            ],
            "BONUSES": [
                6
            ],
            "CASH_SUM": [
                6
            ],
            "CITY": [
                2
            ],
            "CITY_FIAS_ID": [
                2
            ],
            "COMMENT": [
                2
            ],
            "DATA": [
                108
            ],
            "DATE": [
                2
            ],
            "DATETIME": [
                6
            ],
            "DELIVERY_DEPARTMENT": [
                6
            ],
            "DELIVERY_FREE_FROM_PRICE": [
                2
            ],
            "DELIVERY_FREE_UPDATED_TIME": [
                6
            ],
            "DELIVERY_ID": [
                6
            ],
            "DELIVERY_ID_STRING": [
                2
            ],
            "DELIVERY_PRICE": [
                6
            ],
            "DEPARTMENT_SERVICE_ID": [
                6
            ],
            "DETAILS": [
                1
            ],
            "DETAILS_STRING": [
                2
            ],
            "DISCOUNT_PERCENT": [
                6
            ],
            "DISCOUNT_REASON": [
                2
            ],
            "EMAIL": [
                2
            ],
            "ENTRANCE": [
                2
            ],
            "FIO": [
                2
            ],
            "FLAT": [
                2
            ],
            "FLOOR": [
                2
            ],
            "GIFTS_LIST": [
                2
            ],
            "HOUSE": [
                2
            ],
            "HOUSE_COORDS": [
                49
            ],
            "HOUSE_COORDS_STRING": [
                2
            ],
            "HOUSE_FIAS_ID": [
                2
            ],
            "INTERCOM": [
                2
            ],
            "LIFT": [
                1
            ],
            "LIFT_STRING": [
                2
            ],
            "LOCATION": [
                2
            ],
            "NEED_CONFIRM": [
                1
            ],
            "NEED_CONFIRM_STRING": [
                2
            ],
            "PAYMENT_TYPE": [
                9
            ],
            "PAYMENT_TYPE_STRING": [
                2
            ],
            "PAY_SYSTEM_ID": [
                6
            ],
            "PAY_SYSTEM_ID_STRING": [
                2
            ],
            "PERSONS_NUMBER": [
                6
            ],
            "PHONE": [
                2
            ],
            "PICKUP_DEPARTMENT": [
                6
            ],
            "PRIVATE_HOUSE": [
                1
            ],
            "PRIVATE_HOUSE_STRING": [
                2
            ],
            "PROFILE_COMMENT": [
                2
            ],
            "PROFILE_DEFAULT": [
                1
            ],
            "PROFILE_DEFAULT_STRING": [
                2
            ],
            "PROFILE_ID": [
                6
            ],
            "PROMOCODE": [
                2
            ],
            "RECEIVER_ANOTHER": [
                1
            ],
            "RECEIVER_ANOTHER_STRING": [
                2
            ],
            "RECEIVER_NAME": [
                2
            ],
            "RECEIVER_PHONE": [
                2
            ],
            "RESERVE_AVAILABLE_TIME": [
                6
            ],
            "RESERVE_NEED_TIME": [
                6
            ],
            "RESERVE_REQUEST_TIME": [
                6
            ],
            "RESERVE_STATUS": [
                2
            ],
            "RESERVE_SUCCESS_HASH": [
                2
            ],
            "RESERVE_SUCCESS_REQUEST_TIME": [
                6
            ],
            "ROISTAT": [
                2
            ],
            "SERVICE_SEND": [
                1
            ],
            "SERVICE_SEND_START": [
                6
            ],
            "SERVICE_SEND_STRING": [
                2
            ],
            "SETTLEMENT": [
                2
            ],
            "SETTLEMENT_FIAS_ID": [
                2
            ],
            "SOURCE": [
                2
            ],
            "STREET": [
                2
            ],
            "STREET_COORDS": [
                49
            ],
            "STREET_COORDS_STRING": [
                2
            ],
            "STREET_FIAS_ID": [
                2
            ],
            "STREET_PATH": [
                2
            ],
            "STRUCT": [
                85
            ],
            "TEST_TIME": [
                6
            ],
            "TEST_TIME_STRING": [
                2
            ],
            "TIME": [
                2
            ],
            "TIME_MODE": [
                10
            ],
            "TIME_MODE_STRING": [
                2
            ],
            "TRANSPORT_TYPE": [
                11
            ],
            "USER_DESCRIPTION": [
                2
            ],
            "UUID": [
                2
            ],
            "VORDER_ID": [
                6
            ],
            "WITH_OPERATOR": [
                1
            ],
            "WITH_OPERATOR_STRING": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OrderAttributesValueInput": {
            "ADDRESS": [
                2
            ],
            "ADDRESS_IS_CUSTOM": [
                1
            ],
            "ADDRESS_SOURCE": [
                2
            ],
            "APP_VERSION": [
                2
            ],
            "BENEFIT_TYPE": [
                2
            ],
            "BONUSES": [
                6
            ],
            "CASH_SUM": [
                6
            ],
            "CITY": [
                2
            ],
            "CITY_FIAS_ID": [
                2
            ],
            "COMMENT": [
                2
            ],
            "DATA": [
                109
            ],
            "DATE": [
                2
            ],
            "DATETIME": [
                6
            ],
            "DELIVERY_DEPARTMENT": [
                6
            ],
            "DELIVERY_FREE_FROM_PRICE": [
                2
            ],
            "DELIVERY_FREE_UPDATED_TIME": [
                6
            ],
            "DELIVERY_ID": [
                6
            ],
            "DELIVERY_PRICE": [
                6
            ],
            "DEPARTMENT_SERVICE_ID": [
                6
            ],
            "DETAILS": [
                1
            ],
            "DISCOUNT_PERCENT": [
                6
            ],
            "DISCOUNT_REASON": [
                2
            ],
            "EMAIL": [
                2
            ],
            "ENTRANCE": [
                2
            ],
            "FIO": [
                2
            ],
            "FLAT": [
                2
            ],
            "FLOOR": [
                2
            ],
            "GIFTS_LIST": [
                2
            ],
            "HOUSE": [
                2
            ],
            "HOUSE_COORDS": [
                50
            ],
            "HOUSE_FIAS_ID": [
                2
            ],
            "INTERCOM": [
                2
            ],
            "LIFT": [
                1
            ],
            "LOCATION": [
                2
            ],
            "NEED_CONFIRM": [
                1
            ],
            "PAYMENT_TYPE": [
                9
            ],
            "PAY_SYSTEM_ID": [
                6
            ],
            "PERSONS_NUMBER": [
                6
            ],
            "PHONE": [
                2
            ],
            "PICKUP_DEPARTMENT": [
                6
            ],
            "PRIVATE_HOUSE": [
                1
            ],
            "PROFILE_COMMENT": [
                2
            ],
            "PROFILE_DEFAULT": [
                1
            ],
            "PROFILE_ID": [
                6
            ],
            "PROMOCODE": [
                2
            ],
            "RECEIVER_ANOTHER": [
                1
            ],
            "RECEIVER_NAME": [
                2
            ],
            "RECEIVER_PHONE": [
                2
            ],
            "RESERVE_AVAILABLE_TIME": [
                6
            ],
            "RESERVE_NEED_TIME": [
                6
            ],
            "RESERVE_REQUEST_TIME": [
                6
            ],
            "RESERVE_STATUS": [
                2
            ],
            "RESERVE_SUCCESS_HASH": [
                2
            ],
            "RESERVE_SUCCESS_REQUEST_TIME": [
                6
            ],
            "ROISTAT": [
                2
            ],
            "SERVICE_SEND": [
                1
            ],
            "SERVICE_SEND_START": [
                6
            ],
            "SETTLEMENT": [
                2
            ],
            "SETTLEMENT_FIAS_ID": [
                2
            ],
            "SOURCE": [
                2
            ],
            "STREET": [
                2
            ],
            "STREET_COORDS": [
                50
            ],
            "STREET_FIAS_ID": [
                2
            ],
            "STREET_PATH": [
                2
            ],
            "STRUCT": [
                2
            ],
            "TEST_TIME": [
                6
            ],
            "TIME": [
                2
            ],
            "TIME_MODE": [
                10
            ],
            "TRANSPORT_TYPE": [
                11
            ],
            "USER_DESCRIPTION": [
                2
            ],
            "UUID": [
                2
            ],
            "VORDER_ID": [
                6
            ],
            "WITH_OPERATOR": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "OrderCancelReason": {
            "CODE": [
                2
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OrderData": {
            "paramArray": [
                85
            ],
            "paramInt": [
                6
            ],
            "paramString": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OrderDataInput": {
            "paramArray": [
                85
            ],
            "paramInt": [
                6
            ],
            "paramString": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OrderFilter": {
            "ACCOUNT_NUMBER": [
                181
            ],
            "CANCELED": [
                1
            ],
            "DATE_DAY": [
                6
            ],
            "DATE_MONTH": [
                6
            ],
            "DATE_PAYED_DAY": [
                6
            ],
            "DATE_PAYED_MONTH": [
                6
            ],
            "DATE_PAYED_YEAR": [
                6
            ],
            "DATE_YEAR": [
                6
            ],
            "ELEMENT_NAME": [
                181
            ],
            "EMAIL": [
                181
            ],
            "ID": [
                84
            ],
            "MODE": [
                2
            ],
            "PAYED": [
                1
            ],
            "PAY_SYSTEM_ID": [
                84
            ],
            "PHONE": [
                181
            ],
            "PROP_CONTRACT_NUM": [
                181
            ],
            "PROP_EDU_GROUP_NUM": [
                181
            ],
            "PROP_EMAIL": [
                181
            ],
            "PROP_FIO": [
                181
            ],
            "PROP_PHONE": [
                181
            ],
            "PROP_PRODUCT_SECTIONS": [
                181
            ],
            "PROP_STUDENT_FIO": [
                181
            ],
            "SECTION_ID": [
                84
            ],
            "STATUS_ID": [
                181
            ],
            "USER_ID": [
                84
            ],
            "__typename": [
                2
            ]
        },
        "OrderProfile": {
            "ATTR": [
                112
            ],
            "ATTRS": [
                100
            ],
            "CAPTION": [
                2
            ],
            "COMPANY_ID": [
                6
            ],
            "COORDS": [
                49
            ],
            "DELIVERY_FREE_FROM_PRICE": [
                6
            ],
            "ID": [
                6
            ],
            "IS_DEFAULT": [
                1
            ],
            "NAME": [
                2
            ],
            "PERSON_TYPE": [
                124
            ],
            "PERSON_TYPE_ID": [
                6
            ],
            "USER": [
                183
            ],
            "USER_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "OrderProfileAttributesValue": {
            "ADDRESS": [
                2
            ],
            "ADDRESS_IS_CUSTOM": [
                1
            ],
            "ADDRESS_SOURCE": [
                2
            ],
            "CITY": [
                2
            ],
            "CITY_FIAS_ID": [
                2
            ],
            "ENTRANCE": [
                2
            ],
            "FLAT": [
                2
            ],
            "FLOOR": [
                2
            ],
            "HOUSE": [
                2
            ],
            "HOUSE_COORDS": [
                49
            ],
            "HOUSE_FIAS_ID": [
                2
            ],
            "LIFT": [
                1
            ],
            "PRIVATE_HOUSE": [
                1
            ],
            "PROFILE_COMMENT": [
                2
            ],
            "PROFILE_DEFAULT": [
                1
            ],
            "RECEIVER_ANOTHER": [
                1
            ],
            "RECEIVER_NAME": [
                2
            ],
            "RECEIVER_PHONE": [
                2
            ],
            "SETTLEMENT": [
                2
            ],
            "SETTLEMENT_FIAS_ID": [
                2
            ],
            "STREET": [
                2
            ],
            "STREET_COORDS": [
                49
            ],
            "STREET_FIAS_ID": [
                2
            ],
            "STREET_PATH": [
                2
            ],
            "STRUCT": [
                85
            ],
            "TEST_TIME": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "OrderProfileRecordset": {
            "info": [
                140
            ],
            "nodes": [
                111
            ],
            "__typename": [
                2
            ]
        },
        "OrderScope": {
            "CONTRACT_NUM": [
                2
            ],
            "ENTITY_ID": [
                6
            ],
            "ENTITY_TYPE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OrderStatus": {
            "COLOR": [
                2
            ],
            "ID": [
                2
            ],
            "NAME": [
                2
            ],
            "SORT": [
                6
            ],
            "TYPE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "OtpError": {
            "data": [
                85
            ],
            "fieldMessage": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                75
            ],
            "param": [
                6
            ],
            "rel": [
                85
            ],
            "type": [
                76
            ],
            "__typename": [
                2
            ]
        },
        "Page": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CONTENT_CHUNKS": [
                118
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATA_CHUNKS": [
                119
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "PageContentChunk": {
            "CODE": [
                2
            ],
            "GROUP": [
                2
            ],
            "NAME": [
                2
            ],
            "TYPE": [
                2
            ],
            "VALUE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "PageDataChunk": {
            "CODE": [
                2
            ],
            "TYPE": [
                2
            ],
            "VALUE": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "PageRecordset": {
            "info": [
                140
            ],
            "nodes": [
                117
            ],
            "__typename": [
                2
            ]
        },
        "Payment": {
            "DATE_PAID": [
                85,
                {
                    "format": [
                        2
                    ],
                    "formatType": [
                        54
                    ]
                }
            ],
            "ID": [
                6
            ],
            "IS_PAID": [
                1
            ],
            "ORDER_ID": [
                2
            ],
            "ORDER_URL": [
                2
            ],
            "PAYSYSTEM": [
                123
            ],
            "PAYSYSTEM_ID": [
                6
            ],
            "PAY_NAV": [
                85
            ],
            "PS_INVOICE_ID": [
                2
            ],
            "PS_STATUS": [
                2
            ],
            "PS_STATUS_CODE": [
                2
            ],
            "PS_STATUS_ID": [
                2
            ],
            "PS_STATUS_NAME": [
                2
            ],
            "SUM": [
                13
            ],
            "SUM_PAID": [
                13
            ],
            "__typename": [
                2
            ]
        },
        "PaymentType": {
            "CODE": [
                2
            ],
            "ICON": [
                2
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Paysystem": {
            "CODE": [
                2
            ],
            "DESCRIPTION": [
                2
            ],
            "ID": [
                6
            ],
            "IS_BILL": [
                1
            ],
            "IS_INNER": [
                1
            ],
            "IS_ONLINE": [
                1
            ],
            "IS_ONLINE_DELAYED": [
                1
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "PersonType": {
            "CODE": [
                2
            ],
            "ID": [
                6
            ],
            "IS_COMPANY": [
                1
            ],
            "NAME": [
                2
            ],
            "RESTRICTED": [
                1
            ],
            "SORT": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "Product": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "BENEFITS": [
                126
            ],
            "BUILD": [
                46
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "FLAGS": [
                128
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "IMAGE": [
                82
            ],
            "IS_SALE_SPECIAL": [
                1
            ],
            "MEASURE": [
                129
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "OFFERS": [
                125
            ],
            "OFFER_PARENT_ELEMENT": [
                125
            ],
            "PARENT": [
                125
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "PRICE": [
                130
            ],
            "PROPERTIES": [
                131
            ],
            "ROOT_SECTION": [
                177
            ],
            "SALES_COUNT": [
                6
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SET_ITEMS": [
                137
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                138
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "WEIGHT": [
                6
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ProductBenefit": {
            "IS_GIFT": [
                1
            ],
            "PRODUCT": [
                125
            ],
            "PRODUCT_ID": [
                6
            ],
            "QUANTITY": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "ProductFilterInput": {
            "ACTIVE": [
                1
            ],
            "CODE": [
                181
            ],
            "IBLOCK_CODE": [
                181
            ],
            "IBLOCK_ID": [
                84
            ],
            "IBLOCK_SECTION_ID": [
                84
            ],
            "ID": [
                84
            ],
            "NAME": [
                181
            ],
            "SORT": [
                84
            ],
            "__typename": [
                2
            ]
        },
        "ProductFlag": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ProductMeasure": {
            "NAME": [
                2
            ],
            "RATIO": [
                13
            ],
            "__typename": [
                2
            ]
        },
        "ProductPrice": {
            "DISCOUNTED": [
                13
            ],
            "DISCOUNT_PERCENT": [
                13
            ],
            "PRICE": [
                13
            ],
            "__typename": [
                2
            ]
        },
        "ProductProps": {
            "ADDITIVES": [
                6
            ],
            "ADDITIVES_ENTITIES": [
                65
            ],
            "AVAILABLE_SCHEDULE": [
                132
            ],
            "BELKI": [
                2
            ],
            "BENEFITS": [
                133
            ],
            "COMPONENT_IS": [
                86
            ],
            "FLAG_ITEMS": [
                6
            ],
            "FLAG_ITEMS_ENTITIES": [
                65
            ],
            "HOLIDAY": [
                86
            ],
            "IS_HIT": [
                1
            ],
            "KKAL": [
                6
            ],
            "NEW": [
                86
            ],
            "PHOTOV2": [
                82
            ],
            "ROLLS": [
                6
            ],
            "ROLLS_ENTITIES": [
                65
            ],
            "SALE_SPECIAL": [
                1
            ],
            "SERVICE_ID": [
                2
            ],
            "SET_ITEMS": [
                134
            ],
            "SOSTAV": [
                70
            ],
            "TAGS": [
                6
            ],
            "TAGS_ENTITIES": [
                65
            ],
            "UGLEVODY": [
                2
            ],
            "UPSALE_ELEMENTS": [
                6
            ],
            "UPSALE_ELEMENTS_ENTITY": [
                65
            ],
            "UPSALE_SECTIONS": [
                2
            ],
            "WEIGHT": [
                2
            ],
            "ZHIRY": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ProductPropsAVAILABLE_SCHEDULE": {
            "DAY": [
                86
            ],
            "FROM": [
                2
            ],
            "TO": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ProductPropsBENEFITS": {
            "IS_GIFT": [
                1
            ],
            "PRODUCT": [
                6
            ],
            "PRODUCT_ENTITY": [
                65
            ],
            "QUANTITY": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "ProductPropsSET_ITEMS": {
            "PRODUCT_ID": [
                6
            ],
            "PRODUCT_ID_ENTITY": [
                65
            ],
            "QUANTITY": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ProductSection": {
            "ACTIVE": [
                1
            ],
            "CHILDREN": [
                177
            ],
            "CODE": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DEPTH_LEVEL": [
                6
            ],
            "DESCRIPTION": [
                2
            ],
            "DESCRIPTION_TYPE": [
                2
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "ELEMENT_CNT": [
                6
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_EXTERNAL_ID": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_TYPE_ID": [
                2
            ],
            "ID": [
                6
            ],
            "LEFT_MARGIN": [
                6
            ],
            "META": [
                68
            ],
            "NAME": [
                2
            ],
            "PARENT": [
                135
            ],
            "PARENTS": [
                135
            ],
            "PICTURE": [
                82
            ],
            "PROPERTIES": [
                136
            ],
            "REPLACE_LINK": [
                2
            ],
            "RIGHT_MARGIN": [
                6
            ],
            "SEARCHABLE_CONTENT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "SECTION_PAGE_URL": [
                2
            ],
            "SORT": [
                6
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "__typename": [
                2
            ]
        },
        "ProductSectionProps": {
            "CATEGORY_CLASS": [
                2
            ],
            "DATA": [
                6
            ],
            "DATA_FORMATTED": [
                2
            ],
            "DAY": [
                6
            ],
            "ENUM": [
                58
            ],
            "FLOAT": [
                13
            ],
            "HINT": [
                2
            ],
            "HTML": [
                2
            ],
            "INT": [
                6
            ],
            "LIST": [
                86
            ],
            "LOGIC": [
                1
            ],
            "MENU_BG_COLOR": [
                2
            ],
            "MENU_COLOR": [
                2
            ],
            "MENU_FOCUSED_ICON": [
                82
            ],
            "MENU_ICON": [
                82
            ],
            "M_CARD": [
                2
            ],
            "NAV_TITLE": [
                2
            ],
            "NAV_URL": [
                2
            ],
            "REPLACE_LINK": [
                2
            ],
            "SEARCH_IN": [
                2
            ],
            "UPSALE_SECTIONS": [
                6
            ],
            "UPSALE_SECTIONS_ENTITY": [
                177
            ],
            "__typename": [
                2
            ]
        },
        "ProductSetItem": {
            "PRODUCT": [
                125
            ],
            "PRODUCT_ID": [
                6
            ],
            "QUANTITY": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "ProductTag": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Query": {
            "camera_element_list": [
                202,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "catalog_product_all": [
                125,
                {
                    "filter": [
                        127
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "catalog_product_fav_list": [
                125,
                {
                    "filter": [
                        127
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "catalog_product_fav_list2": [
                125,
                {
                    "type": [
                        2
                    ]
                }
            ],
            "catalog_product_list": [
                125,
                {
                    "filter": [
                        127
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "catalog_product_recordset": [
                8,
                {
                    "filter": [
                        127
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "catalog_product_search": [
                174,
                {
                    "elementDetail": [
                        1
                    ],
                    "query": [
                        2
                    ]
                }
            ],
            "catalog_product_search_new": [
                125,
                {
                    "elementDetail": [
                        1
                    ],
                    "filterSuggestion": [
                        85
                    ],
                    "query": [
                        2
                    ]
                }
            ],
            "catalog_product_section_list": [
                135,
                {
                    "filter": [
                        178
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "catalog_product_single": [
                125,
                {
                    "filter": [
                        127
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "company_office_list": [
                40,
                {
                    "filter": [
                        97
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "company_office_recordset": [
                98,
                {
                    "filter": [
                        97
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "company_office_single": [
                40,
                {
                    "filter": [
                        97
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "company_vacancy_list": [
                44,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "company_vacancy_recordset": [
                197,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "company_vacancy_single": [
                44,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "entity_info_list": [
                71
            ],
            "faq_element_list": [
                78,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "faq_element_recordset": [
                79,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "faq_element_single": [
                78,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "geo_geocoder_location_by_address": [
                81,
                {
                    "address": [
                        2
                    ]
                }
            ],
            "geo_geocoder_locations_by_coords": [
                81,
                {
                    "lat": [
                        13
                    ],
                    "lon": [
                        13
                    ]
                }
            ],
            "list": [
                36
            ],
            "menu_menus": [
                87
            ],
            "notice_pub_list": [
                37
            ],
            "offer_common_list": [
                94
            ],
            "offer_list": [
                94,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "offer_recordset": [
                95,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "offer_single": [
                94,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "offer_user_list": [
                94
            ],
            "page_list": [
                117,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "page_recordset": [
                120,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "page_route": [
                117,
                {
                    "URL": [
                        2
                    ]
                }
            ],
            "page_single": [
                117,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "review_list": [
                147,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_bonus_level_list": [
                31,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_client_card_apply_by_phone": [
                151,
                {
                    "phone": [
                        2
                    ]
                }
            ],
            "sale_client_card_fetch": [
                151,
                {
                    "isScope": [
                        1
                    ],
                    "refetch": [
                        1
                    ]
                }
            ],
            "sale_client_card_list": [
                151,
                {
                    "filter": [
                        34
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_client_card_recordset": [
                35,
                {
                    "filter": [
                        34
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_client_card_single": [
                151,
                {
                    "filter": [
                        34
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_delivery_zones": [
                59
            ],
            "sale_order_active_list": [
                99,
                {
                    "filter": [
                        110
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_order_ensure_payment": [
                121,
                {
                    "hash": [
                        2
                    ],
                    "id": [
                        6
                    ],
                    "paymentId": [
                        6
                    ]
                }
            ],
            "sale_order_statuses": [
                115
            ],
            "sale_profile_list": [
                111,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_profile_recordset": [
                113,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "nocache": [
                        1
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_profile_single": [
                111,
                {
                    "filter": [
                        67
                    ],
                    "id": [
                        6
                    ],
                    "nav": [
                        141
                    ],
                    "where": [
                        85
                    ]
                }
            ],
            "sale_vorder_basket_products": [
                125
            ],
            "sale_vorder_current": [
                199,
                {
                    "check": [
                        1
                    ]
                }
            ],
            "sale_vorder_summary": [
                201
            ],
            "search_suggestions": [
                175,
                {
                    "query": [
                        2
                    ]
                }
            ],
            "search_suggestions_popular": [
                175
            ],
            "user_app_client": [
                5
            ],
            "user_fetch": [
                183,
                {
                    "sessionWrite": [
                        1
                    ]
                }
            ],
            "user_session": [
                196
            ],
            "__typename": [
                2
            ]
        },
        "QueryInfo": {
            "limit": [
                6
            ],
            "nextPage": [
                6
            ],
            "page": [
                6
            ],
            "pages": [
                6
            ],
            "total": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "QueryNavInput": {
            "asc": [
                1
            ],
            "limit": [
                6
            ],
            "page": [
                6
            ],
            "postLimit": [
                6
            ],
            "postSort": [
                2
            ],
            "postSortAsc": [
                1
            ],
            "sort": [
                2
            ],
            "sortAscending": [
                1
            ],
            "sortField": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "RateError": {
            "data": [
                85
            ],
            "fieldMessage": [
                2
            ],
            "message": [
                2
            ],
            "messages": [
                2
            ],
            "name": [
                75
            ],
            "rel": [
                85
            ],
            "ttl": [
                6
            ],
            "type": [
                76
            ],
            "__typename": [
                2
            ]
        },
        "Response": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                85
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "ResponseRate": {
            "limited": [
                1
            ],
            "ttl": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "ResponseRedirect": {
            "restricted": [
                1
            ],
            "ttl": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "ResponseState": {
            "events": [
                85
            ],
            "messages": [
                90
            ],
            "rate": [
                144
            ],
            "redirect": [
                145
            ],
            "__typename": [
                2
            ]
        },
        "Review": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CHILDREN": [
                147
            ],
            "CODE": [
                2
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "ELEMENT": [
                65
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "ORDER": [
                99
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ReviewOrderGuestReviewResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                149
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "ReviewOrderGuestReviewResultPayload": {
            "departmentId": [
                6
            ],
            "departmentName": [
                2
            ],
            "redirectUrl": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "ReviewServiceReviewResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                147
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleClientCard": {
            "BONUSES": [
                6
            ],
            "BONUSES_EXPIRE": [
                6
            ],
            "BONUSES_EXPIRE_FORMATTED": [
                2
            ],
            "BONUSES_PERCENT": [
                13
            ],
            "CLIENT_PHONE": [
                2
            ],
            "DISCOUNTS": [
                60
            ],
            "DIS_FIRST_ORDER": [
                13
            ],
            "DIS_SELF_PICKUP": [
                13
            ],
            "EXPIRED": [
                1
            ],
            "FETCHED": [
                1
            ],
            "FETCHED_ACTUAL": [
                1
            ],
            "FETCHED_TIME": [
                6
            ],
            "FETCHED_TIME_FORMATTED": [
                2
            ],
            "ID": [
                6
            ],
            "LEVEL": [
                31
            ],
            "LEVEL_CODE": [
                2
            ],
            "LEVEL_NAME": [
                2
            ],
            "MONTH_SPENT": [
                13
            ],
            "TRANSPORT": [
                58
            ],
            "USER_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "SaleOrderCancelResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                99
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleOrderPayOnlineResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                154
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleOrderPayOnlineResultPayload": {
            "action": [
                4
            ],
            "order": [
                99
            ],
            "__typename": [
                2
            ]
        },
        "SaleOrderRepeatResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                99
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleProfileCalcDeliveryResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                55
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleProfileDefaultResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                111
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleProfileDeleteResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                6
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleProfileSaveResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                111
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderApplyResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                161
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderApplyResultPayload": {
            "vorder": [
                198
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderBasketResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                163
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderBasketResultPayload": {
            "vorder": [
                198
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderCouponResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                165
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderCouponResultPayload": {
            "coupon": [
                51
            ],
            "vorder": [
                198
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderNewResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                167
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderNewResultPayload": {
            "vorder": [
                198
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderReserveResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                169
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderReserveResultPayload": {
            "calc": [
                55
            ],
            "vorder": [
                198
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderSubmitResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                171
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderSubmitResultPayload": {
            "order": [
                99
            ],
            "orderId": [
                6
            ],
            "orderUrl": [
                2
            ],
            "vorder": [
                198
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderSyncResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                173
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "SaleVorderSyncResultPayload": {
            "vorder": [
                198
            ],
            "__typename": [
                2
            ]
        },
        "SearchResult": {
            "ELEMENTS": [
                125
            ],
            "SECTIONS": [
                177
            ],
            "__typename": [
                2
            ]
        },
        "SearchSuggestion": {
            "data": [
                176
            ],
            "label": [
                2
            ],
            "value": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "SearchSuggestionData": {
            "entityId": [
                6
            ],
            "entityRole": [
                2
            ],
            "entityTitle": [
                2
            ],
            "entityTypeCode": [
                2
            ],
            "entityTypeId": [
                2
            ],
            "entityTypeName": [
                2
            ],
            "hint": [
                2
            ],
            "type": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Section": {
            "ACTIVE": [
                1
            ],
            "CHILDREN": [
                177
            ],
            "CODE": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DEPTH_LEVEL": [
                6
            ],
            "DESCRIPTION": [
                2
            ],
            "DESCRIPTION_TYPE": [
                2
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "ELEMENT_CNT": [
                6
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_EXTERNAL_ID": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_TYPE_ID": [
                2
            ],
            "ID": [
                6
            ],
            "LEFT_MARGIN": [
                6
            ],
            "META": [
                68
            ],
            "NAME": [
                2
            ],
            "PARENT": [
                177
            ],
            "PARENTS": [
                177
            ],
            "PICTURE": [
                82
            ],
            "REPLACE_LINK": [
                2
            ],
            "RIGHT_MARGIN": [
                6
            ],
            "SEARCHABLE_CONTENT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "SECTION_PAGE_URL": [
                2
            ],
            "SORT": [
                6
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "__typename": [
                2
            ]
        },
        "SectionFilterInput": {
            "ACTIVE": [
                1
            ],
            "CATEGORY_CLASS": [
                181
            ],
            "CODE": [
                181
            ],
            "DATA": [
                181
            ],
            "DAY": [
                181
            ],
            "DEPTH_LEVEL": [
                84
            ],
            "ENUM": [
                181
            ],
            "FLOAT": [
                181
            ],
            "GLOBAL_ACTIVE": [
                1
            ],
            "HAS_ELEMENT": [
                84
            ],
            "HINT": [
                181
            ],
            "HTML": [
                181
            ],
            "IBLOCK_ACTIVE": [
                1
            ],
            "IBLOCK_ID": [
                84
            ],
            "IBLOCK_NAME": [
                181
            ],
            "IBLOCK_TYPE": [
                181
            ],
            "ID": [
                84
            ],
            "INT": [
                181
            ],
            "LEFT_BORDER": [
                84
            ],
            "LEFT_MARGIN": [
                84
            ],
            "LIST": [
                181
            ],
            "LOGIC": [
                181
            ],
            "MENU_BG_COLOR": [
                181
            ],
            "MENU_COLOR": [
                181
            ],
            "MENU_FOCUSED_ICON": [
                181
            ],
            "MENU_ICON": [
                181
            ],
            "M_CARD": [
                181
            ],
            "NAME": [
                181
            ],
            "NAV_TITLE": [
                181
            ],
            "NAV_URL": [
                181
            ],
            "REPLACE_LINK": [
                181
            ],
            "RIGHT_BORDER": [
                84
            ],
            "RIGHT_MARGIN": [
                84
            ],
            "SEARCH_IN": [
                181
            ],
            "SECTION_ID": [
                84
            ],
            "UPSALE_SECTIONS": [
                181
            ],
            "XML_ID": [
                181
            ],
            "__typename": [
                2
            ]
        },
        "SpecialOffer": {
            "ELEMENT": [
                125
            ],
            "ELEMENT_ID": [
                6
            ],
            "MIN_PRICE": [
                6
            ],
            "MODE": [
                2
            ],
            "TYPE": [
                2
            ],
            "TYPE_INFO": [
                180
            ],
            "__typename": [
                2
            ]
        },
        "SpecialOfferType": {
            "CODE": [
                2
            ],
            "COLOR": [
                2
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "StringFilterInput": {
            "eq": [
                2
            ],
            "exists": [
                1
            ],
            "in": [
                2
            ],
            "like": [
                2
            ],
            "not": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "TimeModeEnum": {},
        "User": {
            "AVATAR": [
                192
            ],
            "EMAIL": [
                2
            ],
            "FAMILY": [
                193
            ],
            "GREETING_NAME": [
                2
            ],
            "GROUPS_INFO": [
                195
            ],
            "ID": [
                6
            ],
            "LAST_NAME": [
                2
            ],
            "LOGIN": [
                2
            ],
            "LOGIN_FORMAT": [
                2
            ],
            "NAME": [
                2
            ],
            "NAME_FULL": [
                2
            ],
            "NAME_TEASER": [
                2
            ],
            "PERSONAL_BIRTHDAY": [
                2
            ],
            "PERSONAL_PHOTO": [
                82
            ],
            "PERSON_TYPE_ID": [
                6
            ],
            "PHONE": [
                2
            ],
            "PHONE_FORMATTED": [
                2
            ],
            "PROFILE_FILLED": [
                1
            ],
            "PROFILE_GIFT_USED": [
                1
            ],
            "PROMOCODE": [
                2
            ],
            "PROPS": [
                73
            ],
            "ROLES": [
                85
            ],
            "SECOND_NAME": [
                2
            ],
            "SESSION_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthConfirm": {
            "CODE": [
                2
            ],
            "COLOR": [
                2
            ],
            "CONFIRM_CONTENT_MOBILE": [
                2
            ],
            "CONFIRM_CONTENT_WEB": [
                2
            ],
            "CONFIRM_STEPS": [
                185
            ],
            "ICON": [
                2
            ],
            "LIST_BUTTON_MOBILE": [
                89
            ],
            "LIST_BUTTON_WEB": [
                88
            ],
            "LIST_CAPTION": [
                2
            ],
            "LIST_NAME": [
                2
            ],
            "NAME": [
                2
            ],
            "RESEND_TITLE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthConfirmStep": {
            "CAPTION": [
                2
            ],
            "CODE": [
                2
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthLoginConfirmResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                187
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthLoginConfirmResultPayload": {
            "appClient": [
                5
            ],
            "redirect": [
                85
            ],
            "sessionId": [
                2
            ],
            "userId": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthLoginRequestResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                189
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthLoginRequestResultPayload": {
            "id": [
                2
            ],
            "sid": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthLoginStartResult": {
            "error": [
                74
            ],
            "errors": [
                74
            ],
            "payload": [
                191
            ],
            "state": [
                146
            ],
            "success": [
                1
            ],
            "__typename": [
                2
            ]
        },
        "UserAuthLoginStartResultPayload": {
            "confirmModes": [
                184
            ],
            "__typename": [
                2
            ]
        },
        "UserAvatar": {
            "ELEMENT_ID": [
                6
            ],
            "IMAGE": [
                82
            ],
            "__typename": [
                2
            ]
        },
        "UserFamily": {
            "BIRTHDAY": [
                2
            ],
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "RELATION": [
                2
            ],
            "USER_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "UserFamilyInput": {
            "BIRTHDAY": [
                2
            ],
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "UserGroup": {
            "ID": [
                6
            ],
            "NAME": [
                2
            ],
            "TYPE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "UserSession": {
            "FUSER_ID": [
                6
            ],
            "SESSION_ID": [
                2
            ],
            "USER_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "VacancyRecordset": {
            "info": [
                140
            ],
            "nodes": [
                44
            ],
            "__typename": [
                2
            ]
        },
        "Vorder": {
            "ATTR": [
                105
            ],
            "ATTRS": [
                100
            ],
            "BASKET": [
                12,
                {
                    "recalc": [
                        1
                    ]
                }
            ],
            "BONUSES": [
                6
            ],
            "COUPONS": [
                51
            ],
            "DELIVERY_DEPARTMENT_ID": [
                6
            ],
            "DEPARTMENT_ID": [
                6
            ],
            "EMAIL": [
                2
            ],
            "FIELDS_RAW": [
                85
            ],
            "FUSER_ID": [
                6
            ],
            "ID": [
                6
            ],
            "ORDER": [
                99
            ],
            "ORDER_ID": [
                6
            ],
            "PHONE": [
                2
            ],
            "PICKUP_DEPARTMENT_ID": [
                6
            ],
            "PROFILE_ID": [
                6
            ],
            "PROPS_RAW": [
                85
            ],
            "SESSION_ID": [
                2
            ],
            "USER": [
                183
            ],
            "USER_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "VorderCurrent": {
            "ATTR": [
                105
            ],
            "ATTRS": [
                100
            ],
            "BASKET": [
                12,
                {
                    "recalc": [
                        1
                    ]
                }
            ],
            "BASKET_RULES": [
                21
            ],
            "BASKET_RULES_RESULT": [
                30
            ],
            "BONUSES": [
                6
            ],
            "BONUSES_AVAILABLE": [
                6
            ],
            "BONUSES_PERCENT": [
                6
            ],
            "COUPONS": [
                51
            ],
            "COUPON_CAN_ADD": [
                1
            ],
            "DELIVERIES": [
                56
            ],
            "DELIVERY_CALCULATED": [
                1
            ],
            "DELIVERY_DEPARTMENT_ID": [
                6
            ],
            "DELIVERY_FREE_FROM_PRICE": [
                13
            ],
            "DEPARTMENTS": [
                40
            ],
            "DEPARTMENT_ID": [
                6
            ],
            "DISCOUNTS": [
                60
            ],
            "EMAIL": [
                2
            ],
            "FIELDS_RAW": [
                85
            ],
            "FUSER_ID": [
                6
            ],
            "ID": [
                6
            ],
            "ORDER": [
                99
            ],
            "ORDER_ID": [
                6
            ],
            "PAYMENT_TYPES": [
                122
            ],
            "PERSON_TYPES": [
                124
            ],
            "PHONE": [
                2
            ],
            "PICKUP_DEPARTMENT_ID": [
                6
            ],
            "PROFILES": [
                111
            ],
            "PROFILE_ID": [
                6
            ],
            "PROPS_RAW": [
                85
            ],
            "RESPONSE_STATE": [
                146
            ],
            "SESSION_ID": [
                2
            ],
            "TRANSPORT_TYPES": [
                58
            ],
            "TS": [
                2
            ],
            "USER": [
                183
            ],
            "USER_ID": [
                6
            ],
            "__typename": [
                2
            ]
        },
        "VorderInput": {
            "attrs": [
                106
            ],
            "basket": [
                85
            ],
            "__typename": [
                2
            ]
        },
        "VorderSummary": {
            "EMAIL": [
                2
            ],
            "FUSER_ID": [
                6
            ],
            "ID": [
                6
            ],
            "PHONE": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "Webcam": {
            "ACTIVE": [
                1
            ],
            "ACTIVE_FROM": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "ACTIVE_TO": [
                6
            ],
            "CREATED_BY": [
                6
            ],
            "CREATED_USER_NAME": [
                2
            ],
            "DATE_CREATE": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "DETAIL_PICTURE": [
                82
            ],
            "DETAIL_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "DETAIL_TEXT_TYPE": [
                2
            ],
            "EXTERNAL_ID": [
                2
            ],
            "IBLOCK_CODE": [
                2
            ],
            "IBLOCK_ID": [
                6
            ],
            "IBLOCK_SECTION_ID": [
                6
            ],
            "IBLOCK_SECTION_IDS": [
                6
            ],
            "ID": [
                6
            ],
            "META": [
                68
            ],
            "MODIFIED_BY": [
                6
            ],
            "NAME": [
                2
            ],
            "PATH": [
                177
            ],
            "PREVIEW_PICTURE": [
                82
            ],
            "PREVIEW_TEXT": [
                2,
                {
                    "format": [
                        1
                    ]
                }
            ],
            "PREVIEW_TEXT_TYPE": [
                2
            ],
            "PROPERTIES": [
                203
            ],
            "ROOT_SECTION": [
                177
            ],
            "SECTION": [
                177
            ],
            "SECTIONS": [
                177
            ],
            "SHOW_COUNTER": [
                6
            ],
            "SHOW_COUNTER_START": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "SORT": [
                6
            ],
            "TAGS": [
                2
            ],
            "TIMESTAMP_X": [
                85,
                {
                    "format": [
                        2
                    ]
                }
            ],
            "URL": [
                2
            ],
            "XML_ID": [
                2
            ],
            "__typename": [
                2
            ]
        },
        "WebcamProps": {
            "CODE": [
                2
            ],
            "__typename": [
                2
            ]
        }
    }
}