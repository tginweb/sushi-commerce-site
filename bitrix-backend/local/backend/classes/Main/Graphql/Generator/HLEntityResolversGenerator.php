<?php

namespace Main\Graphql\Generator;

use Main\Entity\D7\D7Model;
use Main\Entity\D7\D7Query;
use Main\Entity\IBlock\ElementModel;
use Main\Entity\IBlock\PropertyModel;
use Main\Graphql\Type\HLBlock\HLEntityFilterInputType;
use Main\Graphql\Type\HLBlock\HLEntityType;
use Main\Graphql\Type\IBlock\ElementInputType;
use Main\Model\Response;

class HLEntityResolversGenerator extends EntityResolversGenerator
{
    public string $modelClass = D7Model::class;

    public string $modelTypeClass = HLEntityType::class;
    public string $filterTypeClass = HLEntityFilterInputType::class;

    public string $modelCreateTypeClass = ElementInputType::class;
    public string $modelUpdateTypeClass = ElementInputType::class;

    function queryRecordset($rootValue, $args, $ctx)
    {
        return $this->getQueryPrepared($this->modelClass::query()->setClientQuery($args))->getGraph();
    }

    function getQueryPrepared(D7Query $query)
    {
        $query->withViewList();
        $this->queryProcess($query);
        return $query;
    }

    function queryList($rootValue, $args, $ctx)
    {

        return $this->getQueryPrepared($this->modelClass::query()->setClientQuery($args))->limit(100)->getList();
    }

    function querySingle($rootValue, $args, $ctx)
    {
        return $this->getQueryPrepared($this->modelClass::query()->setClientQuery($args))->first();
    }

    function mutationCreate($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();

        $inputModel = $args['model'];

        $element = $this->modelClass::create([
            'UF_NAME' => $inputModel['UF_NAME'],
        ]);

        $element->save();

        if ($element->id) {
            $element = $this->modelClass::query()->withViewList()->getById($element->id);
        } else {
            $element = null;
        }

        $response->setPayload($element);

        return $response->getGraphqlJson();
    }

    function mutationUpdate($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();

        $id = $args['id'];
        $inputModel = $args['model'];

        /** @var ElementModel $element */
        $element = $this->modelClass::query()->withViewList()->getById($id);

        if ($element) {
            $element->setFieldValue('NAME', $inputModel['NAME']);
            $this->applyInputProps($element, $inputModel['PROPERTIES']);
            $element->save();
            $element = $this->modelClass::query()->withViewList()->getById($id);
        }

        $response->setPayload($element);

        return $response->getGraphqlJson();
    }

    function applyInputProps(ElementModel $element, $props = [])
    {
        if (empty($props)) {
            return;
        }
        foreach ($this->resolveInputProps($props) as $prop => $value) {
            $element->setPropValue($prop, $value);
        }
    }

    function resolveInputProps($inputProps = [])
    {
        $result = [];

        $props = $this->modelClass::getProps();

        foreach ($inputProps as $propCode => $propValue) {
            /* @var $prop PropertyModel */
            $prop = $props[$propCode];
            if ($prop) {
                if (method_exists($prop, 'gqlResolveInput')) {
                    $propValue = $prop->gqlResolveInput($propValue);
                }
            }
            $result[$propCode] = $propValue;
        }
        return $result;
    }

    function mutationDelete($rootValue, $args, $ctx, $info, Response $response)
    {
        $id = $args['id'];
        $entity = $this->modelClass::query()->getById($id);
        if ($entity)
            $entity->delete();
        return true;
    }
}


