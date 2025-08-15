<?php

namespace Main\Graphql\Type\HLBlock;

use Main\DI\Containerable;
use Main\Graphql\Generator\UserPropsGenerator;
use Main\Graphql\Type\InputType;
use Main\Graphql\Types;

class HLEntityInputType extends InputType
{
    use Containerable;

    const NAME = 'HLEntityInput';

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
        $hlBlockId = static::getHLBlockId();
        if ($hlBlockId) {
            $propsInfo = $this->getPropsInfo();
            if (!empty($propsInfo)) {
                return UserPropsGenerator::create(static::NAME, 'HLBLOCK_' . $hlBlockId, $propsInfo)->getPropsAsList();
            }
        }
        return [];
    }
}

