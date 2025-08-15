<?php

namespace Main\Entity\Otp;

class OtpPhoneConfirmModel extends OtpModel
{
    function send()
    {
        $result = null;

        if (!$this->isFake()) {
            $provider = $this->getProvider();
            if ($provider) {
                $result = $provider->sendCode($this);
            }
        } else {
            $result = true;
        }

        if ($result) {
            $this->setSended();
        }

        $this->save();

        return $result;
    }

    function getProvider()
    {
        return $this->container->getAuthConfirmService()->getProvider($this->getProviderCode());
    }
}
