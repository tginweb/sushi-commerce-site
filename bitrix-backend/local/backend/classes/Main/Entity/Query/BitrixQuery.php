<?php

namespace Main\Entity\Query;

use CDBResult;
use Main\DI\Containerable;
use Main\Service\IBlockService;

abstract class BitrixQuery extends BaseQuery
{
    use Containerable;

    /**
     * Query select.
     *
     * @var array
     */
    //public $select = ['FIELDS', 'PROPS'];
    public $select = ['FIELDS'];

    /**
     * Fetch method and parameters.
     *
     * @var array
     */
    protected $fetchUsing;

    /**
     * Constructor.
     *
     * @param object $bxObject
     * @param string $modelName
     */
    public function __construct($bxObject, $modelName)
    {
        parent::__construct($bxObject, $modelName);

        $this->fetchUsing($modelName::$fetchUsing);
    }

    /**
     * Set fetch using from string or array.
     *
     * @param string|array $methodAndParams
     * @return $this
     */
    public function fetchUsing($methodAndParams)
    {
        // simple case
        if (is_string($methodAndParams) || empty($methodAndParams['method'])) {
            $this->fetchUsing = in_array($methodAndParams, ['GetNext', 'getNext'])
                ? ['method' => 'GetNext', 'params' => [true, true]]
                : ['method' => 'Fetch'];
            return $this;
        }

        // complex case
        if (in_array($methodAndParams['method'], ['GetNext', 'getNext'])) {
            $bTextHtmlAuto = isset($methodAndParams['params'][0]) ? $methodAndParams['params'][0] : true;
            $useTilda = isset($methodAndParams['params'][1]) ? $methodAndParams['params'][1] : true;
            $this->fetchUsing = ['method' => 'GetNext', 'params' => [$bTextHtmlAuto, $useTilda]];
        } else {
            $this->fetchUsing = ['method' => 'Fetch'];
        }

        return $this;
    }

    static function getIblockService()
    {
        return IBlockService::i();
    }

    public function getModelClass($arItem = null)
    {
        return $this->modelName;
    }

    public function getIblockId()
    {
        return $this->iblockId;
    }

    function resultProcess($items)
    {

        if (isset($this->sort['IDS'])) {

            $result = $items->make();

            $ids = (array)$this->filter['ID'];

            foreach ($ids as $id) {
                if (isset($items[$id]))
                    $result[$id] = $items[$id];
            }

            $items = $result;
        }

        return $items;
    }

    /**
     * Getter for limit.
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->navigation['nPageSize'];
    }

    /**
     * Choose between Fetch() and GetNext($bTextHtmlAuto, $useTilda) and then fetch
     *
     * @param CDBResult $rsItems
     * @return array|false
     */
    protected function performFetchUsingSelectedMethod($rsItems)
    {
        return $this->fetchUsing['method'] === 'GetNext'
            ? $rsItems->GetNext($this->fetchUsing['params'][0], $this->fetchUsing['params'][1])
            : $rsItems->Fetch();
    }
}
