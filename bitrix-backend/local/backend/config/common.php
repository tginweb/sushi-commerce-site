<?php

/** @var array $config */

$profileUrl = '/personal';
$sharedStorageDir = $_SERVER["DOCUMENT_ROOT"] . '/../.shared/storage/';

return [
    'C1' => [
        'SOAP' => $config['ENV']['C1']['SOAP'] + [
                'TIMEOUT' => 60,
            ],
    ],
    'APP' => [
        'ID' => 'sushi',
        'PROD_HOST' => 'irkutsk.sushi-studio.ru',
        'PROD_URL' => 'https://irkutsk.sushi-studio.ru',
    ],
    'SALE' => [
        'MIN_ORDER_PRICE' => 1000,
        'MIN_DELIVERY_FREE_ORDER_PRICE' => 1500,
        'ORDER_HISTORY_STATUS' => ['F'],
        'URL' => [
            'PROFILE_PAYMENT_CARDS' => $profileUrl . '/payment-cards/',
            'PROFILE_ORDERS' => $profileUrl . '/orders/',
            'PROFILE_ORDER_PROFILES' => $profileUrl . '/orders/',
        ],
        'PASYSTEM_WRAPPER_CLASS' => [
            1 => \Shop\Core\Entity\Wrapper\Paysystem\RealCash::class,
            2 => \Shop\Core\Entity\Wrapper\Paysystem\RealCard::class,
            7 => \Shop\Core\Entity\Wrapper\Paysystem\Bonus::class,
            8 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineSber::class,
            9 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineSber::class,
            11 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineSber::class,
            12 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineSber::class,

            14 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineYookassa::class,
            15 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineYookassa::class,
            16 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineYookassa::class,
            17 => \Shop\Core\Entity\Wrapper\Paysystem\OnlineYookassa::class,
        ],
        'PAYTYPES' => [
            'cash' => ['CLASS' => \Shop\Core\Entity\Wrapper\Paytype\Cash::class, 'PAYSYSTEM_ID' => 1],
            'card' => ['CLASS' => \Shop\Core\Entity\Wrapper\Paytype\Card::class, 'PAYSYSTEM_ID' => 2],
            'online' => ['CLASS' => \Shop\Core\Entity\Wrapper\Paytype\Online::class],
        ],
        'DELIVERY_WRAPPER_CLASS' => [
            1 => \Shop\Core\Entity\Wrapper\Delivery\Courier::class,
            2 => \Shop\Core\Entity\Wrapper\Delivery\Pickup::class,
        ],
    ],
    'IMAGE' => [
        'BASE_URL' => 'https://irkutsk.sushi-studio.ru',
        'IMAGE_DEV_BASE_URL' => 'http://dev.sushi-new.prod.loc',
        'STYLER_PROVIDERS' => [
            'default' => [
                'class' => \Main\Provider\ImageStyler\Cloudinary::class,
            ]
        ]
    ],
    'GRAPHQL' => [
        'CACHE_ENABLE' => true
    ],
    'CACHE' => [
        'PROVIDERS' => [
            'default' => [
                'class' => \Main\Provider\Cache\Redis::class,
                'connection' => 'default'
            ]
        ]
    ],
    'REDIS' => [
        'CONNECTIONS' => [
            'default' => [
                'dsn' => 'redis://localhost:6379/0',
            ]
        ]
    ],
    'AUTH_CONFIRM' => [
        'PROVIDERS' => [

            'telegram' => [
                'class' => \Main\Provider\AuthConfirm\Telegram::class,
                'rateRules' => [
                    [
                        'limit' => 3,
                        'period' => 30,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 5,
                        'period' => 60,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 10,
                        'period' => 60 * 3,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 15,
                        'period' => 60 * 5,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 30,
                        'period' => 60 * 60,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],

                    [
                        'limit' => 50,
                        'period' => 60 * 60 * 24,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 500,
                        'period' => 60 * 60 * 24,
                        'args' => function ($args) {
                            return ['IP'];
                        }
                    ],
                ],
            ],

            'call' => [
                'class' => \Main\Provider\AuthConfirm\Call::class,
                //'rateDisablePhones' => ['79501102996'],
                'rateRules' => [
                    [
                        'limit' => 1,
                        'period' => 30,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 2,
                        'period' => 60,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 3,
                        'period' => 60 * 3,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 5,
                        'period' => 60 * 5,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 10,
                        'period' => 60 * 60 * 3,
                        'args' => function ($args) {
                            return [$args['phone']];
                        }
                    ],
                    [
                        'limit' => 50,
                        'period' => 60 * 60 * 24,
                        'args' => function ($args) {
                            return ['IP'];
                        }
                    ],
                ],
            ],
            /*
            'sms' => [
                'class' => TG\Main\User\Lib\AuthConfirmProvider\Sms::class,
            ],
            */
        ]
    ],
    'SMS' => [
        'PROVIDERS' => [
            'default' => [
                //'class' => \TG\Sms\Core\Provider\Call::class,
            ]
        ]
    ],
    'CAPTCHA' => [
        'PROVIDERS' => [
            'google' => [
                'class' => \TG\Captcha\Core\Provider\Google\Provider::class,
            ],
        ],
        'DEFAULT_WEB' => 'draggable',
        'DEFAULT_MOBILE' => 'draggable',
    ],
    'PAGE' => [
        'IBLOCK_ID' => 45,
        'ROUTES_STORAGE_URI' => $sharedStorageDir . '/routes.json',
    ],
    'CATALOG' => [
        'SECTION_STORAGE_URI' => $sharedStorageDir . '/catalog-sections.json',
        'SECTION_PATHS_STORAGE_URI' => $sharedStorageDir . '/catalog-sections-paths.json',
        'BLOCK_ID' => 1,
        'DEFAULT_MEASURE_NAME' => 'кг',
        'SALES_COUNT_IS_HIT' => 8000,
        'SALES_COUNT_IS_POPULAR' => 500,
    ],
    'ENTITY' => [
        'CLIENT_STORAGE_URI' => $sharedStorageDir . '/entity-type.json',
    ],
    'REDIRECTS' => [
        'STORAGE_URI' => $sharedStorageDir . '/redirects.txt',
    ],
    'HLBLOCK' => [
        'SCHEMA' => [
            'Notice' => 44,
            'NoticeCommon' => 43,
        ]
    ],
    'IBLOCK' => [
        'CACHE_IBLOCKS' => true,
        'CACHE_PROPS' => true,
        'CACHE_ENUMS' => true,

        'SCHEMA' => [
            1 => [
                'ELEMENT_CLASS' => App\Shop\Core\Entity\AppProduct::class,
                'SECTION_CLASS' => App\Shop\Core\Entity\AppProductSection::class,
                'ROLE' => 'product'
            ],
            24 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\Constructor::class,
                'SECTION_CLASS' => \Shop\Core\Entity\ConstructorSection::class,
                'ROLE' => 'product_constructor'
            ],
            37 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\ConstructorBuild::class,
                'ROLE' => 'product_constructor_build'
            ],
            56 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\ProductTag::class,
                'ROLE' => 'product_tag'
            ],
            19 => [
                'ELEMENT_CLASS' => App\Webcam\Core\Entity\Webcam::class,
            ],
            45 => [
                'ELEMENT_CLASS' => \Page\Core\Entity\Page::class,
                'SECTION_CLASS' => \Page\Core\Entity\PageSectionModel::class,
            ],
            46 => [
                'ELEMENT_CLASS' => \Main\Entity\User\UserAvatarElementModel::class,
            ],
            47 => [
                'ELEMENT_CLASS' => \Menu\Core\Entity\MenuItem::class,
                'SECTION_CLASS' => \Menu\Core\Entity\MenuItemSection::class,
            ],
            49 => [
                'ELEMENT_CLASS' => \Faq\Core\Entity\FaqQuestion::class,
                'SECTION_CLASS' => \Faq\Core\Entity\FaqQuestionSectionModel::class,
            ],
            48 => [
                'ELEMENT_CLASS' => \Company\Core\Entity\Office::class,
            ],
            50 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\DeliveryZone::class,
            ],
            52 => [
                'ELEMENT_CLASS' => \Review\Core\Entity\Review::class,
            ],
            54 => [
                'ELEMENT_CLASS' => \Search\Core\Entity\SearchSuggestion::class,
            ],
            57 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\PersonalGift::class,
            ],
            58 => [
                'ELEMENT_CLASS' => \Company\Core\Entity\Vacancy::class,
            ],
            59 => [
                'ELEMENT_CLASS' => \Company\Core\Entity\VacancyOrderModel::class,
            ],

            60 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\BonusLevel::class,
            ],
            61 => [
                'ELEMENT_CLASS' => \Offer\Core\Entity\OfferModel::class,
                'SECTION_CLASS' => \Offer\Core\Entity\OfferSectionModel::class,
            ],
            63 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\Discount::class,
            ],
            64 => [
                'ELEMENT_CLASS' => \Shop\Core\Entity\Coupon::class,
            ],
            66 => [
                'ELEMENT_CLASS' => \Menu\Core\Entity\MenuItemMobile::class,
                'SECTION_CLASS' => \Menu\Core\Entity\MenuItemMobileSection::class,
            ],
            67 => [
                'ELEMENT_CLASS' => \Page\Core\Entity\Page::class,
                'SECTION_CLASS' => \Page\Core\Entity\PageSectionModel::class,
            ],
        ]
    ],

    'USER' => [
        'PROFILE_PATH' => $profileUrl,
        'AUTO_CREATE_EMAIL_DOMAIN' => 'sushi-studio.ru'
    ],

    'MONGO' => [
        'CONNECTIONS' => [
            'default' => [
                'dsn' => 'mongodb://localhost:27017',
            ],
            'logger' => [
                'dsn' => 'mongodb://localhost:27017',
                'db' => 'sushi',
            ],
        ]
    ],

    'LOGGER' => [
        'ENABLE' => false,
        'MONGO' => [
            'connection' => 'logger',
        ]
    ],

    'SERVICE_1C' => [
        'SEND_ORDER' => true,
    ],

    'GEOCODER' => [
        'PROVIDERS' => [
            'default' => [
                'class' => \Geo\Core\Provider\GeoCoder\Yandex::class,
                'apiKeys' => [
                    '143f8c3a-7a2e-4040-a32a-bb5a55890be4',
                ]
            ]
        ]
    ],

    'RATE' => [
        'RULES' => [

            'USER_EMAIL_CHANGE_SUCCESS' => [
                [
                    'limit' => 1,
                    'period' => 3600 * 24,
                    'args' => function ($args) {
                        return ['USER'];
                    }
                ],
            ],
            'USER_EMAIL_CHANGE' => [
                [
                    'limit' => 2,
                    'period' => 30,
                    'args' => function ($args) {
                        return [$args['email']];
                    }
                ],
                [
                    'limit' => 3,
                    'period' => 60,
                    'args' => function ($args) {
                        return [$args['email']];
                    }
                ],
                [
                    'limit' => 20,
                    'period' => 3600 * 24,
                    'args' => function ($args) {
                        return [$args['email']];
                    }
                ],
                [
                    'limit' => 500,
                    'period' => 3600 * 24,
                    'args' => function ($args) {
                        return ['IP'];
                    }
                ],
            ]
        ]
    ],

    'MOBILE_APP' => [
        'HTML_CLASSES' => [
            'default' => [
                'body' => ['fontSize' => '20px'],
                'c-item' => [
                    'border-radius' => '16px',
                    'border' => '1px solid #DDDDDD',
                    'margin-bottom' => '20px'
                ]
            ],
            //'li' => ['color' => '#FF0000']
        ],
        'HTML_STYLES' => [
            'default' => [
                'body' => ['whiteSpace' => 'normal', 'color' => '#222222', 'fontSize' => '16px'],
                'a' => ['color' => '#ce8714', 'textDecorationColor' => '#ce8714'],
                'ul' => ['margin' => '10px 0 10px 0', 'padding' => '0 0 0 20px'],
                'li' => ['margin' => '0px 0 10px 0'],
            ]
        ],
    ],

    'MAIL' => []
];
