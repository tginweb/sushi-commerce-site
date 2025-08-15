<?php

namespace Main\Graphql;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use IteratorAggregate;
use Main\Dataloader\BaseDataloader;
use Main\Entity\Model\Traits\ArrayAccessTrait;
use Overblog\DataLoader\Promise\Adapter\Webonyx\GraphQL\SyncPromiseAdapter;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;

class ExecutionContext implements ArrayAccess, Arrayable, Jsonable, IteratorAggregate
{
    use ArrayAccessTrait;

    public $fields = [
        'loader' => [],
        'root' => [
            'args' => []
        ]
    ];

    public $promiseAdapter;
    public $dataloaderPromiseAdapter;

    public function __construct($fields = [])
    {
        $this->fields += $fields;
        $this->fields['promiseAdapter'] = $this->getPromiseAdapter();

        /** @var BaseDataloader $instance */
        foreach (Types::getDataloaders() as $key => $instance) {
            $instance->setPromiseAdapter($this->getDataLoaderPromiseAdapter());
            $this->fields['dataloader'][$key] = $instance->getLoader();
            $this->fields['loader'][$key] = $instance;
        }
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    public function toArray()
    {
        return $this->fields;
    }

    function getPromiseAdapter()
    {
        if (!$this->promiseAdapter) {
            $this->promiseAdapter = new SyncPromiseAdapter();
        }
        return $this->promiseAdapter;
    }

    function getDataLoaderPromiseAdapter()
    {
        if (!$this->dataloaderPromiseAdapter) {
            $this->dataloaderPromiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($this->getPromiseAdapter());
        }
        return $this->dataloaderPromiseAdapter;
    }

    public function setRootArgs($args)
    {
        $this->fields['root'] = [
            'args' => $args
        ];
    }

    public function getRootArg($name = null, $def = null)
    {
        return $name ? ($this->fields['root']['args'][$name] ?? $def) : $this->fields['root']['args'];
    }

    public function getViewMode($viewmode = null, $detailArgName = 'detail', $viewArgName = 'viewmode')
    {
        if ($viewmode)
            return $viewmode;

        if (isset($this->fields['root']['args'][$detailArgName]) && $this->fields['root']['args'][$detailArgName]) {
            return 'detail';
        }

        if (isset($this->fields['root']['args'][$viewArgName])) {
            return $this->fields['root']['args'][$viewArgName];
        }
    }
}
