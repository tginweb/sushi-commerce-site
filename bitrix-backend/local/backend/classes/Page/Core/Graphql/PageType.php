<?php

namespace Page\Core\Graphql;

use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Types;
use Page\Core\Entity\Page;

class PageType extends ElementType
{
    const NAME = 'Page';

    static function iblockId()
    {
        return Page::getIblockIdOrThrow();
    }

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                'DATA_CHUNKS' => Types::listOf(Types::get(DataChunkType::class)),
                'CONTENT_CHUNKS' => Types::listOf(Types::get(ContentChunkType::class)),
            ];
    }

    function resolve_META($element, $args, $context)
    {
        $result = [
            'TITLE' => $element['PROPERTY_META_TITLE_VALUE'],
            'DESCRIPTION' => $element['PROPERTY_META_DESCRIPTION_VALUE'],
            'KEYWORDS' => $element['PROPERTY_META_KEYWORDS_VALUE'],
        ];
        return $result;
    }

    public function resolve_DATA_CHUNKS($parent, $args, $ctx)
    {
        return $parent->getDataChunks();
    }

    public function resolve_CONTENT_CHUNKS($parent, $args, $ctx)
    {
        return array_map(function ($item) {
            $itemFields = $item['VALUE']['SUB_VALUES'];
            return [
                'GROUP' => $itemFields['CHUNK_GROUP']['VALUE'],
                'NAME' => $itemFields['CHUNK_NAME']['VALUE'],
                'CODE' => $itemFields['CHUNK_CODE']['VALUE'],
                'VALUE' => html_entity_decode(FormatText($itemFields['CHUNK_VALUE']['VALUE']['TEXT'], $itemFields['CHUNK_VALUE']['VALUE']['TYPE'])),
            ];
        }, $parent['PROPERTY_CHUNKS'] ?? []);
    }
}
