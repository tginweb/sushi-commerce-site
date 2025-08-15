<?php

namespace Event\Core\Entity\ClientNotice;

use Event\Core\Entity\ClientNotice;

class ProfileFillGiftClientNotice extends ClientNotice
{
    function fillByType()
    {
        $this->sendPush = 1;
        $this->setShowAsByCode('dialog');
    }

    function getImage()
    {
        return 'icon:gift';
    }

    function getTitle()
    {
        return 'Вы получили ' . $this->getEventData('bonuses') . ' бонусов в подарок за внесение данных в профиль';
    }

    function getMessage()
    {
        return null;
    }

    function getBody()
    {
        return 'Бонусы могут быть использованы в ваших следующих заказах';
    }

    function getBodyHtml()
    {
        return null;
    }

    function getShowAs()
    {
        return 'dialog';
    }

    function getActions($platform = 'web')
    {
        if ($platform === 'web') {
            $action = [
                'label' => 'перейти к каталог',
                'url' => '/'
            ];
        } else {
            $action = [
                'label' => 'перейти к каталог',
                'action' => [
                    'url' => 'catalog://index'
                ]
            ];
        }

        return [$action];
    }
}
