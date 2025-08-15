<?php

namespace Main\Service;

use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;
use Main\Lib\Date\DateDate;
use Main\Lib\Date\DateTime;

class GraphqlHelperService extends BaseService
{

    function resolveFile($parent, $args, $context, $info)
    {
        $value = $parent[$info->fieldName] ?? null;

        if (!$value || is_array($value) || is_object($value) || !is_numeric($value))
            return $value;

        return $context['dataloader']['image']->load($value);
    }

    function resolveBoolean($parent, $args, $context, $info)
    {
        $value = $parent[$info->fieldName] ?? null;
        return !(!$value || in_array($value, ['N', 'n', '0', 0], true));
    }

    function resolveDate($parent, $args, $context, $info)
    {
        $value = $parent[$info->fieldName] ?? null;
        $date = DateDate::parse($value);
        return $date ? $date->formatted($args['format'] ?? 'timestamp') : null;
    }

    function resolveDatetime($parent, $args, $context, $info)
    {
        $value = $parent[$info->fieldName] ?? null;
        $date = DateTime::parse($value);
        return $date ? $date->formatted($args['format'] ?? 'timestamp') : null;
    }

    function getGraphTypeByAttr($attr, $withResolver = true)
    {
        if ($attr['gqlType'])
            return $attr['gqlType'];

        switch ($attr['type']) {
            case 'number':
            case 'numeric':
            case 'int':
                return Types::int();
            case 'double':
            case 'float':
            case 'decimal':
                return Types::float();
            case 'string':
                return Types::string();
            case 'text':
                return [
                    'type' => Types::string(),
                    'args' => [
                        'format' => Types::boolean(),
                    ]
                ];
            case 'date':
                return [
                    'type' => Types::JSON(),
                    'args' => [
                        'format' => Types::string(),
                    ],
                    'resolve' => $withResolver ? [$this, 'resolveDate'] : null
                ];
            case 'datetime':
                return [
                    'type' => Types::JSON(),
                    'args' => [
                        'format' => Types::string(),
                    ],
                    'resolve' => $withResolver ? [$this, 'resolveDatetime'] : null
                ];
            case 'bool':
            case 'boolean':
                return [
                    'type' => Types::boolean(),
                    'resolve' => $withResolver ? [$this, 'resolveBoolean'] : null
                ];
            case 'file':
            case 'image':
                return [
                    'type' => Types::get(ImageType::class),
                    'resolve' => $withResolver ? [$this, 'resolveFile'] : null
                ];
        }
    }

    function getFilterTypeByAttr($attr)
    {
        if ($attr['gqlType'])
            return $attr['gqlType'];

        switch ($attr['type']) {
            case 'number':
            case 'numeric':
            case 'int':
                return Types::IntFilter();
            case 'string':
            case 'text':
                return Types::StringFilter();
            case 'date':
            case 'datetime':
                return Types::DateFilter();
            case 'bool':
            case 'boolean':
                return Types::boolean();
            case 'file':
            case 'image':
                return Types::IntFilter();
        }
    }
}
