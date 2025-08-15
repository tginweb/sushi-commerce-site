<?php

namespace Main\Graphql\Generator;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\DI\Containerable;
use Main\Entity\D7\D7Model;
use Main\Entity\IBlock\ElementModel;
use Main\Graphql\Type\IBlock\ListValueType;
use Main\Graphql\Type\IBlock\SectionType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;

class UserPropsGenerator extends PropsGenerator
{
    use Containerable;

    public $entityType;
    public $modelClass = D7Model::class;
    public $view = [];
    public $allowedProps = [];

    function getProps()
    {
        return $this->modelClass::getAttrsProps($this->view);
    }

    function getFieldsInfo($props)
    {
        $fields = [];

        $propsResult = [];

        foreach ($props as $prop) {
            switch ($prop['USER_TYPE_ID']) {
                case 'iblock_section':
                    $newProp = [
                            'CODE' => $prop['CODE'] . '_ENTITY',
                        ] + $prop;
                    $propsResult[] = $newProp;
                    $prop['USER_TYPE_ID'] = 'integer';
                    break;
                case 'datetime':
                    $newProp = [
                            'USER_TYPE_ID' => 'datetime_formatted',
                            'CODE' => $prop['CODE'] . '_FORMATTED',
                        ] + $prop;
                    $propsResult[] = $newProp;
                    break;
            }
            $propsResult[] = $prop;
        }

        foreach ($propsResult as $prop) {

            $prop['CODE'] = strtoupper($prop['CODE']);

            $customProp = $this->getPropCustomType($prop);

            if ($customProp) {
                $resolver = function (ElementModel $parent, $args, $context, ResolveInfo $info) use ($prop, $customProp) {
                    $val = $parent->getProp($info->fieldName);
                    if ($customProp['typeClass']) {
                        return call_user_func([$customProp['typeClass'], 'prepareValue'], $parent, $val);
                    }
                };
            } else {
                switch ($prop['USER_TYPE_ID']) {
                    case 'file':
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
                    case 'iblock_section':
                        $resolver = function ($parent, $args, $context, ResolveInfo $info) use ($prop) {
                            $propName = $prop['FIELD_NAME'];

                            if (is_array($parent)) {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    $value = $parent[$propName];
                                    $ids = array_keys($value);
                                    return !empty($ids) ? $context['dataloader']['section']->loadMany($ids) : [];
                                } else {
                                    $id = $parent[$propName];
                                    return !empty($id) ? $context['dataloader']['section']->load($id) : null;
                                }
                            } else {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    $ids = $parent->getProp($propName, 'VALUE', true);
                                    return !empty($ids) ? $context['dataloader']['section']->loadMany($ids) : [];
                                } else {
                                    $id = $parent->getProp($propName, 'VALUE');
                                    return !empty($id) ? $context['dataloader']['section']->load($id) : null;
                                }
                            }
                        };
                        break;
                    case 'enumeration':
                        $resolver = function ($parent, $args, $context, ResolveInfo $info) use ($prop) {
                            $propName = $prop['FIELD_NAME'];
                            if (is_array($parent)) {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    $value = $parent[$propName];
                                    $ids = array_keys($value);
                                    return !empty($ids) ? $context['dataloader']['field_enum']->loadMany($ids) : [];
                                } else {
                                    $id = $parent[$propName];
                                    return !empty($id) ? $context['dataloader']['field_enum']->load($id) : null;
                                }
                            } else {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    $ids = $parent->getProp($propName, 'VALUE', true);
                                    return !empty($ids) ? $context['dataloader']['field_enum']->loadMany($ids) : [];
                                } else {
                                    $id = $parent->getProp($propName, 'VALUE');
                                    return !empty($id) ? $context['dataloader']['field_enum']->load($id) : null;
                                }
                            }
                        };
                        break;
                    default:
                        $resolver = function ($parent, $args, $context, ResolveInfo $info) use ($prop, $props) {
                            $propName = $prop['FIELD_NAME'];

                            if (is_array($parent)) {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    return $parent[$info->fieldName] ?: [];
                                } else {
                                    return $parent[$info->fieldName];
                                }
                            } else {
                                if ($prop['MULTIPLE'] === 'Y') {
                                    return $this->transformPropValues($parent->getProp($propName, 'VALUE', true), $args, $prop);
                                } else {
                                    return $this->transformPropValue($parent->getProp($propName, 'VALUE'), $args, $prop);
                                }
                            }
                        };
                        break;
                }
            }

            $fieldName = $prop['ALIAS'] ?: $prop['CODE'];

            $type = $this->mapPropertyType($prop);

            if (is_array($type)) {
                $fields[$fieldName] = $type + [
                        'resolve' => $resolver
                    ];
            } else {
                $fields[$fieldName] = [
                    'type' => $type,
                    'resolve' => $resolver
                ];
            }
        }

        return $fields;
    }

    function getPropsType($props, $typeSuffix = 'Props')
    {
        $fieldsInfo = $this->getFieldsInfo($props);

        if (!empty($fieldsInfo)) {
            return new ObjectType([
                'name' => $this->entityType . $typeSuffix,
                'fields' => $fieldsInfo
            ]);
        }
    }

    function getPropCustomType($prop)
    {
        $schema = $this->container->applyFiltersCached('uf:props.graphql', []);
        $propCode = $prop['CODE'];
        return $schema[$propCode] ?? null;
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
        switch ($prop['USER_TYPE_ID']) {
            case 'date':
                $time = DateTime::parseFromDate($value);
                return $time ? $time->getTimestamp() : null;
            case 'date_formatted':
                $time = DateTime::parseFromDate($value);
                return $time ? $time->formatToDate() : null;

            case 'datetime':
                $time = DateTime::parseFromDateTime($value);
                return $time ? $time->getTimestamp() : null;
            case 'datetime_formatted':
                $time = DateTime::parseFromDateTime($value);
                return $time ? $time->formatToDateTime() : null;

            case 'boolean':
                return $value === 'Y' || $value === '1' ? true : false;
            default:
                return $value;
        }
    }

    function mapPropertyType(array $prop)
    {
        $type = $this->resolveMainType($prop);

        if ($prop['MULTIPLE'] === 'Y') {
            if (is_array($type)) {
                $type['type'] = Types::nonNullListOf($type['type']);
            } else {
                $type = Types::nonNullListOf($type);
            }
        }

        return $type;
    }

    function resolveMainType(array $prop)
    {
        $customProp = $this->getPropCustomType($prop);

        if ($customProp) {
            return Types::get($customProp['typeClass']);
        }

        switch ($prop['USER_TYPE_ID']) {
            case 'string':
                return $this->resolveStringType($prop);
            case 'datetime':
            case 'date':
                return Types::int();
            case 'integer':
                return Types::int();
            case 'double':
                return Types::float();
            case 'boolean':
                return Types::boolean();
            case 'enumeration':
                return Types::get(ListValueType::class);
            case 'iblock_section':
                return Types::get(SectionType::class);
            case 'file':
                return Types::get(ImageType::class);
            default:
                return $this->resolveStringType($prop);
        }
    }

    function resolveStringType(array $prop)
    {
        switch ($prop['USER_TYPE_ID']) {
            case 'tg_enum':
                $enumType = $prop['SETTINGS']['DICTIONARY_ID'];
                if ($enumType && Types::hasType($enumType)) {
                    return Types::getType($enumType);
                } else {
                    return Types::string();
                }
                break;
            case 'tg_html':
                return Types::string();
            case 'DateTime':
                return Types::int();
        }

        return Types::string();
    }
}

