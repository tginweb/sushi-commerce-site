<?php

namespace Shop\Core\Graphql;

use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;
use TG\Shop\Core\Entity\Wrapper;

class PaysystemType extends ObjectType
{
    const NAME = 'Paysystem';

    public function getFieldsInfo()
    {
        return [
            'ID' => Types::int(),
            'NAME' => Types::string(),
            'CODE' => Types::string(),
            'DESCRIPTION' => Types::string(),

            'IS_INNER' => Types::boolean(),
            'IS_ONLINE' => Types::boolean(),
            'IS_ONLINE_DELAYED' => Types::boolean(),
            'IS_BILL' => Types::boolean(),
        ];
    }

    function getWrapperMethod($parent, $method)
    {
        $wrapper = \Shop\Core\Entity\Wrapper\Paysystem\Base::wrap($parent) ?: null;
        if ($wrapper) {
            return $wrapper->{$method}();
        }
    }

    public function resolve_IS_INNER($parent, $args, $ctx)
    {
        return $this->getWrapperMethod($parent, 'isInner');
    }

    public function resolve_IS_ONLINE($parent, $args, $ctx)
    {
        return $this->getWrapperMethod($parent, 'isOnline');
    }

    public function resolve_IS_ONLINE_DELAYED($parent, $args, $ctx)
    {
        return $this->getWrapperMethod($parent, 'isOnlineDelayed');
    }

    public function resolve_IS_BILL($parent, $args, $ctx)
    {

        return $this->getWrapperMethod($parent, 'isBill');
    }
}
