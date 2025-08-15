<?php

namespace Main\Service;

use Bitrix;
use TG\Main\Helper;

class FileService extends BaseService
{

    public function getGenerateLink($handlerName, $entityType, $entityId, $field, $format = 'word')
    {
        $query = [
            'handler' => $handlerName,
            'entityType' => $entityType,
            'entityId' => $entityId,
            'field' => $field,
            'format' => $format,
        ];
        return \Main\Helper\Str::makeUrl('/file/generate', $query);
    }
}



