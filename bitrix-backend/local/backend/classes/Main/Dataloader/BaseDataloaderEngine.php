<?php

namespace Main\Dataloader;

use Overblog\DataLoader\DataLoader as VendorDataLoader;

abstract class BaseDataloaderEngine extends VendorDataLoader
{
    function getLoader()
    {
        $this->load();
    }
}
