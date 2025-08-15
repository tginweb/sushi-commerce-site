<?php

namespace Shop\Core\Entity\Wrapper\Paysystem;

class OnlineYookassa extends BaseOnline
{
    function supportOnlineRecurrentPayment()
    {
        return true;
    }

    function getActionWeb($payment)
    {
        $data = [
            'url' => $this->getPaymentLink($payment, ['absolute' => true])
        ];
        return $data;
    }

    function getActionMobile($payment)
    {
        $data = [
            'url' => $this->getPaymentLink($payment, ['absolute' => true])
        ];
        return $data;
    }


    function getPayNav($payment)
    {
        $data = [
            'type' => 'location',
            'path' => $this->getPaymentLink($payment)
        ];
        return $data;
    }

    function getPsStatusId($payment)
    {

        switch ($payment->getField('PS_STATUS_CODE')) {
            case 'succe':
            case 'succeed':
                $id = 'succeed';
                break;

            case 'cance':
            case 'canceled':
                $id = 'canceled';
                break;

            case 'waiti':
            case 'waiting_for_capture':
                $id = 'holded';
                break;

            case 'pendi':
            case 'pending':
                $id = 'pending';
                break;
        }

        return $id;
    }

    function processResult($data)
    {
        $data = ['object' => $data];
        return parent::processResult($data);
    }

    function isOnlineDelayed()
    {
        return true;
    }
}
