<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;
use TG\Main\Helper;

class PaymentType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Payment',
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'PAYSYSTEM_ID' => Types::int(),
            'PAYSYSTEM' => Types::get(PaysystemType::class),

            'IS_PAID' => Types::boolean(),
            'SUM' => Types::float(),
            'SUM_PAID' => Types::float(),

            'PAY_NAV' => Types::JSON(),

            'PS_STATUS' => Types::string(),
            'PS_STATUS_CODE' => Types::string(),
            'PS_STATUS_ID' => Types::string(),
            'PS_STATUS_NAME' => Types::string(),
            'PS_INVOICE_ID' => Types::string(),

            'ORDER_ID' => Types::string(),
            'ORDER_URL' => Types::string(),

            'DATE_PAID' => Types::date(),
        ];
    }

    public function resolve_DATE_PAID($parent, $args, $ctx)
    {
        return DateTime::parseAndFormat($parent->getField('DATE_PAID'), $args['format']);
    }

    public function resolve_PAYSYSTEM_ID($parent, $args, $ctx)
    {
        return $parent->getPaymentSystemId();
    }

    public function resolve_PAYSYSTEM($parent, $args, $ctx)
    {
        return $parent->getPaySystem();
    }

    public function resolve_PAY_NAV($parent, $args, $ctx)
    {
        return $parent->getPayCommand();
    }

    public function resolve_IS_PAID($parent, $args, $ctx)
    {
        return $parent->isPaid();
    }

    public function resolve_IS_ONLINE($parent, $args, $ctx)
    {
        return $parent->isOnline();
    }

    public function resolve_IS_BILL($parent, $args, $ctx)
    {
        return $parent->isOnline();
    }

    public function resolve_SUM($parent, $args, $ctx)
    {
        return $parent->getSum();
    }

    public function resolve_SUM_PAID($parent, $args, $ctx)
    {
        return $parent->getSumPaid();
    }

    public function resolve_PS_STATUS_ID($parent, $args, $ctx)
    {
        return $parent->getPaysystemStatusId();
    }

    public function resolve_PS_STATUS_NAME($parent, $args, $ctx)
    {
        return $parent->getPaysystemStatusName();
    }

    public function resolve_ORDER_ID($parent, $args, $ctx)
    {
        return $parent->getField('ORDER_ID');
    }

    public function resolve_ORDER_URL($parent, $args, $ctx)
    {
        return $parent->getOrder()->getSecretUrl();
    }

}
