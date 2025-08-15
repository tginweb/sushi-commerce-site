<?php

namespace Main\Lib\Jwt;

class AccessToken extends Token
{
    public function getJwt()
    {
        if (!$this->jwt)
            $this->jwt = new JWT('secret', 'HS256', 3600, 10);
        return $this->jwt;
    }
}
