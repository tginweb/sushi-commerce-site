<?php

namespace Main\Entity\User;

use Bitrix\Main\UserTable;
use Bitrix\Main\UserUtils;
use Main\Entity\Query\BitrixQuery;

/**
 * @method UserQuery active()
 * @method UserQuery fromGroup($groupId)
 */
class UserQuery extends BitrixQuery
{
    public $select = ['FIELDS', 'PROPS'];

    /**
     * Query sort.
     *
     * @var array
     */
    public $sort = ['last_name' => 'asc'];

    /**
     * Query offset.
     *
     * @var int|null
     */
    public $offset = null;


    /**
     * Query limit.
     *
     * @var int|null
     */
    public $limit = null;

    /**
     * List of standard entity fields.
     *
     * @var array
     */
    protected $standardFields = [
        '*',
    ];


    /**
     * Setter for limit.
     *
     * @param int|null $value
     * @return $this
     */
    public function limit($value)
    {
        $this->limit = $value;

        return $this;
    }

    /**
     * Getter for limit.
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the "page number" value of the query.
     *
     * @param int $num
     * @return $this
     */
    public function page($num)
    {
        $num = intval($num) ?: 1;
        return $this->offset((int)$this->limit * ($num - 1));
    }

    /**
     * Setter for offset.
     *
     * @param int|null $value
     * @return $this
     */
    public function offset($value)
    {
        $this->offset = $value;

        return $this;
    }

    public function getPage()
    {
        return $this->offset ? round($this->offset / $this->limit) + 1 : 1;
    }

    /**
     * Get the first user with a given login.
     *
     * @param string $login
     *
     * @return UserModel
     */
    public function getByLogin($login)
    {
        $this->filter['LOGIN_EQUAL_EXACT'] = $login;

        return $this->first();
    }

    /**
     * Get the first user with a given email.
     *
     * @param string $email
     *
     * @return UserModel
     */
    public function getByEmail($email)
    {
        $this->filter['EMAIL'] = $email;

        return $this->first();
    }

    /**
     * Get count of users according the current query.
     *
     * @return int
     */
    public function count()
    {
        if ($this->queryShouldBeStopped) {
            return 0;
        }

        $queryType = 'UserQuery::count';
        $filter = $this->normalizeFilter();
        $callback = function () use ($filter) {
            return UserTable::getCount($filter);
        };

        return $this->handleCacheIfNeeded(compact('queryType', 'filter'), $callback);
    }

    /**
     * Normalize filter before sending it to getList.
     * This prevents some inconsistency.
     *
     * @return array
     */
    protected function normalizeFilter()
    {
        $this->substituteField($this->filter, 'GROUPS', 'GROUPS_ID');
        $this->substituteField($this->filter, 'GROUP_ID', 'GROUPS_ID');

        return $this->filter;
    }

    public function getClientFiltersInfo()
    {
        return [
            'ID' => [
                'TYPE' => 'int'
            ],
            'EMAIL' => [
                'TYPE' => 'string'
            ],
            'ROLES' => [
                'CALLBACK' => function (&$result, $condValue, &$query) {
                    if ($condValue) {
                        if ($roles = $condValue['in']) {

                            $groupIds = array_map(function ($code) {
                                return UserGroupModel::getIdByCode($code);
                            }, $roles);

                            if (!empty($groupIds)) $query->filterGroupId($groupIds);
                        }
                    }
                }
            ],
            'SEARCH' => [
                'CALLBACK' => function (&$result, $condValue, &$query) {
                    if ($condValue) {
                        $this->filter[] = [
                            'LOGIC' => 'OR',
                            UserUtils::getUserSearchFilter(array(
                                'FIND' => $condValue
                            ))
                        ];
                    }
                }
            ],
        ];
    }

    function filterGroupId($groups)
    {
        $this->filter([
            'Bitrix\Main\UserGroupTable:USER.GROUP_ID' => $groups
        ]);
    }

    /**
     * Get the collection of users according to the current query.
     *
     * @return UserCollection
     */
    protected function loadModels()
    {
        $queryType = 'UserQuery::getList';
        $sort = $this->sort;
        $filter = $this->normalizeFilter();


        $props = $this->propsMustBeSelected() ? ['UF_*'] : ($this->normalizeUfSelect() ?: []);

        $select = array_merge($this->normalizeSelect(), $props);

        $selectGroups = $this->groupsMustBeSelected();

        $keyBy = $this->keyBy;

        $callback = function () use ($sort, $filter, $select, $selectGroups) {

            $users = [];

            $rsUsers = UserTable::getList([
                "select" => $select,
                "filter" => $filter,
                'order' => $sort,
                'offset' => $this->offset,
                'limit' => $this->limit,
            ]);

            while ($arUser = $rsUsers->Fetch()) {
                $this->addItemToResults($users, new $this->modelName($arUser['ID'], $arUser));
            }

            return new UserCollection($users);
        };

        return $this->handleCacheIfNeeded(compact('queryType', 'sort', 'filter', 'selectGroups', 'keyBy'), $callback);
    }

    /**
     * Normalize select UF before sending it to getList.
     *
     * @return array
     */
    protected function normalizeUfSelect()
    {
        return preg_grep('/^(UF_+)/', $this->select);
    }

    /**
     * Normalize select before sending it to getList.
     * This prevents some inconsistency.
     *
     * @return array
     */
    protected function normalizeSelect()
    {
        if ($this->fieldsMustBeSelected()) {
            $this->select = array_merge($this->standardFields, $this->select);
        }

        $this->select[] = 'ID';

        return $this->clearSelectArray();
    }

    /**
     * Determine if groups must be selected.
     *
     * @return bool
     */
    protected function groupsMustBeSelected()
    {
        return in_array('GROUPS', $this->select) || in_array('GROUP_ID', $this->select) || in_array('GROUPS_ID', $this->select);
    }

    protected function prepareMultiFilter(&$key, &$value)
    {
        $value = join(' | ', $value);
    }
}
