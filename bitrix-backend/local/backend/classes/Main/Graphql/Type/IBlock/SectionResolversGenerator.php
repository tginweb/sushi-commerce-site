<?php

namespace Main\Graphql\Type\IBlock;

use Main\Entity\IBlock\SectionModel;
use Main\Entity\IBlock\SectionQuery;
use Main\Graphql\Generator\EntityResolversGenerator;

class SectionResolversGenerator extends EntityResolversGenerator
{
    public string $modelClass = SectionModel::class;
    public string $modelTypeClass = SectionType::class;
    public string $filterTypeClass = SectionFilterInputType::class;

    function queryRecordset($rootValue, $args, $ctx)
    {
        return $this->getQueryPrepared($this->modelClass::query()->setClientQuery($args))->getGraph();
    }

    function getQueryPrepared(SectionQuery $query)
    {
        if ($this->activeOnly)
            $query->active();
        $query->withViewList();
        $this->queryProcess($query);
        return $query;
    }

    function queryList($rootValue, $args, $ctx)
    {
        return $this->getQueryPrepared($this->modelClass::query()->setClientQuery($args))->getList();
    }

    function querySingle($rootValue, $args, $ctx)
    {
        return $this->getQueryPrepared($this->modelClass::query()->setClientQuery($args))->first();
    }
}


