<?php

namespace Main\Entity\Otp;

class OtpEmailConfirmModel extends OtpModel
{
    function send()
    {
        $email = $this->getValue();

        $arFields = [
            'EMAIL' => $email,
            'SECURE_CODE' => $this->getSecureCode()
        ];

        $eventName = 'SA_USER_EMAIL_CONFIRM';

        $this->container->getMailService()->eventSend($eventName, $arFields, null, true);

        $this->setSended();

        return true;
    }
}
