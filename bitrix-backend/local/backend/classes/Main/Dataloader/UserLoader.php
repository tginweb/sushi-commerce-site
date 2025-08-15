<?php

namespace Main\Dataloader;

use Main\Entity\User\UserModel;
use Main\Registry;
use Overblog\DataLoader\DataLoader;

class UserLoader extends BaseDataLoader
{

    function __construct()
    {
        Registry::dataloader('user', $this);
    }

    function createLoader()
    {
        return new DataLoader([$this, 'batch'], $this->getPromiseAdapter());
    }

    function batch($keys)
    {
        $users = UserModel::query()->filter(["ID" => $keys])->select(['PROPS'])->getList();

        return $this->getPromiseAdapter()->createAll(array_map(function ($id) use ($users) {
            return $users[$id];
        }, $keys));
    }
}




