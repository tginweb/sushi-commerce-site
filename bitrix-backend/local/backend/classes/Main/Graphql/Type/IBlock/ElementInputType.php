<?php

namespace Main\Graphql\Type\IBlock;

use GraphQL\Type\Definition\InputObjectType;
use Main\DI\Containerable;
use Main\Entity\IBlock\PropertyModel;
use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class ElementInputType extends InputType
{
    use Containerable;

    const NAME = 'ElementInput';

    function getFieldsInfo()
    {
        $props = $this->getPropsField();

        $fields = [
            'IBLOCK_ID' => Types::int(),
            'NAME' => Types::string(),
            'CODE' => Types::string(),
        ];

        if (!empty($props)) {
            $fields['PROPERTIES'] = $props;
        }

        return $fields;
    }

    function getPropsField()
    {
        $props = self::getProperties();
        if (!empty($props)) {
            return [
                'type' => $this->getPropsType($props),
            ];
        }
    }

    static function getProperties()
    {
        $iblockId = static::iblockId();
        if (!$iblockId) {
            return [];
        }
        return static::container()->getIblockService()->getProps($iblockId);
    }

    static function iblockId()
    {
        return 0;
    }

    function getPropsType($props, $typeSuffix = 'Props')
    {
        $fields = [];

        $propsResult = [];

        /** @var PropertyModel $prop */
        foreach ($props as $prop) {

            switch ($prop['PROPERTY_TYPE']) {
                case 'E':
                    if ($prop['MULTIPLE'] === 'Y') {
                        $newProp = new PropertyModel(null, [
                                'ORIGINAL_CODE_ALIAS' => $prop['ALIAS'],
                                'ORIGINAL_CODE' => $prop['CODE'],
                                'CODE' => $prop['CODE'] . '_ENTITIES',
                            ] + $prop->fields);

                        if ($prop['ALIAS']) {
                            $newProp['ALIAS'] = $prop['ALIAS'] . '_ENTITIES';
                        }

                        $propsResult[] = $newProp;
                    } else {

                        $newProp = new PropertyModel(null, [
                                'ORIGINAL_CODE_ALIAS' => $prop['ALIAS'],
                                'ORIGINAL_CODE' => $prop['CODE'],
                                'CODE' => $prop['CODE'] . '_ENTITY',
                            ] + $prop->fields);

                        if ($prop['ALIAS']) {
                            $newProp['ALIAS'] = $prop['ALIAS'] . '_ENTITY';
                        }

                        $propsResult[] = $newProp;
                    }
                    $prop['PROPERTY_TYPE'] = 'N';
                    break;
            }

            $propsResult[] = $prop;
        }

        foreach ($propsResult as $prop) {
            $prop['CODE'] = strtoupper($prop['CODE']);
            $fieldName = $prop['ALIAS'] ?: $prop['CODE'];
            $fields[$fieldName] = [
                'type' => $this->mapPropertyType($prop),
            ];
        }

        $gqlType = static::NAME . $typeSuffix;

        return new InputObjectType([
            'name' => $gqlType,
            'fields' => $fields
        ]);
    }

    function mapPropertyType(PropertyModel $prop)
    {
        $type = $this->resolveMainType($prop);

        if ($prop['MULTIPLE'] === 'Y') {
            $type = Types::listOf(Types::nonNull($type));
        }

        return $type;
    }

    function resolveMainType(PropertyModel $prop)
    {
        if (method_exists($prop, 'getGglInputClass')) {
            return $prop->getGglInputClass();
        }

        switch ($prop['PROPERTY_TYPE']) {
            case 'S':
                if ($prop['USER_TYPE'] === 'SASDCheckbox') {
                    return Types::boolean();
                }
                return $this->resolveStringType($prop);
            case 'N':
                if ($prop['USER_TYPE'] === 'SASDCheckboxNum') {
                    return Types::boolean();
                }
                return Types::int();
            case 'L':
                return Types::int();
            case 'F':
                return Types::int();
            case 'E':
                return Types::int();
            case 'D':
                return Types::string();
            case 'T':
                return Types::string();
            case 'X':
                return Types::string();
            default:
                return Types::string();
        }
    }

    function resolveStringType(PropertyModel $prop)
    {
        switch ($prop['USER_TYPE']) {
            case 'HTML':
                return Types::string();
            case 'DateTime':
                return Types::int();
            case 'simai_complex':
                $settings = unserialize($prop['USER_TYPE_SETTINGS']);
                $props = self::getProperties();
                $subProps = [];
                foreach ($settings['SUBPROPS'] as $propId) {
                    $supProp = $props[$propId];
                    if ($supProp) {
                        $supProp['ALIAS'] = str_replace($prop['CODE'] . '_', '', $supProp['CODE']);
                        $supProp['MULTIPLE'] = 'N';
                        $subProps[] = $supProp;
                    }
                }
                if (!empty($subProps)) {
                    return $this->getPropsType($subProps, $prop['CODE']);
                } else {
                    return Types::string();
                }
        }

        return Types::string();
    }
}

