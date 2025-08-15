<?php

namespace Main\Graphql\Type\IBlock;

use Bitrix\Iblock\InheritedProperty\ElementValues;
use GraphQL\Type\Definition\ResolveInfo;
use Main\Entity\IBlock\ElementModel;
use Main\Graphql\Generator\ElementPropsGenerator;
use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;
use function TG\Main\Graphql\Type\IBlock\FormatText;

class ElementType extends EntityType
{
    const NAME = 'Element';

    public static function getModelClass()
    {
        return ElementModel::class;
    }

    public static function iblockId()
    {
        return static::getModelClass()::iblockId();
    }

    public static function getModelView()
    {
        return ['list', 'detail'];
    }

    public function getBaseFieldsInfo()
    {
        $gqlFields = [];
        $attrsByType = static::getModelClass()::getAttrsFilteredByType('client');
        $attrsFields = $attrsByType['field'];

        foreach ($attrsFields as $code => $attrField) {
            if (!empty($attrField['base'])) {
                $type = $this->container->getGraphqlHelperService()->getGraphTypeByAttr($attrField);
                if ($type) {
                    $gqlFields[$code] = $type;
                }
            }
        }
        return $gqlFields;
    }

    public function getFieldsInfo()
    {
        $fields = [];
        $attrsByType = static::getModelClass()::getAttrsFilteredByType('client');
        $attrsFields = $attrsByType['field'];

        foreach ($attrsFields as $code => $attr) {
            $type = $this->container->getGraphqlHelperService()->getGraphTypeByAttr($attr);
            if ($type) {
                if ($attr['notNull']) {
                    $type = Types::nonNull($type);
                }
                $fields[$code] = $type;
            }
        }

        $fields += [
            'IBLOCK_SECTION_IDS' => Types::nonNullListOf(Types::nonNull(Types::int())),
            'URL' => Types::string(),
            'PATH' => Types::listOf(Types::get($this->getSectionTypeClass())),
            'SECTIONS' => Types::listOf(Types::get($this->getSectionTypeClass())),
            'SECTION' => Types::get($this->getSectionTypeClass()),
            'ROOT_SECTION' => Types::get($this->getSectionTypeClass()),
            'META' => Types::get(ElementMetaType::class),
        ];

        return $fields + $this->getPropsFieldsInfo();
    }

    public function getPropsFieldsInfo()
    {
        $modelClass = static::getModelClass();

        $fields = [];
        if ($modelClass) {
            $allowedProps = [];
            $generator = new ElementPropsGenerator(
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

    public function resolveField($element, $args, $context, ResolveInfo $info)
    {
        $fieldName = $info->fieldName;
        $method = 'resolve_' . ucfirst($fieldName);
        if (method_exists($this, $method)) {
            $result = $this->{$method}($element, $args, $context, $info);
        } else if (isset($element[$info->fieldName])) {
            $result = $element[$info->fieldName];
        }
        return $this->resolveResultProcess($result, $element, $args, $context, $info);
    }

    public function getSectionTypeClass()
    {
        return SectionType::class;
    }

    public static function getAllowedProps()
    {
        $modelClass = static::getModelClass();
        if ($modelClass) {
            $props = call_user_func([$modelClass, 'getPropsInfoByView'], static::getModelView());
            return array_keys($props);
        }
        return [];
    }

    public function resolve_IBLOCK_SECTION_IDS(ElementModel $element, $args, $context)
    {
        return array_map(function ($section) {
            return $section['ID'];
        }, $element->getSections());
    }

    public function resolve_SECTIONS(ElementModel $element, $args, $context)
    {
        return $element->getSections();
    }

    public function resolve_ACTIVE($element, $args, $context)
    {
        return $element['ACTIVE'] === 'Y';
    }

    public function resolve_DETAIL_IMAGE($element, $args, $context)
    {
        $pictureId = $element['DETAIL_PICTURE'] ?? $element['PREVIEW_PICTURE'];

        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }

    public function resolve_LIST_IMAGE($element, $args, $context)
    {
        $pictureId = $element['PREVIEW_PICTURE'] ?? $element['DETAIL_PICTURE'];

        return $pictureId ? $context['dataloader']['image']->load($pictureId) : null;
    }

    public function resolve_DETAIL_TEXT($element, $args, $context)
    {
        $text = $element['DETAIL_TEXT'];
        if ($args['format']) {
            $text = FormatText($text, $element['DETAIL_TEXT_TYPE']);
        }
        return $text;
    }

    public function resolve_PREVIEW_TEXT($element, $args, $context)
    {
        $text = $element['PREVIEW_TEXT'];
        if ($args['format']) {
            $text = FormatText($text, $element['PREVIEW_TEXT_TYPE']);
        }
        return $text;
    }

    public function resolve_URL($parent, $args, $ctx)
    {
        return $parent->getUrl();
    }

    public function resolve_PATH($parent, $args, $ctx)
    {
        return $parent->getParentSections();
    }

    public function resolve_SECTION($parent, $args, $ctx)
    {
        return $parent['IBLOCK_SECTION_ID'] ? $ctx['dataloader']['section']->load($parent['IBLOCK_SECTION_ID']) : null;
    }

    public function resolve_ROOT_SECTION($parent, $args, $ctx)
    {
        return $parent->getRootSection();
    }

    public function resolve_META($element, $args, $context)
    {
        $result = [

        ];
        $ipropValues = new ElementValues(
            $element["IBLOCK_ID"], // ID инфоблока
            $element["ID"] // ID элемента
        );

        $values = $ipropValues->getValues();

        foreach ($values as $key => $val) {
            if (strpos($key, 'ELEMENT') === 0) {
                $result[preg_replace('/ELEMENT\_META\_|ELEMENT\_/', '', $key)] = $val;
            }
        }

        return $result;
    }
}
