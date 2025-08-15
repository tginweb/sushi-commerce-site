<?php

namespace Main\Service;

use Bitrix;
use Exception;
use Main\Provider\ImageStyler\Base;

class ImageService extends BaseService
{
    const STYLES = [
        'r30' => ["op" => "resize", "args" => [30, 40]],
        'r70' => ["op" => "resize", "args" => [70, 1000]],
        'r100' => ["op" => "resize", "args" => [100, 1000]],
        'r200' => ["op" => "resize", "args" => [200, 1000]],
        'r300' => ["op" => "resize", "args" => [300, 1000]],
        'r400' => ["op" => "resize", "args" => [400, 1000]],
        'r500' => ["op" => "resize", "args" => [500, 1000]],
        'r600' => ["op" => "resize", "args" => [600, 1000]],
        'r700' => ["op" => "resize", "args" => [700, 1000]],
        'r800' => ["op" => "resize", "args" => [800, 1000]],

        'rslider' => ["op" => "resize", "args" => [1500, 1500]],
        'rview' => ["op" => "resize", "args" => [1800, 1200]],

        's1' => ["op" => "crop", "args" => [220, 220]], // 1

        'm0.7' => ["op" => "crop", "args" => [510, 728]], // 1
        'm1' => ["op" => "crop", "args" => [500, 500]], // 1
        'm1.25' => ["op" => "crop", "args" => [500, 400]], // 1.25
        'm1.33' => ["op" => "crop", "args" => [500, 375]], // 1.33
        'm1.4' => ["op" => "crop", "args" => [500, 357]], // 1.4
        'm1.5' => ["op" => "crop", "args" => [500, 333]], // 1.5
        'm1.6' => ["op" => "crop", "args" => [500, 312]], // 1.6
        'm1.78' => ["op" => "crop", "args" => [500, 281]], // 1.78
        'm1.9' => ["op" => "crop", "args" => [500, 263]], // 1.9
        'm2' => ["op" => "crop", "args" => [500, 250]], // 2
        'm2.2' => ["op" => "crop", "args" => [500, 227]], // 2.2
        'm2.4' => ["op" => "crop", "args" => [500, 208]], // 2.4
        'm2.6' => ["op" => "crop", "args" => [500, 192]], // 2.6
        'm2.75' => ["op" => "crop", "args" => [500, 181]], // 2.75
        'm3' => ["op" => "crop", "args" => [500, 166]], // 3
        'm3.2' => ["op" => "crop", "args" => [500, 156]], // 3
        'm3.8' => ["op" => "crop", "args" => [500, 131]], // 3

        't0.7' => ["op" => "crop", "args" => [900, 1285]], // 1
        't1' => ["op" => "crop", "args" => [900, 900]], // 1
        't1.25' => ["op" => "crop", "args" => [900, 720]], // 1.25
        't1.33' => ["op" => "crop", "args" => [900, 676]], // 1.33
        't1.4' => ["op" => "crop", "args" => [900, 642]], // 1.4
        't1.5' => ["op" => "crop", "args" => [900, 600]], // 1.5
        't1.6' => ["op" => "crop", "args" => [900, 562]], // 1.6
        't1.78' => ["op" => "crop", "args" => [900, 505]], // 1.78
        't1.9' => ["op" => "crop", "args" => [900, 473]], // 1.9
        't2' => ["op" => "crop", "args" => [900, 450]], // 2
        't2.2' => ["op" => "crop", "args" => [900, 409]], // 2.2
        't2.4' => ["op" => "crop", "args" => [900, 375]], // 2.4
        't2.6' => ["op" => "crop", "args" => [900, 346]], // 2.6
        't2.75' => ["op" => "crop", "args" => [900, 327]], // 2.75
        't3' => ["op" => "crop", "args" => [900, 300]], // 3

        'd1' => ["op" => "crop", "args" => [1300, 1300]],
        'd1.25' => ["op" => "crop", "args" => [1300, 1040]],
        'd1.33' => ["op" => "crop", "args" => [1300, 977]],
        'd1.4' => ["op" => "crop", "args" => [1300, 928]],
        'd1.5' => ["op" => "crop", "args" => [1300, 866]],
        'd1.6' => ["op" => "crop", "args" => [1300, 812]],
        'd1.78' => ["op" => "crop", "args" => [1300, 730]],
        'd1.9' => ["op" => "crop", "args" => [1300, 684]],
        'd2' => ["op" => "crop", "args" => [1300, 650]],
        'd2.2' => ["op" => "crop", "args" => [1300, 590]],
        'd2.4' => ["op" => "crop", "args" => [1300, 541]],
        'd2.6' => ["op" => "crop", "args" => [1300, 500]],
        'd2.75' => ["op" => "crop", "args" => [1300, 472]],
        'd3' => ["op" => "crop", "args" => [1300, 433]],
    ];
    public $config = [];
    public $providers = [];
    public $styles;

    function register($scopes = [])
    {
        $this->config = $this->container->getConfigService()->get('IMAGE');
    }

    /**
     * @return Base
     */
    function getStylerProvider($name = 'default')
    {
        if (isset($this->providers[$name])) {
            $provider = $this->providers[$name];
        } else if ($providerInfo = $this->config['STYLER_PROVIDERS'][$name]) {
            $provider = $this->providers[$name] = new $providerInfo['class']($providerInfo);
        }

        if (!$provider)
            throw new Exception('Styler provider not found');

        return $provider;
    }

    function getStyleInfo($name)
    {
        $this->getStylesInfo();
        return $this->styles[$name] ?? null;
    }

    function getStylesInfo()
    {
        if (!isset($this->styles)) {
            $this->styles = static::STYLES;
        }
        return $this->styles;
    }
}



