<?php

namespace Faq\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class FaqQuestion extends ElementModel
{
    public function getUrl()
    {
        return '/faq/' . $this['CODE'];
    }

    public function getActions()
    {
        $result = [];

        $urls = $this->getProp('ACTIONS', 'VALUE', true);
        $labels = $this->getProp('ACTIONS', 'DESCRIPTION', true);

        foreach ($urls as $i => $url) {
            $result[] = [
                'action' => [
                    'url' => $url
                ],
                'label' => $labels[$i],
            ];
        }

        //die(\Safe\json_encode($result));

        return $result;
    }

    static function getPropsInfo()
    {
        return [
            'OFFICE' => ['view' => true],
            'JOB' => ['view' => true],
            'PHONE' => ['view' => true],
            'HINT' => ['view' => true],
            'PHONE_WHATSAPP' => ['view' => true],
            'EMAIL' => ['view' => true],
        ];
    }
}
