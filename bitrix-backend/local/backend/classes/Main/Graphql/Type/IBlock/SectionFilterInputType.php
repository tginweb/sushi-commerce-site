<?php

namespace Main\Graphql\Type\IBlock;

use Main\DI\Containerable;
use Main\Entity\IBlock\SectionModel;
use Main\Graphql\Type\Query\QueryFilterInputType;

class SectionFilterInputType extends QueryFilterInputType
{
    use Containerable;

    const NAME = 'SectionFilterInput';

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

    function getQuery()
    {
        return SectionModel::query();
    }
}
