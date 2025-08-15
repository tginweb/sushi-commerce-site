<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\D7Query;

class OrderQuery extends D7Query
{
    public $recordset;

    public function __construct($bxObject, $modelName, $collectionClass = null)
    {
        $this->bxObject = $bxObject;
        $this->modelName = $modelName;

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
    }

    function buildClientFilterItem(&$result, $attr, $field, $filterOp, $filterValue)
    {
        parent::buildClientFilterItem($result, $attr, $field, $filterOp, $filterValue);
    }

    protected function loadModels()
    {
        $propFilters = $this->extractFilterProps($this->filter);

        $arProps = [];

        foreach ($propFilters as $propCode => $propFilter) {
            if ($propFilter['eq']) {
                $arProps[$propCode] = ['filter' => ['op' => '=', 'value' => $propFilter['eq']]];
            } else if ($propFilter['like']) {
                $arProps[$propCode] = ['filter' => ['op' => '%', 'value' => $propFilter['like']]];
            } else if ($propFilter['in']) {
                $arProps[$propCode] = ['filter' => ['op' => 'in', 'value' => $propFilter['in']]];
            }
        }


        if (!empty($arProps)) {

            $qRuntime = [];

            foreach ($arProps as $index => $arProp) {

                $pfield = 'PROP_' . $index;

                $propName = $arProp['prop'] ?: $index;

                $this->filter([
                    '=' . $pfield . '.CODE' => $propName
                ]);

                if ($filter = $arProp['filter']) {
                    $this->filter([
                        $filter['op'] . $pfield . '.VALUE' => $filter['value']
                    ]);
                }

                if ($select = $arProp['select']) {
                    $this->select($pfield . '.VALUE');
                }

                if ($arProp['order']) {
                    //$qOrder[$pfield . '.VALUE'] = $sortOrder;
                }

                $qRuntime[] = new \Bitrix\Main\Entity\ReferenceField(
                    $pfield,
                    '\Bitrix\sale\Internals\OrderPropsValueTable',
                    ["=this.ID" => "ref.ORDER_ID"],
                    ["join_type" => "left"]
                );
            }

            $this->runtime($qRuntime);
        }

        return parent::loadModels();
    }

    public function getRecordset($refetch = false)
    {
        if (!isset($this->recordset) || $refetch) {
            $params = [
                'select' => $this->select,
                'filter' => $this->filter,
                'group' => $this->group,
                'order' => $this->sort,
                'limit' => $this->limit,
                'offset' => $this->offset,
                'runtime' => $this->runtime,
                'count_total' => true
            ];

            $this->recordset = $this->bxObject->getList($params);
        }
        return $this->recordset;
    }

    public function loadCollection($params)
    {
        $rows = [];
        if (!$this->setEmpty) {
            $result = $this->getRecordset();
            while ($row = $result->fetch()) {
                $rows[$row['ID']] = $this->modelName::createFromArray($row);
            }
        }
        return new $this->collectionClass($rows);
    }

    public function count()
    {
        return $this->getRecordset()->getCount();
    }

    public function extractFilterProps(&$filters)
    {
        $result = [];

        foreach ($filters as $filterKey => $filterValue) {
            if (strpos($filterKey, 'PROP_') !== false) {
                $result[str_replace('PROP_', '', $filterKey)] = $filterValue;
                unset($filters[$filterKey]);
            }
        }

        return $result;
    }
}
