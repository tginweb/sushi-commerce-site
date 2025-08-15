<?php

namespace Main\Graphql\Type\HLBlock;

use Main\DI\Containerable;
use Main\Entity\D7\HLModel;
use Main\Graphql\Type\Query\QueryFilterInputType;

class HLEntityFilterInputType extends QueryFilterInputType
{
    use Containerable;

    const NAME = 'HLEntityFilter';

    function getQuery()
    {
        return HLModel::query();
    }

    public function getPropsInfo()
    {
        return [];
    }

    public function getFieldsInfo()
    {
        $fields = [];

        $filters = static::getQuery()->getClientFilters();

        $service = $this->container->getGraphqlHelperService();

        foreach ($filters as $name => $filter) {
            $type = $service->getFilterTypeByAttr($filter);
            if ($type) {
                $fields[$name] = $type;
            }
        }

        return $fields;
    }
}
