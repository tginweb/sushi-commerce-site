<?php

namespace Offer\Core\Entity;

use Main\Entity\IBlock\ElementModel;

class OfferModel extends ElementModel
{
    const ENTITY_TYPE = 'offer';

    public $contextVars = [];

    public function setContextVars($vars = [])
    {
        $this->contextVars += $vars;
        return $this;
    }

    public function getContextVars()
    {
        $res = $this->contextVars + [
                'PERCENT' => $this->getProp('PERCENT'),
                'PERCENT_FULL' => $this->getProp('PERCENT') . '%'
            ];
        return $res;
    }

    public function getUrl()
    {
        return '/review/' . $this['ID'];
    }

    public function replaceContentVars($content)
    {
        $content = preg_replace('/#.+#/', '', $content);
        return $content;
    }

    public function getSlides()
    {
        $slides = [];
        $bgColor = $this->getProp('BG_COLOR');

        foreach ($this->getProp('SLIDES', 'VALUE', true) as $slideImageId) {
            $slide = new OfferSlideModel();
            $slide['ID'] = $slideImageId;
            $slide['NAME'] = $this['NAME'];
            $slide['CONTENT_TYPE'] = $this->getProp('CONTENT_TYPE', 'XML_ID') ?: 'image';
            $slide['CONTENT_IMAGE'] = $slideImageId;
            $slide['CONTENT_HTML'] = '';
            $slide['BG_COLOR'] = $bgColor;
            $slides[] = $slide;
        }
        return $slides;
    }

    static function getPropsInfo()
    {
        return [];
    }

    function getActionSection()
    {
        if ($sectionId = $this->getProp('ACTION_SECTION_ID')) {
            $rs = \CIBlockSection::getList([], ['ID' => $sectionId], false, ['ID', 'CODE', 'NAME']);
            while ($row = $rs->fetch()) {
                return $row;
            }
        }
    }

    function getActions($platform = 'web')
    {
        $res = [];

        $actionSection = $this->getActionSection();

        if ($platform === 'mobile') {

            $actionUrl = $this->getProp('ACTION_MOBILE_URL');
            $actionProps = $this->getProp('ACTION_MOBILE_PROPS') ?: [];

            if ($actionUrl) {
                $res[] = $actionProps + [
                        'label' => $this->getProp('ACTION_TITLE'),
                        'action' => [
                            'url' => $actionUrl
                        ],
                    ];
            } else if ($actionSection) {
                $res[] = $actionProps + [
                        'label' => 'перейти в раздел меню',
                        'action' => [
                            'url' => 'catalog://section.' . $actionSection['ID'],
                        ],
                    ];
            }

        } else {
            $actionUrl = $this->getProp('ACTION_WEB_URL');
            $actionProps = $this->getProp('ACTION_WEB_PROPS') ?: [];

            if ($actionUrl) {
                $res[] = $actionProps + [
                        'label' => $this->getProp('ACTION_TITLE'),
                        'url' => $actionUrl
                    ];
            } else if ($actionSection) {
                $res[] = $actionProps + [
                        'label' => 'Открыть меню в каталоге',
                        'url' => '/#catalog--' . $actionSection['CODE']
                    ];
            }
        }

        return $res;
    }

    public function isHot()
    {
        return !!$this->getProp('IS_HOT');
    }

    public function getViewMode()
    {
        return 'sheet';
    }

    public function getVid()
    {
        return $this['VID'] ?: $this['ID'];
    }
}
