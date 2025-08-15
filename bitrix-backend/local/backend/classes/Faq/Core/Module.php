<?php

namespace Faq\Core;

use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;

class Module extends BaseModule
{
    function register($scopes = [])
    {
        parent::register($scopes);
    }

    function registerTypes()
    {
        $gql = $this->container->getRegistryService();

        Types::type('FaqElement', \Faq\Core\Graphql\FaqQuestionType::class);
    }

}
