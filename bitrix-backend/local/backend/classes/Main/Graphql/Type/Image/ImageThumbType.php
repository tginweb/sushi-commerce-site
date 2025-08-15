<?php


namespace Main\Graphql\Type\Image;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class ImageThumbType extends ObjectType
{
    const NAME = 'ImageThumb';

    public function getFieldsInfo()
    {
        return [
            'STYLE' => Types::string(),
            'SRC' => Types::string(),
        ];
    }

    public function resolveField($element, $args, $context, ResolveInfo $info)
    {
        list ($type, $name) = explode('_', $info->fieldName, 2);

        if ($type === 'PROP') {
            // die($name);
            if (isset($element['PROPERTY_' . $name . '_VALUE'])) {
                return $element['PROPERTY_' . $name . '_VALUE'];
            } else {
                return $element['PROPERTY_' . $name];
            }
        } else {
            $method = 'resolve' . ucfirst($info->fieldName);
            if (method_exists($this, $method)) {
                return $this->{$method}($element, $args, $context, $info);
            } else {
                return $element[$info->fieldName];
            }
        }
    }

}
