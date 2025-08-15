<?php

namespace Main\Entity\User;

use CGroup;
use Main\Entity\Query\BitrixQuery;

/**
 * @method UserQuery active()
 * @method UserQuery fromGroup($groupId)
 */
class UserGroupQuery extends BitrixQuery
{
    /**
     * Query sort.
     *
     * @var array
     */
    public $sort = ['NAME' => 'ASC'];

    /**
     * List of standard entity fields.
     *
     * @var array
     */
    protected $standardFields = [
        'ID',
        'NAME',
    ];

    public function count()
    {
        $filter = $this->filter;

        $rsGroups = CGroup::GetList($orderField, $orderDir, $filter);

        $count = 0;
        while ($row = $rsGroups->fetch()) {
            $count++;
        }

        return $count;
    }

    /**
     * Get the collection of users according to the current query.
     *
     * @return UserGroupCollection
     */
    protected function loadModels()
    {
        $filter = $this->filter;

        $items = [];
        $orderField = 'ID';
        $orderDir = 'ASC';

        $rsGroups = CGroup::GetList($orderField, $orderDir, $filter);

        while ($row = $rsGroups->fetch()) {
            $this->addItemToResults($items, new $this->modelName($row['ID'], $row));
        }

        return new UserGroupCollection($items);
    }
}
