<?php

namespace Main\Graphql\Generator;

use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ResolveInfo;
use Main\DI\Containerable;
use Main\Entity\IBlock\ElementModel;
use Main\Entity\IBlock\PropertyModel;
use Main\Graphql\Type\IBlock\ElementPropValueHtmlType;
use Main\Graphql\Type\IBlock\ElementPropValueWithDescType;
use Main\Graphql\Type\IBlock\ElementType;
use Main\Graphql\Type\IBlock\ListValueType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;

class ElementPropsGenerator extends PropsGenerator
{
    use Containerable;

    public $modelClass = ElementModel::class;

    function getIblockId()
    {
        return $this->modelClass::iblockId();
    }

    function getProps()
    {
        return $this->modelClass::getAttrsProps($this->view);
    }

    function getFieldsInfo($props)
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

            if (method_exists($prop, 'gqlResolve')) {
                $resolver = [$prop, 'gqlResolve'];
            } else {
                switch ($prop['PROPERTY_TYPE']) {
                    case 'F':
                        $resolver = function ($parent, $args, $context, ResolveInfo $info) use ($prop) {
                            if ($prop['MULTIPLE'] === 'Y') {
                                $ids = $parent->getProp($prop['CODE'], 'VALUE', true);
                                return !empty($ids) ? $context['dataloader']['image']->loadMany($ids) : [];
                            } else {
                                $id = $parent->getProp($prop['CODE']);
                                return !empty($id) ? $context['dataloader']['image']->load($id) : null;
                            }
                        };
                        break;
                    case 'L':
                        $resolver = function ($parent, $args, $context, ResolveInfo $info) use ($prop) {
                            $propName = $prop['ALIAS'] ?: $prop['CODE'];

                            if (is_array($parent)) {

                                if ($prop['MULTIPLE'] === 'Y') {
                                    $value = $parent[$propName];
                                    $ids = array_keys($value);
                                    return !empty($ids) ? $context['dataloader']['enum']->loadMany($ids) : [];
                                } else {
                                    $id = $parent[$propName];
                                    return !empty($id) ? $context['dataloader']['enum']->load($id) : null;
                                }

                            } else {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    $ids = $parent->getProp($propName, 'ENUM_ID', true);
                                    if (empty($ids)) {
                                        $v = $parent->getProp($propName, 'VALUE', true);
                                        if (!empty($v) && is_array($v)) {
                                            if (array_keys($v)[0] > 0) {
                                                $ids = array_keys($v);
                                            }
                                        }
                                    }
                                    return !empty($ids) ? $context['dataloader']['enum']->loadMany($ids) : [];
                                } else {
                                    $id = $parent->getProp($propName, 'ENUM_ID');
                                    return !empty($id) ? $context['dataloader']['enum']->load($id) : null;
                                }
                            }
                        };
                        break;
                    case 'E':
                        $resolver = function ($parent, $args, $context, ResolveInfo $info) use ($prop) {
                            $propName = $prop['ORIGINAL_CODE_ALIAS'] ?: $prop['ORIGINAL_CODE'] ?: $prop['CODE'];

                            if (is_array($parent)) {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    $ids = $parent[$propName] ?: [];
                                    return !empty($ids) ? $context['dataloader']['element']->loadMany($ids) : [];
                                } else {
                                    $id = $parent[$propName];
                                    return !empty($id) ? $context['dataloader']['element']->load($id) : null;
                                }
                            } else if (is_object($parent)) {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    $ids = $parent->getProp($propName, 'VALUE', true);
                                    return !empty($ids) ? $context['dataloader']['element']->loadMany($ids) : [];
                                } else {
                                    $id = $parent->getProp($propName);
                                    return !empty($id) ? $context['dataloader']['element']->load($id) : null;
                                }
                            }
                        };
                        break;
                    default:
                        $resolver = function ($parent, $args, $context, ResolveInfo $info) use ($prop, $props) {

                            if ($prop['USER_TYPE'] === 'simai_complex') {
                                return $parent->getComplexProp($info->fieldName);
                            }

                            if (is_array($parent)) {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    return $parent[$info->fieldName] ?: [];
                                } else {
                                    return $parent[$info->fieldName];
                                }
                            } else {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    if ($prop['WITH_DESCRIPTION'] === 'Y') {
                                        $values = $parent->getProp($info->fieldName, 'VALUE', true);
                                        $descriptions = $parent->getProp($info->fieldName, 'DESCRIPTION', true);
                                        $result = [];
                                        foreach ($values as $i => $value) {
                                            $result[] = [
                                                'VALUE' => $value,
                                                'DESC' => $descriptions[$i],
                                            ];
                                        }
                                        return $result;
                                    } else {
                                        return $this->transformPropValues($parent->getProp($info->fieldName, 'VALUE', true), $args, $prop);
                                    }
                                } else {
                                    return $this->transformPropValue($parent->getProp($info->fieldName), $args, $prop);
                                }
                            }

                        };
                        break;
                }
            }

            $fieldName = $prop['ALIAS'] ?: $prop['CODE'];

            $fields[$fieldName] = [
                'type' => $this->mapPropertyType($prop),
                'resolve' => $resolver
            ];
        }

        return $fields;
    }

    function transformPropValues($values, $args, $prop)
    {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $values[$key] = $this->transformPropValue($value, $args, $prop);
            }
        } else {
            $values = [];
        }
        return $values;
    }

    function transformPropValue($value, $args, $prop)
    {
        switch ($prop['USER_TYPE']) {
            case 'SASDCheckbox':
            case 'SASDCheckboxNum':
                return $value['VALUE'] === 'Y' || $value['VALUE'] === '1' ? true : false;
            case 'DateTime':
                return $value ? DateTime::parseToTimestamp($value) : null;
            default:
                return $value;
        }
    }

    private function mapPropertyType(PropertyModel $prop)
    {
        $type = $this->resolveMainType($prop);
        if ($prop['MULTIPLE'] === 'Y') {
            $type = Types::nonNullListOf($type instanceof NonNull ? $type : Types::nonNull($type));
        }
        return $type;
    }

    private function resolveMainType(PropertyModel $prop)
    {
        if (method_exists($prop, 'getGglTypeClass')) {
            return $prop->getGglTypeClass();
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
                return $prop['IS_NUMBER'] ? Types::int() : Types::int();
            case 'L':
                return Types::get(ListValueType::class);
            case 'F':
                return Types::get(ImageType::class);
            case 'E':
                return Types::get(ElementType::class);
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

    private function resolveStringType(PropertyModel $prop)
    {
        if ($prop['WITH_DESCRIPTION'] === 'Y') {
            return Types::getNonNull(ElementPropValueWithDescType::class);
        }

        switch ($prop['USER_TYPE']) {
            case 'HTML':
                return Types::get(ElementPropValueHtmlType::class);
            case 'DateTime':
                return Types::int();
            case 'tg_enum':
                $settings = unserialize($prop['USER_TYPE_SETTINGS']);
                $enumType = $settings['DICTIONARY_ID'];
                if ($enumType && Types::hasType($enumType)) {
                    return Types::getType($enumType);
                } else {
                    return Types::string();
                }
                break;
            case 'simai_complex':
                $settings = unserialize($prop['USER_TYPE_SETTINGS']);
                $subProps = [];
                foreach ($settings['SUBPROPS'] as $propId) {
                    $supProp = $this->container->getIblockService()->getProp($this->getIblockId(), $propId);
                    if ($supProp) {
                        $supProp['ALIAS'] = str_replace($prop['CODE'] . '_', '', $supProp['CODE']);
                        $supProp['MULTIPLE'] = 'N';
                        $subProps[] = $supProp;
                    }
                }
                if (!empty($subProps)) {
                    return $this->getFieldsType($subProps, 'Props' . $prop['CODE']);
                } else {
                    return Types::string();
                }
        }

        return Types::string();
    }

}

