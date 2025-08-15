<?php

namespace Main\Lib\Jwt;

class RefreshToken extends Token
{
    public function getJwt()
    {
        if (!$this->jwt)
            $this->jwt = new JWT('secret', 'HS256', 3600 * 24 * 360, 10);
        return $this->jwt;
    }
}
