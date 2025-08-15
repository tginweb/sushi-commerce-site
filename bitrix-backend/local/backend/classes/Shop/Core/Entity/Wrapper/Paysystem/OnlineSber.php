<?php

namespace Shop\Core\Entity\Wrapper\Paysystem;

use Bitrix\Main\Config\Option;
use Bitrix\Sale\BusinessValue;
use function TG\Shop\Core\Entity\Wrapper\Paysystem\writeLog;

class OnlineSber extends BaseOnline
{
    function getPayNav($payment)
    {
        $data = [
            'type' => 'location',
            'path' => $this->getPaymentLink($payment)
        ];
        return $data;
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

    /**
     * Подтверждаем заказ, висящий на предавторизации
     * @param $id
     * @return bool|string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public function confirmOrder($id)
    {
        writeLog('order', 'confirmOrder ' . $id);

        $order = $this->loadOrder($id);

        if (!$order)
            return null;

        $psId1 = $order->getPaymentSystemId();
        $psId = $psId1[0];

        writeLog('order', 'Платежная система ' . $psId);

        $orderSberId = $this->getOrderSberId($order, $psId); // проверяем заказ

        if (!$orderSberId) return "Не подходящий заказ";

        $price = $order->getPrice(); // получаем итоговую стоимость

        writeLog('order', $price);

        $return = self::confirmSberPayment($orderSberId, $price, $psId); // подтверждаем оплату

        if ($return["errorCode"] == 0) { // если оплата подтверждена


            self::fermaPrint($order->getId());

            $paymentCollection = $order->getPaymentCollection(); // получаем коллекцию оплат

            if (!$paymentCollection->current()->isPaid()) { // если заказ не был оплачен -

                writeLog('order', "ps:" . $psId);

                $paymentCollection->current()->setPaid("Y"); // оплачиваем

                //	$order->save(); // сохраняем
            }
        }

        return $return["errorMessage"]; // возвращаем текст сообщения из шлюза
    }



    /**
     * Проверяет возможность подтверждения платежа и фозвращает ID заказа в Сбере
     * @param $number - номер заказа в магазине
     * @param $psId - номер платежной системы
     * @return mixed  - номер заказа в сбере или false
     */
    protected function getOrderSberId($id, $psId)
    {
        $order = $this->loadOrder($id);

        $number = $order->getField('ACCOUNT_NUMBER');

        if (!$number || !$psId) return false;

        writeLog('order', 'пытаемся получить номер заказа в сбере ' . $number . ' - ' . $psId);

        $moduleId = "sberbank.ecom2";

        \CModule::IncludeModule($moduleId); // подключаем модуль sberbank

        $RBS_Gateway = new \Sberbank\Payments\Gateway; // создаем экземпляр шлюза

        $RBS_Gateway->setOptions(array(
            // module settings
            'gate_url_prod' => Option::get($moduleId, 'SBERBANK_PROD_URL'),
            'gate_url_test' => Option::get($moduleId, 'SBERBANK_TEST_URL'),
            'test_mode' => BusinessValue::get("SBERBANK_GATE_TEST_MODE", "PAYSYSTEM_" . $psId) == "Y" ? 1 : 0, // тестовый режим
        ));

        $arOptions = array(
            'userName' => BusinessValue::get("SBERBANK_GATE_LOGIN", "PAYSYSTEM_" . $psId), // логин "gryadkaspb-api"
            'password' => BusinessValue::get("SBERBANK_GATE_PASSWORD", "PAYSYSTEM_" . $psId) // пароль
        );

        for ($i = 0; $i < 10; $i++) { // пробуем сделать проверку заказа 10 раз

            $arOptions["orderNumber"] = $number . '_' . $i;  // преобразуем номер заказа в понятный сберу

            writeLog('order', 'попытка получить статус заказа #' . $arOptions["orderNumber"]);

            $RBS_Gateway->buildData($arOptions);

            $gateResponse = $RBS_Gateway->checkOrder(); // проверка заказа

            $needBreak = false;

            writeLog('order', 'статус заказа: ' . $gateResponse['orderStatus']);

            switch ($gateResponse['orderStatus']) {
                case 1:

                    writeLog('order', 'возвращаем реальный ID заказа в Сбербанке');

                    foreach ($gateResponse["attributes"] as $attr) {
                        if ($attr["name"] == "mdOrder") // возвращаем реальный ID заказа в Сбербанке
                            if ($attr["value"]) return $attr["value"];
                    }
                    break;
                case 3:
                case 4:
                case 5:
                case 6:
                    writeLog('order', $gateResponse['orderStatus'] . ":" . $gateResponse['errorMessage']);
                    break;
                default:
                    writeLog('order', 'заказ не найден или оплачен');
                    return false;

            }

            if ($needBreak) break;
        }
    }

    /**
     * Выполняем запрос на подтверждение платежа
     * @param $order_number - ID заказа в Сбербанке
     * @param int $price - стоимость которую необходимо подтвердить
     * @return array|mixed|string - ответ от шлюза
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     */
    protected function confirmSberPayment($order_number, $price = 0, $psId = null)
    {
        $moduleId = "sberbank.ecom2";

        \CModule::IncludeModule($moduleId);

        $RBS_Gateway = new \Sberbank\Payments\Gateway;

        $RBS_Gateway->setOptions(array(
            // module settings
            'gate_url_prod' => Option::get($moduleId, 'SBERBANK_PROD_URL'),
            'gate_url_test' => Option::get($moduleId, 'SBERBANK_TEST_URL'),
            'test_mode' => BusinessValue::get("SBERBANK_GATE_TEST_MODE", "PAYSYSTEM_" . $psId) == "Y" ? 1 : 0,
        ));

        $arParams = array(
            'userName' => BusinessValue::get("SBERBANK_GATE_LOGIN", "PAYSYSTEM_" . $psId),
            'password' => BusinessValue::get("SBERBANK_GATE_PASSWORD", "PAYSYSTEM_" . $psId),
            'orderId' => $order_number,
            'amount' => $price * 100
        );

        return $RBS_Gateway->getResponse("deposit.do", $arParams);
    }
}


