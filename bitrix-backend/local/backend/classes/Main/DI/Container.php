<?php

namespace Main\DI;

class Container extends ContainerBase
{
    use \Main\ModuleTrait;
    use \Search\Core\ModuleTrait;
    use \TG\Captcha\Core\ModuleTrait;
    use \Geo\Core\ModuleTrait;
    use \Mail\Core\ModuleTrait;
    use \Event\Core\ModuleTrait;
    use \Menu\Core\ModuleTrait;
    use \Offer\Core\ModuleTrait;
    use \Telegram\Core\ModuleTrait;
    use \Page\Core\ModuleTrait;
    use \Shop\Core\ModuleTrait;

    function getApp()
    {
        return Request::getApp();
    }
}

