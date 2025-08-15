<?php

namespace Main\Service;

use Bitrix\Main\Web\JWT;

class TokenService extends BaseService
{
    public $settings;
    public $DECODE_ALGORITHM = 'HS256';

    function decodeIdentityToken(string $identityToken)
    {
        $key = $this->getKey();

        $payload = JWT::decode($identityToken, $key, [$this->DECODE_ALGORITHM]);

        return $payload ? (array)$payload : $payload;
    }

    function getKey()
    {
        return 'sdawdawdawd';
    }

    function encodeIdentityToken($userId, $data = [], $ttl = 3600)
    {
        $key = $this->getKey();

        $payload = [
                'iat' => time(),
                'exp' => time() + $ttl,
                'userId' => $userId
            ] + $data;

        $jwt = JWT::encode($payload, $key, $this->DECODE_ALGORITHM);

        return $jwt;
    }


}


