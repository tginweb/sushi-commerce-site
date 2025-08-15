<?php

namespace Main\Entity\Otp;

use Main\DI\Containerable;
use Main\Entity\D7\D7Model;
use function TG\Main\Entity\Otp\ConvertTimeStamp;
use function TG\Main\Entity\Otp\randString;

class OtpModel extends D7Model
{
    use Containerable;

    public static function tableName()
    {
        return 'secure_action';
    }

    function save($selectedFields = [])
    {
        return parent::save($selectedFields);
    }

    function onBeforeSave()
    {

        if (!$this['UF_SECURE_ID'])
            $this['UF_SECURE_ID'] = randString(20);

        $this['UF_SECURE_CODE'] = $this->getSecureCode();

        if (is_array($this['UF_DATA']))
            $this['UF_DATA'] = json_encode($this['UF_DATA']);

        $this['UF_DATA_HASH'] = md5($this['UF_DATA']);
    }

    function getSecureCode()
    {
        if (!$this['UF_SECURE_CODE']) {
            $this->setSecureCode(randString(4, '0123456789'));
        }
        return $this['UF_SECURE_CODE'];
    }

    function setSecureCode($code)
    {
        $this['UF_SECURE_CODE'] = $code;
        return $this;
    }

    function getData($name = null, $def = null)
    {
        return $name ? $this['UF_DATA'][$name] : $this['UF_DATA'];
    }

    function afterFill()
    {
        if (!$this['ID']) return;

        $this['UF_DATA'] = json_decode($this['UF_DATA'], true);
    }

    function checkCode($code)
    {
        return $this['UF_SECURE_CODE'] == $code;
    }

    function haveAttempts()
    {
        return $this['UF_ATTEMPTS'] > 0;
    }

    function hitAttempt()
    {
        $this['UF_ATTEMPTS'] = $this['UF_ATTEMPTS'] - 1;
    }

    function getPhone()
    {
        return $this->getValue();
    }

    function getValue()
    {
        return $this['UF_VALUE'];
    }

    function getEmail()
    {
        return $this->getValue();
    }

    function send()
    {

    }

    function setSended()
    {
        $this['UF_SENDED'] = ConvertTimeStamp(time(), "FULL");
        return $this;
    }

    function isFake()
    {
        return !!$this['UF_FAKE'];
    }

    function getProviderCode()
    {
        return $this['UF_PROVIDER'] ?: 'default';
    }

    function getProvider()
    {
        return null;
    }

    function getClientId()
    {
        return $this['UF_CLIENT_ID'];
    }
}
