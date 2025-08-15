<?php

namespace Main\Entity\Mongo;

use Main\Entity\D7\D7Model;
use Main\Entity\Model\ModelCollection;

class DocumentModel extends D7Model
{
    /**
     * @return DocumentQuery
     */
    public static function query()
    {
        return new DocumentQuery(__CLASS__, ModelCollection::class);
    }

    public static function getFieldsInfo()
    {
        return [

        ];
    }

    public static function tableName()
    {
        return null;
    }

}
