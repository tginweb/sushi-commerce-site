<?php

namespace Main\Graphql\Type\IBlock;

use Bitrix\Iblock\InheritedProperty\SectionValues;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Entity\IBlock\SectionModel;
use Main\Graphql\Generator\UserPropsGenerator;
use Main\Graphql\Type\ObjectType;
use Main\Graphql\Types;

class SectionType extends ObjectType
{
    const NAME = 'Section';

    public function getModelClass()
    {
        return SectionModel::class;
    }

    public static function getModelView()
    {
        return ['list', 'detail'];
    }

    public function getFieldsInfo()
    {
        $fields = [];
        $attrsByType = static::getModelClass()::getAttrsFilteredByType('client');
        $attrsFields = $attrsByType['field'];

        foreach ($attrsFields as $code => $attr) {
            $type = $this->container->getGraphqlHelperService()->getGraphTypeByAttr($attr, true);
            if ($type) {
                if ($attr['notNull']) {
                    $type = Types::nonNull($type);
                }
                $fields[$code] = $type;
            }
        }

        $fields += [
                'PARENT' => Types::get(static::class),
                'PARENTS' => Types::listOf(Types::get(static::class)),
                'ELEMENT_CNT' => Types::int(),
                'META' => Types::get(ElementMetaType::class),
                'REPLACE_LINK' => Types::string(),
                'CHILDREN' => Types::nonNullListOf(Types::getNonNull(SectionType::class)),
            ] + $this->getPropsFieldsInfo();

        return $fields;
    }

    public function getPropsFieldsInfo()
    {
        $modelClass = static::getModelClass();
        $fields = [];
        if ($modelClass) {
            $allowedProps = [];
            $generator = new UserPropsGenerator(
                static::NAME,
                $modelClass,
                $this->getModelView(),
                $allowedProps
            );
            $type = $generator->getResultType();
            if ($type) {
                $fields['PROPERTIES'] = [
                    'type' => Types::nonNull($type),
                    'resolve' => function ($element) {
                        return $element;
                    }
                ];
            }
        }
        return $fields;
    }

    public function resolveField($section, $args, $context, ResolveInfo $info)
    {
        $fieldName = $info->fieldName;
        $method = 'resolve_' . ucfirst($fieldName);

        if (method_exists($this, $method)) {
            $result = $this->{$method}($section, $args, $context, $info);
        } else {
            if (isset($element['UF_' . $fieldName])) {
                $result = $element['UF_' . $fieldName];
            } else {
                $result = $section[$info->fieldName];
            }
        }

        return $this->resolveResultProcess($result, $section, $args, $context, $info);
    }

    function resolve_PARENT($parent, $args, $context)
    {
        return $parent['IBLOCK_SECTION_ID'] ? $context['dataloader']['section']->load(get_class($parent) . ':' . $parent['IBLOCK_SECTION_ID'] . '.' . $args['viewmode']) : null;
    }

    function resolve_PARENTS($parent, $args, $context)
    {
        $arKeys = [];
        foreach ($parent->getParentSectionsIds() as $id) {
            $arKeys[] = get_class($parent) . ':' . $id . '.' . $args['viewmode'];
        }
        return $context['dataloader']['section']->loadMany($arKeys);
    }

    public function resolve_URL($parent, $args, $ctx)
    {
        return $parent->getUrl();
    }


    function resolve_META($element, $args, $context)
    {
        $result = [
        ];
        $ipropValues = new SectionValues(
            $element["IBLOCK_ID"], // ID инфоблока
            $element["ID"] // ID элемента
        );
        $values = $ipropValues->getValues();
        foreach ($values as $key => $val) {
            if (strpos($key, 'SECTION') === 0) {
                $result[preg_replace('/SECTION\_META\_|SECTION\_/', '', $key)] = $val;
            }
        }
        return $result;
    }
}
