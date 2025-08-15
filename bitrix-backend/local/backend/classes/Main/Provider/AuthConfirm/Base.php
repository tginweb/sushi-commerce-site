<?php

namespace Main\Provider\AuthConfirm;

use Main\DI\Containerable;
use Main\Entity\Model\ArrayableModel;
use Main\Entity\Otp\OtpModel;

class Base extends ArrayableModel
{
    use Containerable;

    public $config = [];
    public $phone = null;

    function __construct($config)
    {
        $this->config = $config;
    }

    function init($phone)
    {
        $this->phone = $phone;
        $this->fields = $this->getFields();
    }

    function getFields()
    {
        return [];
    }

    function getProviderId()
    {
        return null;
    }

    function formatMessage(OtpModel $action)
    {
        return '';
    }

    function sendCode(OtpModel $action)
    {
        return true;
    }

    function request($phone, $params = [])
    {
        return null;
    }

    function isRequestCheckRateNeeded($phone)
    {
        return !in_array($phone, $this->config['rateDisablePhones']);
    }
}
