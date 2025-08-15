<?php

namespace Shop\Core\Entity;

use Main\Entity\D7\OrmQuery;
use TG\Main\Helper;

class OrderOrmQuery extends OrmQuery
{
    public static function getClientFiltersInfo()
    {
        return [
            'ID' => [
                'TYPE' => 'int'
            ],
            'ACCOUNT_NUMBER' => [
                'TYPE' => 'string'
            ],
            'DATE_INSERT' => [
                'TYPE' => 'string'
            ],
            'USER_ID' => [
                'TYPE' => 'int'
            ],
            'PROP_FIO' => [
                'TYPE' => 'string'
            ],
            'PROP_SELLER_GROUPS' => [
                'TYPE' => 'string'
            ],
            'PAY_SYSTEM_ID' => [
                'TYPE' => 'int'
            ],
            'STATUS_ID' => [
                'TYPE' => 'string'
            ],
            'CANCELED' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {
                    if (is_bool($condValue)) {
                        $query->addFilter('CANCELED', $condValue ? 'Y' : 'N');
                    }
                }
            ],
            'PAYED' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {
                    if (is_bool($condValue)) {
                        $query->addFilter('PAYED', $condValue ? 'Y' : 'N');
                    }
                }
            ],
            'ELEMENT_NAME' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {
                    if ($condValue) {

                        $val = $condValue['like'];

                        if (!empty($val)) {

                            $sub = \Bitrix\Sale\Internals\BasketTable::query()->setSelect(['ORDER_ID']);

                            $sub->registerRuntimeField('ELEMENT', [
                                'data_type' => '\Bitrix\Iblock\ElementTable',
                                'reference' => [
                                    '=this.PRODUCT_ID' => 'ref.ID',
                                ],
                            ]);

                            $sub->whereLike('ELEMENT.NAME', '%' . $val . '%');

                            $query->whereIn('ID', $sub);
                        }

                    }
                }
            ],
            'SECTION_ID' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {

                    if ($condValue) {

                        $values = $condValue['in'] ?? $condValue['eq'];

                        if (!empty($values)) {

                            $sub = \Bitrix\Sale\Internals\BasketTable::query()->setSelect(['ORDER_ID']);

                            $sub->registerRuntimeField('ELEMENT', [
                                'data_type' => '\Bitrix\Iblock\ElementTable',
                                'reference' => [
                                    '=this.PRODUCT_ID' => 'ref.ID',
                                ],
                            ]);

                            $values = (array)$values;

                            $subsectionIds = \Main\Helper\Iblock::getSectionChildrenIds($values);

                            $sub->whereIn('ELEMENT.IBLOCK_SECTION_ID', $subsectionIds);
                            $query->whereIn('ID', $sub);
                        }

                    }
                }
            ],
            'DATE_YEAR' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {

                    if ($condValue) {
                        $year = $condValue;

                        $dateFrom = new \DateTime();
                        $dateFrom->setDate($year, 1, 1);

                        $dateTo = new \DateTime();
                        $dateTo->setDate($dateFrom->format('Y'), 12, 31);

                        $query->addFilter('>=DATE_INSERT', $dateFrom->format('d.m.Y'));
                        $query->addFilter('<DATE_INSERT', $dateTo->format('d.m.Y'));


                    }
                }
            ],
            'DATE_MONTH' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {

                    if ($condValue) {
                        $month = $condValue;
                        $year = $filters['DATE_YEAR'];

                        $dateFrom = new \DateTime();
                        $dateFrom->setDate($year, $month, 1);

                        $dateTo = clone $dateFrom;
                        $dateTo->modify('last day of this month');

                        $query->addFilter('>=DATE_INSERT', $dateFrom->format('d.m.Y'));
                        $query->addFilter('<DATE_INSERT', $dateTo->format('d.m.Y'));
                    }
                }
            ],
            'DATE_DAY' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {


                    if ($condValue) {
                        $day = $condValue;
                        $month = $filters['DATE_MONTH'];
                        $year = $filters['DATE_YEAR'];

                        $dateFrom = new \DateTime();
                        $dateFrom->setDate($year, $month, $day);
                        $dateFrom->setTime(00, 00, 00);

                        $dateTo = new \DateTime();
                        $dateTo->setDate($year, $month, $day);
                        $dateTo->setTime(23, 59, 59);

                        $query->addFilter('>=DATE_INSERT', $dateFrom->format('d.m.Y  H:i:s'));
                        $query->addFilter('<DATE_INSERT', $dateTo->format('d.m.Y  H:i:s'));
                    }
                }
            ],

            'DATE_PAYED_YEAR' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {

                    if ($condValue) {
                        $year = $condValue;

                        $dateFrom = new \DateTime();
                        $dateFrom->setDate($year, 1, 1);

                        $dateTo = new \DateTime();
                        $dateTo->setDate($dateFrom->format('Y'), 12, 31);

                        $query->addFilter('>=DATE_PAYED', $dateFrom->format('d.m.Y'));
                        $query->addFilter('<DATE_PAYED', $dateTo->format('d.m.Y'));
                    }
                }
            ],
            'DATE_PAYED_MONTH' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {

                    if ($condValue) {
                        $month = $condValue;
                        $year = $filters['DATE_PAYED_YEAR'];

                        $dateFrom = new \DateTime();
                        $dateFrom->setDate($year, $month, 1);

                        $dateTo = clone $dateFrom;
                        $dateTo->modify('last day of this month');

                        $query->addFilter('>=DATE_PAYED', $dateFrom->format('d.m.Y'));
                        $query->addFilter('<DATE_PAYED', $dateTo->format('d.m.Y'));

                        //die(json_encode([$dateFrom->format('d.m.Y  H:i:s'), $dateTo->format('d.m.Y  H:i:s')]));

                    }
                }
            ],
            'DATE_PAYED_DAY' => [
                'CALLBACK' => function (&$result, $condValue, OrderOrmQuery &$query, $filters) {


                    if ($condValue) {
                        $day = $condValue;
                        $month = $filters['DATE_PAYED_MONTH'];
                        $year = $filters['DATE_PAYED_YEAR'];

                        $dateFrom = new \DateTime();
                        $dateFrom->setDate($year, $month, $day);
                        $dateFrom->setTime(00, 00, 00);

                        $dateTo = new \DateTime();
                        $dateTo->setDate($year, $month, $day);
                        $dateTo->setTime(23, 59, 59);

                        $query->addFilter('>=DATE_PAYED', $dateFrom->format('d.m.Y  H:i:s'));
                        $query->addFilter('<=DATE_PAYED', $dateTo->format('d.m.Y  H:i:s'));

                    }
                }
            ]
        ];
    }

    /**
     * @return OrderCollection
     */
    public function getList()
    {
        $this->beforeExec();
        return $this->loadModels($this->execOnce()->fetchAll());
    }

    /**
     * @return OrderModel
     */
    public function getFirst()
    {
        $this->beforeExec();
        return $this->loadModels($this->execOnce()->fetchAll())->first();
    }

    public function loadModels($rows)
    {
        $models = [];

        foreach ($rows as $row)
            $models[$row['ID']] = OrderModel::createFromArray($row);

        return new OrderCollection($models);
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

    function setComplexFilter($where)
    {
        $this->setFiltersProps($this->extractFilterProps($where));
        $this->mergeFilter($where);

        return $this;
    }

    function setClientFilter($where)
    {
        $this->setFiltersProps($this->extractFilterProps($where));
        $this->mergeFilter($this->buildClientFilter($where));

        return $this;
    }

    function setFiltersProps($propFilters = null)
    {

        if (!isset($propFilters)) {
            $propFilters = $this->extractFilterProps($this->filter);
        }

        $arProps = [];

        foreach ($propFilters as $propCode => $propFilter) {
            if ($propFilter['eq']) {
                $arProps[$propCode] = ['filter' => ['op' => '=', 'value' => $propFilter['eq']]];
            } else if ($propFilter['like']) {
                $arProps[$propCode] = ['filter' => ['op' => '%', 'value' => $propFilter['like']]];
            } else if ($propFilter['in']) {
                $arProps[$propCode] = ['filter' => ['op' => 'in', 'value' => $propFilter['in']]];
            } else if ($propFilter['gt']) {
                $arProps[$propCode] = ['filter' => ['op' => '>', 'value' => $propFilter['gt']]];
            } else if ($propFilter['lt']) {
                $arProps[$propCode] = ['filter' => ['op' => '<', 'value' => $propFilter['lt']]];
            }
        }


        if (!empty($arProps)) {

            foreach ($arProps as $index => $arProp) {

                $pfield = 'PROP_' . $index;

                $propName = $arProp['prop'] ?: $index;

                $this->addFilter('=' . $pfield . '.CODE', $propName);

                if ($filter = $arProp['filter']) {
                    $this->addFilter($filter['op'] . $pfield . '.VALUE', $filter['value']);
                }

                if ($select = $arProp['select']) {
                    $this->addSelect($pfield . '.VALUE');
                }

                if ($arProp['order']) {
                    //$qOrder[$pfield . '.VALUE'] = $sortOrder;
                }

                $this->registerRuntimeField(new \Bitrix\Main\Entity\ReferenceField(
                    $pfield,
                    '\Bitrix\sale\Internals\OrderPropsValueTable',
                    ["=this.ID" => "ref.ORDER_ID"],
                    ["join_type" => "left"]
                ));
            }
        }

        return $this;
    }
}
