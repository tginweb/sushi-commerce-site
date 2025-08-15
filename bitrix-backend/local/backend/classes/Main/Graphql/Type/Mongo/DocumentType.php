<?php

namespace Main\Graphql\Type\Mongo;

use Main\Graphql\Type\Entity\EntityType;
use Main\Graphql\Types;

class DocumentType extends EntityType
{
    const NAME = 'Document';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [
                '_id' => Types::string(),
                'nid' => Types::int(),
            ];
    }
}
