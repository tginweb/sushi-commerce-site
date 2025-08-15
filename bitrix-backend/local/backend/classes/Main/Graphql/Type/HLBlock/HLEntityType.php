<?php

namespace Main\Graphql\Type\HLBlock;

use GraphQL\Type\Definition\ResolveInfo;
use Main\Entity\D7\D7Model;
use Main\Graphql\Generator\UserPropsGenerator;
use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class HLEntityType extends EntityType
{
    const NAME = 'HLEntity';

    public static function getModelClass()
    {
        return D7Model::class;
    }

    public static function getModelView()
    {
        return ['list', 'detail'];
    }

    public function getFieldsInfo()
    {
        $fields = [
            'ID' => Types::nonNull(Types::int()),
        ];

        $modelClass = static::getModelClass();


        if ($modelClass) {
            $allowedProps = [];
            $generator = new UserPropsGenerator(
                static::NAME,
                $modelClass,
                $this->getModelView(),
                $allowedProps
            );

            $propsFields = $generator->getResultFields();
            if (!empty($propsFields)) {
                $fields += $propsFields;
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
        } else if (isset($element['UF_' . $info->fieldName])) {
            $result = $element['UF_' . $info->fieldName];
        }
        return $this->resolveResultProcess($result, $element, $args, $context, $info);
    }
}
