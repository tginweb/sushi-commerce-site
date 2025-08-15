<?php

namespace Main\Error;

class InternalError extends CommonError
{
    /**
     * @return bool
     */
    public function isClientSafe()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'internal';
    }
}
