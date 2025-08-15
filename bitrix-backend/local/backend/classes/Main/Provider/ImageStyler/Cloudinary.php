<?php

namespace Main\Provider\ImageStyler;

use TG\Main\Helper;

class Cloudinary extends Base
{

    function style($imageUrl, $style = null)
    {
        $width = null;
        $height = null;
        $crop = false;

        $service = $this->container->getImageService();

        if (is_numeric($style)) {
            $width = $style;
            $height = 1000;
        } else if (is_string($style)) {
            $styleInfo = $service->getStyleInfo($style);
            if ($styleInfo) {
                list($width, $height) = $styleInfo['args'];
                $crop = $styleInfo['op'] === 'crop';
            }
        } else {
            list($width, $height, $crop) = $style;
        }

        $params = [];

        $params['f'] = 'auto';
        $params['c'] = $crop ? 'thumb' : 'limit';

        if ($width)
            $params['w'] = $width;

        if ($height)
            $params['h'] = $height;

        $parts = [];

        foreach ($params as $paramKey => $paramValue) {
            $parts[] = $paramKey . '_' . $paramValue;
        }

        $isAbsolute = $imageUrl[0] !== '/';

        $imageUrlFull = \Main\Helper\Str::makeUrl($imageUrl, [], [
            'absolute' => !$isAbsolute,
            'production' => true
        ]);

        return 'https://res.cloudinary.com/' . $this->getCloudId() . '/image/fetch/' . join(',', $parts) . '/' . $imageUrlFull;
    }

    function getCloudId()
    {
        return $this->conf['cloudId'];
    }

    function getTemplate()
    {
        return 'https://res.cloudinary.com/' . $this->getCloudId() . '/image/fetch/#params#/#imageUrl#';
    }
}
