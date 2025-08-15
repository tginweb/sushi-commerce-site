<?php

namespace Main\Entity\D7;

use Bitrix\Main\ORM;
use voku\helper\Hooks;

class OrmQuery extends Orm\Query\Query
{
    public $queryName;
    public $clientWhere;
    public $result;

    public function beforeExec()
    {
        Hooks::getInstance()->do_action_ref_array('query:before_exec:' . $this->queryName, [$this]);
    }

    public function getClientWhere()
    {
        return $this->clientWhere;
    }

    public function setClientWhere($where = [])
    {
        if (isset($where)) {
            $this->clientWhere = $where;
        }
        return $this;
    }

    function setQueryName($name)
    {
        $this->queryName = $name;
        return $this;
    }

    function setClientQuery($query, $options = [])
    {
        $query += [
            'nav' => []
        ];

        $options += [
            'nav' => []
        ];

        $this->setClientNav($query['nav'], $options['nav']);

        if (isset($query['select']))
            $this->setSelect($query['select']);

        if (isset($query['filter']))
            $this->setClientFilter($query['filter']);

        if (isset($query['where']))
            $this->setClientWhere($query['where']);

        return $this;
    }

    function setClientNav($nav, $options = [])
    {
        $options += [
            'limitMax' => 50,
            'limitDefault' => 10
        ];

        $nav += [
            'page' => 1,
            'order' => $options['orderDefault'] ?? null,
            'limit' => $options['limitDefault']
        ];

        if ($nav['limit'] > $options['limitMax'])
            $nav['limit'] = $options['limitMax'];

        $this->forPage($nav['page'], $nav['limit']);

        if (isset($nav['order']))
            $this->setOrder($nav['order']);

        return $this;
    }

    /**
     * Set the limit and offset for a given page.
     *
     * @param int $page
     * @param int $perPage
     * @return $this
     */
    public function forPage($page, $perPage = 15)
    {
        return $this->setLimit($perPage)->page($page);
    }

    /**
     * @param int $num
     * @return $this
     */
    public function page($num)
    {
        $num = intval($num) ?: 1;
        return $this->setOffset((int)$this->limit * ($num - 1));
    }

    function setClientFilter($where)
    {
        $this->setFilter($this->buildClientFilter($where));
        return $this;
    }

    function buildClientFilter($where)
    {
        $result = [];

        $filters = $this->getClientFiltersInfo();

        foreach ($where as $fieldName => $cond) {

            $filter = $filters[$fieldName];

            $this->buildClientFilterItem($result, $fieldName, $cond, $filter, $where);
        }


        return $result;
    }

    public static function getClientFiltersInfo()
    {
        return [];
    }

    function buildClientFilterItem(&$result, $fieldName, $cond, $filter, $filters)
    {

        if ($filter['TYPE']) {

            if (is_array($cond)) {
                foreach ($cond as $op => $val) {

                    if (!$val) continue;

                    switch ($op) {
                        case 'lt':
                            $result['<' . $fieldName] = $cond['lt'];
                            break;
                        case 'gt':
                            $result['>' . $fieldName] = $cond['gt'];
                            break;
                        case 'eq':
                            $result[$fieldName] = $cond['eq'];
                            break;
                        case 'ne':
                            $result['!' . $fieldName] = $cond['ne'];
                            break;
                        case 'in':
                            $result[$fieldName] = $cond['in'];
                            break;
                        case 'nin':
                            $result['!' . $fieldName] = $cond['nin'];
                            break;
                        case 'like':
                            $result['%' . $fieldName] = $cond['like'];
                            break;
                    }
                }
            } else {
                $result[$fieldName] = $cond;
            }

        } else if ($filter['CALLBACK']) {
            $filter['CALLBACK']($result, $cond, $this, $filters);
        }
    }

    function setClientQueryOne($query, $options = [])
    {
        $query += [
            'nav' => []
        ];

        $options += [

        ];

        if (isset($query['select']))
            $this->setSelect($query['select']);

        if (isset($query['filter']))
            $this->setClientFilter($query['filter']);

        $this->setLimit(1);

        return $this;
    }

    public function first()
    {
        $list = $this->setLimit(1)->getList();
        if (count($list)) {
            return $list->current();
        }
    }

    public function getList()
    {
        return $this->execOnce()->fetchCollection();
    }

    public function execOnce()
    {
        if (!isset($this->result)) {
            $this->result = $this->exec();
        }
        return $this->result;
    }

    public function getGraph()
    {
        $result = [];

        $nodes = $this->getList();

        $total = $this->execOnce()->getCount() ?: count($nodes);

        $result['nodes'] = $nodes;

        if ($this->getPage()) {
            $page = $this->getPage();
        } else {
            $page = 1;
        }

        if ($this->getLimit()) {
            $perPage = $this->getLimit();
        } else {
            $perPage = $total;
        }

        $pages = ceil($total / $perPage);

        $result['info'] = [
            'total' => $total,
            'limit' => $perPage,
            'pages' => $pages,
            'page' => $page,
            'nextPage' => $page < $pages ? $page + 1 : null,
        ];

        return $result;
    }

    public function getPage()
    {
        return $this->offset ? round($this->offset / $this->limit) + 1 : 1;
    }

    public function mergeFilter(array $filter)
    {
        $this->filter = array_merge($this->filter, $filter);
        return $this;
    }

}
