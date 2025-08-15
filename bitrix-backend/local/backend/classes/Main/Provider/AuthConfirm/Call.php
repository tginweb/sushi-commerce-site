<?php

namespace Main\Provider\AuthConfirm;

use Main\Entity\Otp\OtpModel;
use Main\Entity\Otp\OtpPhoneConfirmModel;
use Main\Error\ExternalServiceError;

class Call extends Base
{

    function getFields()
    {
        return [
            'CODE' => $this->getProviderId(),
            'NAME' => 'Звонок',
            'ICON' => 'phone',
            'COLOR' => '#D16837',
            'RESEND_TITLE' => 'позвонить снова',
            'LIST_NAME' => 'Позвонить вам',
            'LIST_CAPTION' => 'На ваш номер телефон поступит звонок. Небходимо будет ввести последние 4 цифры номера',
            'LIST_BUTTON_MOBILE' => [
                'label' => 'Позвонить вам',
            ],
            'LIST_BUTTON_WEB' => [
                'label' => 'Позвонить вам',
            ],
            'CONFIRM_CONTENT_WEB' => '
                 <div class="q-gutter-y-md">
                 
                    <div class="s-font-md text-center text-weight-bold">Подтвердить телефон через звонок</div>                                                                                        
                 
                    <div class="s-font-xs s-font-md-sm leading-relaxed text-center">
                      На Ваш номер поступит звонок, необходимо ввести последние
                      <nobr class="text-weight-bold">4 цифры</nobr>
                      номера.
                    </div>  
                    
                    <div class="text-center" style="color:#CCC;font-weight:bold;font-size:20px;">
                      +7 000 000 <span style="color:#111;">XXXX</span>
                    </div>        
                    
                 </div>
            ',
            'CONFIRM_CONTENT_MOBILE' => '
                  <View gap-20>
                    <Text text-xxl-h center>Введите код</Text>
                    <Text text-md center>
                        На Ваш номер телефона
                        <Text templatableProps="children" children="{var:phone}"/> 
                        поступит звонок.
                        Необходимо будет ввести последние 
                        <Text text-md-m> 4 цифры номера:</Text>
                    </Text>
                    <Text grey30 text-3xl-h center>
                        +7 000 000 <Text text-3xl-h>XXXX</Text>
                    </Text>
                    <Component name="codeInput"/>
                </View>
                    ',
            'CONFIRM_STEPS' => [],
        ];
    }

    function getProviderId()
    {
        return 'call';
    }

    function formatMessage(OtpModel $action)
    {
        return 'Ваш код авторизации <b>' . $action->getSecureCode() . '</b>. Введите его на сайте или приложении Sushi-Studio';
    }

    function sendCode(OtpModel $action)
    {
        $url = 'https://vp.voicepassword.ru/api/voice-password/send/';
        $apiKey = '6896367b9fccec80b7d3cf24aab64232';

        $data = [
            'security' => [
                'apiKey' => $apiKey
            ],
            'number' => $action->getPhone(),
            'capacity' => 4,
            'voice' => []
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: $apiKey"
        ));

        $outData = curl_exec($ch);

        curl_close($ch);

        $res = json_decode($outData, true);

        if ($res['result'] === 'ok' && $res['code']) {
            $action->setSecureCode($res['code']);
            return true;
        } else if ($res['error_code'] === 'number_in_spam_list') {
            throw ExternalServiceError::_blocked('Превышено число запросов', [
                'serviceResponse' => $res
            ]);
        } else {
            throw ExternalServiceError::_rejected('Ошибка внешнего сервиса', [
                'serviceResponse' => $res
            ]);
        }
    }

    function request($phone, $params = [])
    {
        $rateService = $this->container->getRateService();

        $nextTtl = null;

        if (!in_array($phone, $this->config['rateDisablePhones'])) {
            $rateService->checkRateOrThrow(
                'call',
                $this->config['rateRules'],
                ['phone' => $phone],
                $nextTtl
            );
        }

        $saService = $this->container->getOtpService();

        $action = $saService->createAction([
            'NAME' => 'PHONE_CONFIRM',
            'PROVIDER' => $this->getProviderId(),
            'VALUE' => $phone,
        ], OtpPhoneConfirmModel::class);

        $action->send();
        $action->save();

        return [
            'nextTtl' => $nextTtl
        ];
    }
}
