<?php

namespace Shop\Core\Error;

use Main\Graphql\Types;

class VOrderValidateError extends VOrderBaseError
{
    const TYPE = 'vorder_validate';

    public static function getNameEnumValues()
    {
        return [
            'vorder_validate_basket_empty' => ['message' => 'Корзина заказа пуста'],
            'vorder_validate_field_required' => ['message' => 'Обязательное поле: ~fieldLabel~'],
            'vorder_validate_night_pickup_disable' => ['message' => 'Внимание: с 00:00 до 09:00 утра заказы оформляются только на доставку'],
            'vorder_validate_bars_product_stop_list' => ['message' => 'Данные блюда недоступны при самовывозе из суши-баров: ~stopProductNames~'],
            'vorder_validate_bonus_lager_then_max_percent' => ['message' => 'Указанное количество бонусов превышает ~maxBonusPercent~% от суммы заказа'],
            'vorder_validate_department_online_payment_unavailable' => ['message' => 'Онлайн-оплата по данному поздразделению временно недоступна'],
            'vorder_validate_save_failed' => ['message' => 'Ошибка сохранения заказа'],
        ];
    }

    static function forBasketEmpty($message = null, $fields = [])
    {
        return new static('vorder_validate_field_required', $message, $fields);
    }

    static function forFieldRequired($message = null, $fields = [])
    {
        return new static('vorder_validate_field_required', $message, $fields);
    }

    static function forNightPickupDisable($message = null, $fields = [])
    {
        return new static('vorder_validate_night_pickup_disable', $message, $fields);
    }

    static function forBarsProductStopList($message = null, $fields = [])
    {
        return new static('vorder_validate_bars_product_stop_list', $message, $fields);
    }

    static function forBonusLagerThenMaxPercent($message = null, $fields = [])
    {
        return new static('vorder_validate_bonus_lager_then_max_percent', $message, $fields);
    }

    static function forDepartmentOnlinePaymentUnavailable($message = null, $fields = [])
    {
        return new static('vorder_validate_department_online_payment_unavailable', $message, $fields);
    }

    static function forSaveFailed($message = null, $fields = [])
    {
        return new static('vorder_validate_save_failed', $message, $fields);
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
                'fieldName' => Types::string(),
                'fieldLabel' => Types::string(),
                'maxBonusPercent' => Types::int(),
                'maxBonusValue' => Types::int(),
                'stopProductIds' => Types::nonNullListOf(Types::int()),
                'stopProductNames' => Types::nonNullListOf(Types::string()),
                'departmentId' => Types::int(),
            ] + parent::getGraphQlFieldsInfo();
    }
}
