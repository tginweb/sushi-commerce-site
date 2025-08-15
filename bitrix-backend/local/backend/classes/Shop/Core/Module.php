<?php

namespace Shop\Core;

use Bitrix;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Sale\Registry;
use Main\Entity\IBlock\Property\Json;
use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;
use Main\Lib\Common\Invoker;
use Shop\Core\Enum\BasketRuleActionTypeEnum;
use Shop\Core\Enum\BasketRuleConditionTypeEnum;
use Shop\Core\Enum\ContentBuilderElementTypeEnum;
use Shop\Core\Enum\DeliveryCalcStatusEnum;
use Shop\Core\Enum\DeliveryTransportTypeEnum;
use Shop\Core\Enum\DiscountModeEnum;
use Shop\Core\Enum\DiscountTargetEnum;
use Shop\Core\Enum\TimeModeEnum;
use Shop\Core\Service\BasketService;
use Shop\Core\Service\CatalogFavService;
use Shop\Core\Service\CatalogService;
use Shop\Core\Service\OrderService;
use TG\Shop\Core\Lib\SalePovider;

class Module extends BaseModule
{
    const DI_BASKET_MODEL = 'sale.core:basket.model';
    const DI_VORDER_MODEL = 'sale.core:vorder.model';

    function register($scopes = [])
    {
        Loader::includeModule('sale');

        new \Shop\Core\Dataloader\OrderLoader();
        new \Shop\Core\Dataloader\DepartmentLoader();

        $container = $this->container;

        $events = EventManager::getInstance();

        $reg = Registry::getInstance(Registry::REGISTRY_TYPE_ORDER);

        $reg->set(Registry::ENTITY_BASKET, \Shop\Core\Entity\Basket::class);
        $reg->set(Registry::ENTITY_BASKET_ITEM, \Shop\Core\Entity\BasketItem::class);
        $reg->set(Registry::ENTITY_ORDER, \Shop\Core\Entity\OrderModel::class);
        $reg->set(Registry::ENTITY_PAYMENT, \Shop\Core\Entity\Payment::class);
        $reg->set(Registry::ENTITY_PROPERTY_VALUE, \Shop\Core\Entity\OrderPropertyValue::class);

        $container->define(CatalogService::class);
        $container->define(CatalogFavService::class);

        $container->register(\Shop\Core\Service\OrderService::class);
        $container->register(\Shop\Core\Service\OrderAttributesService::class);

        $container->define(\Shop\Core\Service\OrderHistoryService::class);
        $container->define(\Shop\Core\Service\OrderProfileService::class);
        $container->define(\Shop\Core\Service\OrderReserveService::class);
        $container->define(\Shop\Core\Service\SaleService::class);
        $container->define(\Shop\Core\Service\DiscountService::class);
        $container->define(\Shop\Core\Service\CouponService::class);
        $container->define(\Shop\Core\Service\LocationService::class);
        $container->define(\Shop\Core\Service\BasketService::class);
        $container->define(\Shop\Core\Service\FavService::class);
        $container->define(\Shop\Core\Service\ClientCardService::class);
        $container->define(\Shop\Core\Service\AdminOrderViewService::class);
        $container->define(\Shop\Core\Service\DeliveryService::class);
        $container->define(\Shop\Core\Service\PaymentService::class);
        $container->define(\Shop\Core\Service\BenefitService::class);

        $container->define(self::DI_BASKET_MODEL, function () {
            return \Shop\Core\Service\BasketService::i()->getBasket();
        });

        $container->define(self::DI_VORDER_MODEL, function () use ($container) {
            return $container->getVorderClass()::getCurrent();
        });

        new \Shop\Core\Dataloader\FavLoader();

        $container->addFilter('main:entity.types', [$this, 'registerEntityTypes']);
        $container->addFilter('sale:property.types', [$this, 'registerSalePropertyTypes']);
        $container->addFilter('iblock:properties', [$this, 'registerIblockProperties']);
        $container->addFilter('sale.providers', [$this, 'registerSaleProviders']);
        $container->addFilter('enum.types', [$this, 'registerSaleProviders']);
        $container->addFilter('mail:event.preprocessor.order', new Invoker([OrderService::class, 'callEventPreprocessorOrder']));

        $events->addEventHandler('sale', 'OnSaleBasketBeforeSaved', new Invoker([BasketService::class, 'onEvent']));

        parent::register($scopes);
    }

    function registerIblockProperties($data)
    {
        $data['delivery_zone.GEOJSON'] = [
            'class' => Json::class
        ];
        return $data;
    }

    function registerSalePropertyTypes($data)
    {
        $data['TRANSPORT_TYPE'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Enum::class
        ];

        $data['STRUCT'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Json::class
        ];

        $data['HOUSE_COORDS'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Coordinates::class
        ];

        $data['STREET_COORDS'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Coordinates::class
        ];

        $data['DATA'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Data::class
        ];

        $data['DATE'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Date::class
        ];

        $data['DEPARTMENT'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Department::class
        ];

        $data['PAYMENT_TYPE'] = [
            'class' => \Shop\Core\Entity\OrderProperty\PaymentType::class
        ];

        $data['PROFILE_ID'] = [
            'class' => \Shop\Core\Entity\OrderProperty\ProfileId::class
        ];

        $data['PHONE'] = [
            'class' => \Shop\Core\Entity\OrderProperty\Phone::class
        ];

        $data['RESERVE_REQUEST_TIME'] = [
            'class' => \Shop\Core\Entity\OrderProperty\ReserveRequestTime::class
        ];

        $data['RESERVE_AVAILABLE_TIME'] = [
            'class' => \Shop\Core\Entity\OrderProperty\ReserveAvailableTime::class
        ];

        $data['RESERVE_NEED_TIME'] = [
            'class' => \Shop\Core\Entity\OrderProperty\ReserveNeedTime::class
        ];

        $data['RESERVE_STATUS'] = [
            'class' => \Shop\Core\Entity\OrderProperty\ReserveStatus::class
        ];

        $data['RESERVE_SUCCESS_HASH'] = [
            'class' => \Shop\Core\Entity\OrderProperty\ReserveSuccessHash::class
        ];

        $data['RECEIVER_NAME'] = [
            'class' => \Shop\Core\Entity\OrderProperty\ReceiverName::class
        ];

        $data['BENEFIT_TYPE'] = [
            'class' => \Shop\Core\Entity\OrderProperty\BenefitType::class
        ];

        $data['TIME_MODE'] = [
            'class' => \Shop\Core\Entity\OrderProperty\TimeMode::class
        ];

        return $data;
    }

    function registerSaleProviders($data)
    {
        $data['product'] = [
            'NAME' => ' Товар',
            'CLASS' => \Shop\Core\Lib\SalePovider\Product::class
        ];

        return $data;
    }

    function registerEvents()
    {
        $this->container->addFilter('sale:order.events', function ($data) {
            $data['SALE_NEW_ORDER'] = [
                'NAME' => 'Новый заказ',
            ];
            $data['PAYMENT_AUTO_SUCCESS'] = [
                'NAME' => 'Автооплата: успешно',
            ];
            $data['PAYMENT_AUTO_ERROR'] = [
                'NAME' => 'Автооплата: ошибка',
            ];
            return $data;
        });
    }

    function registerEntityTypes($data)
    {
        return $data;
    }

    function registerApi()
    {

    }

    function registerTypes()
    {

        Types::enums([
            'TimeModeEnum' => TimeModeEnum::class,

            'DeliveryCalcStatusEnum' => DeliveryCalcStatusEnum::class,
            'DeliveryTransportTypeEnum' => DeliveryTransportTypeEnum::class,

            'BasketRuleActionTypeEnum' => BasketRuleActionTypeEnum::class,
            'BasketRuleConditionTypeEnum' => BasketRuleConditionTypeEnum::class,

            'DiscountTargetEnum' => DiscountTargetEnum::class,
            'DiscountModeEnum' => DiscountModeEnum::class,

            'ContentBuilderElementTypeEnum' => ContentBuilderElementTypeEnum::class
        ]);

        Types::types([
            'Product' => \Shop\Core\Graphql\ProductType::class,
            'ProductFilterInput' => \Shop\Core\Graphql\ProductFilterInputType::class,
            'ProductSection' => \Shop\Core\Graphql\ProductSectionType::class,
            'ProductPrice' => \Shop\Core\Graphql\ProductPriceType::class,
            'ProductMeasure' => \Shop\Core\Graphql\ProductMeasureType::class,
            'ProductTagElement' => \Shop\Core\Graphql\ProductTagType::class,
            'ConstructorSection' => \Shop\Core\Graphql\ConstructorSectionType::class,
            'CatalogFav' => \Shop\Core\Graphql\ProductFavType::class,
            'ProductGift' => \Shop\Core\Graphql\ProductGiftType::class,
            'ProductFlag' => \Shop\Core\Graphql\ProductFlagType::class,
            'ProductSetItem' => \Shop\Core\Graphql\ProducSetItemType::class,

            'BonusLevelElement' => \Shop\Core\Graphql\BonusLevelType::class,

            'Vorder' => \Shop\Core\Graphql\VorderType::class,
            'VorderCurrent' => \Shop\Core\Graphql\VorderCurrentType::class,
            'VorderResult' => \Shop\Core\Graphql\VorderResultType::class,
            'VorderInput' => \Shop\Core\Graphql\VorderInputType::class,

            'SaleDepartment' => \Shop\Core\Graphql\DepartmentType::class,

            'Basket' => \Shop\Core\Graphql\BasketType::class,
            'Payment' => \Shop\Core\Graphql\PaymentType::class,

            'OrderFilter' => \Shop\Core\Graphql\OrderFilterType::class,
            'Order' => \Shop\Core\Graphql\OrderType::class,
            'OrderStatus' => \Shop\Core\Graphql\OrderStatusType::class,
            'OrderProp' => \Shop\Core\Graphql\OrderPropType::class,
            'OrderPropValue' => \Shop\Core\Graphql\OrderPropValueType::class,
            'OrderAttribute' => \Shop\Core\Graphql\OrderAttributeType::class,
            'OrderAttributeOption' => \Shop\Core\Graphql\OrderAttributeOptionType::class,
            'OrderProfile' => \Shop\Core\Graphql\OrderProfileType::class,
            'OrderProfileForm' => \Shop\Core\Graphql\OrderProfileFormType::class,
            'OrderPropGroup' => \Shop\Core\Graphql\OrderPropGroupType::class,
            'OrderAttributesValueInput' => \Shop\Core\Graphql\OrderAttributesValueInput::class,

            'PersonType' => \Shop\Core\Graphql\PersonTypeType::class,

            'FavItem' => \Shop\Core\Graphql\FavItemType::class,
            'BasketItem' => \Shop\Core\Graphql\BasketItemType::class,
            'BasketItemBase' => \Shop\Core\Graphql\BasketItemBaseType::class,
            'BasketItemProp' => \Shop\Core\Graphql\BasketItemPropType::class,

            'SpecialOffer' => \Shop\Core\Graphql\SpecialOfferType::class,
            'SpecialOfferType' => \Shop\Core\Graphql\SpecialOfferTypeType::class,

            'Paysystem' => \Shop\Core\Graphql\PaysystemType::class,
            'PaysystemWrapper' => \Shop\Core\Graphql\PaysystemWrapperType::class,
            'PaysystemComputed' => \Shop\Core\Graphql\PaysystemComputedType::class,

            'DeliveryService' => \Shop\Core\Graphql\DeliveryServiceType::class,
            'DeliveryWrapper' => \Shop\Core\Graphql\DeliveryWrapperType::class,
            'DeliveryComputed' => \Shop\Core\Graphql\DeliveryComputedType::class,
            'DeliveryCalculateType' => \Shop\Core\Graphql\DeliveryCalculateType::class,
            'DeliveryZoneElement' => \Shop\Core\Graphql\DeliveryZoneType::class,

            'Location' => \Shop\Core\Graphql\LocationType::class,
            'Coupon' => \Shop\Core\Graphql\CouponType::class,

            'SaleClientCard' => \Shop\Core\Graphql\ClientCardType::class,
            'SaleUserInfo' => \Shop\Core\Graphql\UserInfoType::class,

            'Discount' => \Shop\Core\Graphql\DiscountType::class,
            'DiscountCondition' => \Shop\Core\Graphql\DiscountConditionType::class,

            'CourierState' => \Shop\Core\Graphql\CourierStateType::class,

            'BasketRule' => \Shop\Core\Graphql\BasketRuleType::class,
            'BasketRuleCondition' => \Shop\Core\Graphql\BasketRuleConditionType::class,

            'BasketRuleActionInterface' => [\Shop\Core\Graphql\BasketRuleActionType::class, 'getGraphQlInterfaceType'],
            'BasketRuleActionDiscount' => \Shop\Core\Graphql\BasketRuleActionDiscountType::class,
            'BasketRuleActionGift' => \Shop\Core\Graphql\BasketRuleActionGiftType::class,

            'BasketRulesResultType' => \Shop\Core\Graphql\BasketRulesResultType::class,
            'BasketRulesResultBenefitProductType' => \Shop\Core\Graphql\BasketRulesResultBenefitProductType::class,
        ]);
    }
}
