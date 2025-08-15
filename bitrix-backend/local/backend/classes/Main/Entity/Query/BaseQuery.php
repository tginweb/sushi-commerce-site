<?php

namespace Main\Entity\Query;

use BadMethodCallException;
use Closure;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use LogicException;
use Main\DI\Containerable;
use Main\Entity\Model\BaseBitrixModel;
use Main\Entity\Model\ModelCollection;
use voku\helper\Hooks;

abstract class BaseQuery
{
    use BaseRelationQuery;

    public $isEmpty = false;
    public $queryName;
    public $getListCache;
    public $views = [];

    /**
     * Query select.
     *
     * @var array
     */
    public $select = [];
    /**
     * Query params.
     *
     * @var array
     */
    public $params = [];
    /**
     * Query sort.
     *
     * @var array
     */
    public $sort = [];
    /**
     * Query post sort.
     *
     * @var array
     */
    public $resultSort = [];
    /**
     * Query filter.
     *
     * @var array
     */
    public $filter = [];
    /**
     * Query where.
     *
     * @var array
     */
    public $where = [];
    /**
     * Query navigation.
     *
     * @var array|bool
     */
    public $navigation = false;
    /**
     * The key to list items in array of results.
     * Set to false to have auto incrementing integer.
     *
     * @var string|bool
     */
    public $keyBy = 'ID';
    /**
     * Number of minutes to cache a query
     *
     * @var double|int
     */
    public $cacheTtl = 0;
    /**
     * Name of the model that calls the query.
     *
     * @var string
     */
    public $modelName;
    var $incResultRefs = false;
    /**
     * Bitrix object to be queried.
     *
     * @var object|string
     */
    protected $bxObject;
    /**
     * Model that calls the query.
     *
     * @var object
     */
    protected $model;
    protected $collectionClass = ModelCollection::class;
    /**
     * Indicates that the query should be stopped instead of touching the DB.
     * Can be set in query scopes or manually.
     *
     * @var bool
     */
    protected $queryShouldBeStopped = false;

    /**
     * Constructor.
     *
     * @param object|string $bxObject
     * @param string $modelName
     */
    public function __construct($bxObject, $modelName, $collectionClass = null)
    {
        $this->bxObject = $bxObject;
        $this->modelName = $modelName;
        $this->model = new $modelName();

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
    }

    /**
     * Get item by its id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getById($id)
    {
        if (!$id || $this->queryShouldBeStopped) {
            return false;
        }

        $this->sort = [];
        $this->filter['ID'] = $id;

        return $this->first();
    }

    /**
     * Get the first item that matches query params.
     *
     * @return mixed
     */
    public function first()
    {
        return $this->limit(1)->getList()->first(null, null);
    }

    /**
     * Подготавливает запрос и вызывает loadModels()
     *
     * @return ModelCollection
     */
    public function getList($collectionMethod = null, $refetch = false)
    {
        if (isset($this->getListCache) && !$refetch) {
            return $this->getListCache;
        }

        $this->beforeExec();

        if (!is_null($this->primaryModel)) {
            // Запрос - подгрузка релейшена. Надо добавить filter
            $this->filterByModels([$this->primaryModel]);
        }

        if ($this->queryShouldBeStopped) {
            return new $this->collectionClass();
        }

        $models = $this->loadModels($collectionMethod);

        if (!empty($this->with)) {
            $this->findWith($this->with, $models);
        }

        $this->getListCache = $models;

        return $models;
    }

    function beforeExec()
    {
        Hooks::getInstance()->do_action_ref_array('query:before_exec:' . $this->queryName, [$this]);
    }

    /**
     * Get list of items.
     *
     * @return ModelCollection
     */
    abstract protected function loadModels();

    /**
     * Set the "limit" value of the query.
     *
     * @param int $value
     *
     * @return $this
     */
    public function limit($value)
    {
        $this->navigation['nPageSize'] = $value;

        return $this;
    }

    /**
     * Get item by its id.
     *
     * @param string $code
     *
     * @return mixed
     */
    public function getByCode($code)
    {
        if (!$code || $this->queryShouldBeStopped) {
            return false;
        }

        $this->sort = [];
        $this->filter['CODE'] = $code;

        return $this->getList()->first();
    }

    /**
     * Setter for params.
     *
     * @param mixed $param
     * @param mixed $value
     *
     * @return $this
     */
    public function param($param, $value = null)
    {
        $this->params = array_merge($this->params, is_array($param) ? $param : [$param => $value]);

        return $this;
    }

    /**
     * Another setter for sort.
     *
     * @param mixed $by
     * @param string $order
     *
     * @return $this
     */
    public function order($by, $order = 'ASC')
    {
        return $this->sort($by, $order);
    }

    /**
     * Setter for sort.
     *
     * @param mixed $by
     * @param string $order
     *
     * @return $this
     */
    public function sort($by, $order = 'ASC')
    {
        $this->sort = is_array($by) ? $by : [$by => $order];
        return $this;
    }

    /**
     * Reset filter.
     *
     * @return $this
     */
    public function resetFilter()
    {
        $this->filter = [];

        return $this;
    }

    /**
     * Add another filter to filters array.
     *
     * @param array $filters
     *
     * @return $this
     */
    public function addFilter($filters)
    {
        foreach ($filters as $field => $value) {
            $this->filter[$field] = $value;
        }

        return $this;
    }

    function getClientWhere()
    {
        return $this->where;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        return $this;
    }

    public function pushFilter($filter)
    {
        $this->filter[] = $filter;

        return $this;
    }

    function setClientQueryOne($query, $options = [])
    {
        $query += [
            'nav' => []
        ];

        $options += [

        ];

        if (isset($query['select']))
            $this->select($query['select']);

        if (isset($query['filter']))
            $this->setClientFilter($query['filter']);

        $this->limit(1);

        return $this;
    }

    /**
     * Setter for select.
     *
     * @param $value
     *
     * @return $this
     */
    public function select($value)
    {
        $this->select = array_merge($this->select, is_array($value) ? $value : func_get_args());

        return $this;
    }

    function setClientFilter($filter)
    {
        $this->filter($this->buildClientFilter($filter));
        return $this;
    }

    public function filter($filter)
    {
        $this->filter = array_merge($this->filter, $filter);
        return $this;
    }

    public function withViewDetail()
    {
        return $this->withView('detail');
    }

    public function withViewList()
    {
        return $this->withView('list');
    }

    public function withView($viewmode = [])
    {
        $this->views = array_merge($this->views, (array)$viewmode);
        return $this;
    }

    public function getModelAttrsFiltered($view = [])
    {
        $cls = $this->modelName;
        return $cls ? call_user_func([$cls, 'getAttrsFiltered'], $view) : [];
    }

    public function getModelFilters($view = [])
    {
        $cls = $this->modelName;
        return $cls ? call_user_func([$cls, 'getFilters'], $view) : [];
    }

    public function getFilters($view = [])
    {
        return $this->getModelFilters($view);
    }

    public function getClientFilters($view = [])
    {
        return array_filter($this->getFilters($view), function ($attr) {
            return !empty($attr['client']);
        });
    }

    public function buildClientFilter($filters)
    {
        $result = [];
        $attrs = $this->getClientFilters($this->views);

        foreach ($filters as $filterName => $filter) {
            $attr = $attrs[$filterName];
            if ($attr) {
                if (isset($attr['filterFn'])) {
                    $attr['filterFn']($result, $filter, $this);
                } else {
                    foreach ($filter as $filterOp => $filterValue) {
                        if (isset($attr['filterValueTransformer'])) {
                            $filterValue = $attr['filterValueTransformer']($filterValue, $filterOp, $attr);
                        }
                        $this->buildClientFilterItem($result, $attr, $attr['fullName'] ??  $filterName, $filterOp, $filterValue);
                    }
                }
            }
        }

        return $result;
    }

    public function buildClientFilterItem(&$result, $attr, $field, $filterOp, $filterValue)
    {
        $mask = $field;
        switch ($filterOp) {
            case 'lt':
                $mask = '<' . $field;
                break;
            case 'gt':
                $mask = '>' . $field;
                break;
            case 'eq':
                $mask = $field;
                break;
            case 'ne':
                $mask = '!' . $field;
                break;
            case 'in':
                $mask = $field;
                break;
            case 'like':
                $filterValue = '%' . $filterValue . '%';
                break;
        }
        $result[$mask] = $filterValue;
        return $result;
    }

    /**
     * Setter for navigation.
     *
     * @param $value
     *
     * @return $this
     */
    public function navigation($value)
    {
        $this->navigation = $value;

        return $this;
    }

    /**
     * Setter for cache ttl.
     *
     * @param float|int $minutes
     *
     * @return $this
     */
    public function cache($minutes)
    {
        $this->cacheTtl = $minutes;

        return $this;
    }

    /**
     * Setter for keyBy.
     *
     * @param string $value
     *
     * @return $this
     */
    public function keyBy($value)
    {
        $this->keyBy = $value;

        return $this;
    }

    public function paginator($perPage = 15, $page)
    {
        $total = $this->count();
        $results = $this->forPage($page, $perPage)->getList();

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
    }

    /**
     * Get count of users that match $filter.
     *
     * @return int
     */
    abstract public function count();

    /**
     * Set the limit and offset for a given page.
     *
     * @param int $page
     * @param int $perPage
     * @return $this
     */
    public function forPage($page, $perPage = 15)
    {
        return $this->take($perPage)->page($page);
    }

    /**
     * Set the "page number" value of the query.
     *
     * @param int $num
     *
     * @return $this
     */
    public function page($num)
    {
        $this->navigation['iNumPage'] = intval($num) ?: 1;

        return $this;
    }

    /**
     * Alias for "limit".
     *
     * @param int $value
     *
     * @return $this
     */
    public function take($value)
    {
        return $this->limit($value);
    }

    /**
     * Paginate the given query into a paginator.
     *
     * @param int $perPage
     * @param string $pageName
     *
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 15, $pageName = 'page')
    {
        $page = Paginator::resolveCurrentPage($pageName);
        $total = $this->count();
        $results = $this->forPage($page, $perPage)->getList();

        return new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Get a paginator only supporting simple next and previous links.
     *
     * This is more efficient on larger data-sets, etc.
     *
     * @param int $perPage
     * @param string $pageName
     *
     * @return Paginator
     */
    public function simplePaginate($perPage = 15, $pageName = 'page')
    {
        $page = Paginator::resolveCurrentPage($pageName);
        $results = $this->forPage($page, $perPage + 1)->getList();

        return new Paginator($results, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Handle dynamic method calls into the method.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return $this
     * @throws BadMethodCallException
     *
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->model, 'scope' . $method)) {
            array_unshift($parameters, $this);

            $query = call_user_func_array([$this->model, 'scope' . $method], $parameters);

            if ($query === false) {
                $this->stopQuery();
            }

            return $query instanceof static ? $query : $this;
        }

        $className = get_class($this);

        throw new BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }

    /**
     * Stop the query from touching DB.
     *
     * @return $this
     */
    public function stopQuery()
    {
        $this->queryShouldBeStopped = true;

        return $this;
    }

    function getEmptyCollection()
    {
        return new $this->collectionClass([]);
    }

    function wrapCollection($items)
    {
        $cls = $this->getCollectionClass();
        return new $cls($items);
    }

    function getCollectionClass()
    {
        return $this->collectionClass ?: 'Collection';
    }

    function getEmptyGraph()
    {
        return [
            'nodes' => [],
            'info' => [
                'total' => 0,
                'limit' => $this->getLimit(),
                'pages' => 0,
                'page' => 1,
                'nextPage' => null,
            ]
        ];
    }

    public function getLimit()
    {
        return $this->navigation['nPageSize'];
    }

    function getGraph()
    {
        $result = [];

        $result['nodes'] = $this->getList();

        if ($this->getPage()) {
            $page = $this->getPage();
        } else {
            $page = 1;
        }

        $totalCount = $this->count();

        if ($this->getLimit()) {
            $perPage = $this->getLimit();
        } else {
            $perPage = $totalCount;
        }

        $pages = ceil($totalCount / $perPage);

        $result['info'] = [
            'total' => $totalCount,
            'limit' => $perPage,
            'pages' => $pages,
            'page' => $page,
            'nextPage' => $page < $pages ? $page + 1 : null,
        ];

        return $result;
    }

    public function getPage()
    {
        return $this->navigation['iNumPage'] ?? 1;
    }

    function setQueryName($name)
    {
        $this->queryName = $name;
        return $this;
    }

    public function debug($fn)
    {
        $fn($this);
        return $this;
    }

    public function setGraphqlContext($args, &$context)
    {
        $this->setClientQuery($args, ['nav' => ['limitDefault' => 50, 'limitMax' => 500]]);
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

        if (isset($query['id']))
            $this->filter(['ID' => $query['id']]);

        if (isset($query['select']))
            $this->select($query['select']);

        if (isset($query['filter']))
            $this->setClientFilter($query['filter']);

        if (isset($query['where']))
            $this->setClientWhere($query['where']);

        return $this;
    }

    function setClientNav($nav, $options = [])
    {
        $options += [
            'limitMax' => 5000,
            'limitDefault' => 200
        ];

        $nav += [
            'page' => 1,
            'sort' => $options['sort'] ?? null,
            'asc' => $options['asc'] ?? null,
            'limit' => $options['limitDefault']
        ];

        if ($nav['limit'] > $options['limitMax'])
            $nav['limit'] = $options['limitMax'];

        $this->forPage($nav['page'], $nav['limit']);

        $sortField = $nav['sortField'] ?? $nav['sort'];
        $sortAsc = $nav['sortAscending'] ?? $nav['asc'];

        if ($sortField) {
            $this->sort($sortField, $sortAsc ? 'ASC' : 'DESC');
        }

        if (isset($nav['resultSort']))
            $this->resultSort($nav['resultSort'], $nav['resultSortAsc'] ? 'ASC' : 'DESC');

        return $this;
    }

    /**
     * Setter for sort.
     *
     * @param mixed $by
     * @param string $order
     *
     * @return $this
     */
    public function resultSort($by, $order = 'ASC')
    {
        $this->sort = is_array($by) ? $by : [$by => $order];

        return $this;
    }

    function setClientWhere($where)
    {
        $this->where = $where;
    }

    public function withResultRefs()
    {
        $this->incResultRefs = true;
        return $this;
    }

    /**
     * Adds $item to $results using keyBy value.
     *
     * @param $results
     * @param BaseBitrixModel $object
     *
     * @return void
     */
    protected function addItemToResults(&$results, BaseBitrixModel $object)
    {
        $object->queryClass = get_called_class();

        $item = $object->fields;
        if (!array_key_exists($this->keyBy, $item)) {
            throw new LogicException("Field {$this->keyBy} is not found in object");
        }

        $keyByValue = $item[$this->keyBy];

        if (!isset($results[$keyByValue])) {
            $results[$keyByValue] = $object;
        } else {
            $oldFields = $results[$keyByValue]->fields;

            foreach ($oldFields as $field => $oldValue) {
                // пропускаем служебные поля.
                if (in_array($field, ['_were_multiplied', 'PROPERTIES'])) {
                    continue;
                }

                $alreadyMultiplied = !empty($oldFields['_were_multiplied'][$field]);

                // мультиплицируем только несовпадающие значения полей
                $newValue = $item[$field];
                if ($oldValue !== $newValue) {
                    // если еще не мультиплицировали поле, то его надо превратить в массив.
                    if (!$alreadyMultiplied) {
                        $oldFields[$field] = [
                            $oldFields[$field]
                        ];
                        $oldFields['_were_multiplied'][$field] = true;
                    }

                    // добавляем новое значению поле если такого еще нет.
                    if (empty($oldFields[$field]) || (is_array($oldFields[$field]) && !in_array($newValue, $oldFields[$field]))) {
                        $oldFields[$field][] = $newValue;
                    }
                }
            }

            $results[$keyByValue]->fields = $oldFields;
        }
    }

    protected function fieldsMustBeSelected()
    {
        return in_array('FIELDS', $this->select);
    }

    protected function propsMustBeSelected()
    {
        return in_array('PROPS', $this->select)
            || in_array('PROPERTIES', $this->select)
            || in_array('PROPERTY_VALUES', $this->select);
    }

    protected function substituteField(&$array, $old, $new)
    {
        if (isset($array[$old]) && !isset($array[$new])) {
            $array[$new] = $array[$old];
        }

        unset($array[$old]);
    }

    protected function clearSelectArray()
    {
        $strip = ['FIELDS', 'PROPS', 'PROPERTIES', 'PROPERTY_VALUES', 'GROUPS', 'GROUP_ID', 'GROUPS_ID'];

        return array_values(array_diff(array_unique($this->select), $strip));
    }

    protected function handleCacheIfNeeded($cacheKeyParams, Closure $callback)
    {
        return $this->cacheTtl
            ? $this->rememberInCache(md5(json_encode($cacheKeyParams)), $this->cacheTtl, $callback)
            : $callback();
    }

    protected function rememberInCache($key, $minutes, Closure $callback)
    {
        $minutes = (double)$minutes;

        if ($minutes <= 0) {
            return $callback();
        }

        $key = 'query.' . $key;

        $cacheProvider = Containerable::container()->getCacheService()->getProvider();

        $cacheItem = $cacheProvider->getItem($key, intval($minutes * 60));

        if ($cacheItem->isHit()) {
            $vars = $cacheItem->get();
            return !empty($vars['isCollection']) ? new $this->collectionClass($vars['cache']) : $vars['cache'];
        }

        $result = $callback();

        // Bitrix cache is bad for storing collections. Let's convert it to array.
        $isCollection = $result instanceof ModelCollection;
        if ($isCollection) {
            $result = $result->all();
        }

        $cacheItem->set(['cache' => $result, 'isCollection' => $isCollection]);
        $cacheProvider->saveItem($cacheItem);

        return $isCollection ? new $this->collectionClass($result) : $result;
    }

    protected function prepareMultiFilter(&$key, &$value)
    {

    }

    function setEmptyQuery()
    {
        $this->isEmpty = true;
    }

    function active()
    {
        $this->filter['ACTIVE'] = 'Y';
    }
}
