<?php

namespace Main\Dataloader;

use Main\Lib\Common\Base;
use Overblog\DataLoader\DataLoader;
use Overblog\DataLoader\Option;

abstract class BaseDataloader extends Base
{

    var $field;
    public $promiseAdapter;

    public $dataloader;

    function getLoader()
    {
        if (!$this->dataloader) {
            $this->dataloader = $this->createLoader();
        }
        return $this->dataloader;
    }

    function createLoader()
    {
        return new DataLoader([$this, 'batchWrap'], $this->getPromiseAdapter(), new Option(['cache' => false]));
    }

    function getPromiseAdapter()
    {
        return $this->promiseAdapter;
    }

    function setPromiseAdapter($adapter)
    {
        $this->promiseAdapter = $adapter;
    }

    function batchWrap($keys)
    {
        $res = $this->batch($keys);

        return $res;
    }

    function batch($keys)
    {
        return $keys;
    }

    function extractKeys($data)
    {
        $keys = [];

        foreach ($data as $item) {
            list($type, $entity) = explode(':', $item);

            if (!$entity) {
                $entity = $type;
                $type = null;
            }

            list($id, $view, $field) = explode('.', $entity);

            $keys[] = $type ? $type . ':' . $id : $id;
        }

        return [
            $keys,
            $field,
            $view
        ];
    }
}
