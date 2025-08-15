<?php

namespace Main\Graphql\Type\IBlock;

use Main\DI\Containerable;
use Main\Entity\IBlock\ElementModel;
use Main\Graphql\Type\Query\QueryFilterInputType;

class ElementFilterInputType extends QueryFilterInputType
{
    use Containerable;

    const NAME = 'ElementListFilterInput';

    function getQuery()
    {
        return ElementModel::query();
    }

    function getFieldsInfo()
    {
        $filters = $this->getQuery()->getClientFilters();

        $service = $this->container->getGraphqlHelperService();
        $fields = [];
        foreach ($filters as $name => $filter) {
            $type = $service->getFilterTypeByAttr($filter);
            if ($type) {
                $fields[$name] = $type;
            }
        }

        return $fields;
    }
}
