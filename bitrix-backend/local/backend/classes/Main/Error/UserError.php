<?php

namespace Main\Error;

class UserError extends CommonError
{
    /**
     * @return bool
     */
    public function isClientSafe()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return 'user';
    }
}
