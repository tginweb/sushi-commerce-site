<?php

namespace Shop\Core\Graphql;

class OrderProfileFormType extends OrderProfileType
{
    const NAME = 'OrderProfileForm';

    public function getFieldsInfo()
    {
        return parent::getFieldsInfo() + [];
    }
}
