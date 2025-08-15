<?php

namespace Main\Service;

use Main\Graphql\Types;

class EnumService extends BaseService
{
    function getItemField($dictionaryId, $enumCode, $field, $def = null)
    {
        $items = $this->getItems($dictionaryId);
        return $items[$enumCode][$field] ?? $def;
    }

    function getItems($dictionaryId)
    {
        $items = $this->container->applyFiltersCached(
            'enum:' . $dictionaryId,
            [],
            function ($items) {
                foreach ($items as $code => $item) {
                    if (is_string($item)) {
                        $item = [
                            'name' => $item
                        ];
                    }
                    $item['code'] = $code;
                    $items[$code] = $item;
                }
                return $items;
            },
            function () use ($dictionaryId) {
                $cls = Types::$data['enum'][$dictionaryId];
                if ($cls) {
                    return $cls::getOptions();
                }
                return [];
            },
        );

        return $items;
    }

}


