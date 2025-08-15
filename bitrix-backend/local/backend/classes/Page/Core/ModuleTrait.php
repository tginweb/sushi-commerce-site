<?php

namespace Page\Core;

use Page\Core\Service\PageService;

trait ModuleTrait
{
    /**
     * @return PageService
     */
    function getPageService()
    {
        return $this->get(PageService::class);
    }
}

