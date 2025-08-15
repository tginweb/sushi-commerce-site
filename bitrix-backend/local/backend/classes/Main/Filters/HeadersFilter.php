<?php

namespace Main\Filters;

use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Type\IRequestFilter;

class HeadersFilter implements IRequestFilter
{
    public function filter(array $headers)
    {
        if (!empty($headers['HTTP_X_FORWARDED_PROTO'])) {
            $isHttps = $headers['HTTP_X_FORWARDED_PROTO'] === 'https';
        }

        if (isset($isHttps)) {
            if ($isHttps) {
                $headers['SERVER_PORT'] = 443;
                Configuration::setValue('https_request', true);
            } else {
                $headers['SERVER_PORT'] = 80;
                Configuration::setValue('https_request', false);
            }
        }
        return $headers;
    }
}
