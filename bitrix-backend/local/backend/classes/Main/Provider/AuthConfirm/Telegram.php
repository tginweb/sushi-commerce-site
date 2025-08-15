<?php

namespace Main\Provider\AuthConfirm;

use Main\Entity\Otp\OtpModel;
use Main\Entity\Otp\OtpPhoneConfirmModel;

class Telegram extends Base
{
    public $isChatStartedCache;

    function getFields()
    {

        $fields = [
            'CODE' => $this->getProviderId(),
            'NAME' => 'Telegram',
            'ICON' => 'telegram',
            'COLOR' => '#24A1DE',
            'RESEND_TITLE' => 'отправить заново',
            'LIST_NAME' => 'Через telegram-бот',
            'LIST_CAPTION' => 'Если у вас установлен телеграм - проще всего воспользоваться ботом',
            'CONFIRM_CONTENT_WEB' => '
                 <div class="q-gutter-y-md">                  
                    <div class="s-font-md text-center text-weight-bold">Подтвердить телефон в Telegram</div>                                                                                        
                    <div class="text-center s-font-sm">
                        Для подтверждения вашего номера <span class="text-weight-medium">{#phone#}</span> необходимо открыть наш бот в Telegram и следовать 
                        дальнейшим инструкциям для получения кода                       
                    </div>     
                    <component
                     :is="QBtn"
                     label="перейти к боту @sushistudio_irk_bot"
                     size="md"
                     outline
                     href="' . $this->getTelegramUrl(true) . '"
                     target="_blank"
                     type="href"                     
                     color="primary"
                     class="full-width"
                    />                                                                                                
                </div>',
        ];


        if (!$this->isChatStarted()) {
            $fields['LIST_BUTTON_MOBILE'] = [
                'label' => 'Перейти в бот Telegram ',
                'action' => [
                    'url' => $this->getTelegramUrl(false)
                ]
            ];
            $fields['LIST_BUTTON_WEB'] = [
                'label' => 'Перейти в бот Telegram',
                'url' => $this->getTelegramUrl(true)
            ];
        } else {
            $fields['LIST_BUTTON_MOBILE'] = [
                'label' => 'Получить код в Telegram',
            ];
            $fields['LIST_BUTTON_WEB'] = [
                'label' => 'Получить код в Telegram',
            ];
        }

        return $fields;
    }

    function getProviderId()
    {
        return 'telegram';
    }

    function getTelegramUrl($web = false)
    {
        $clientId = $this->container->getAppClientService()->getClientId();

        $startData = 'cmd_auth__cid_' . $clientId;

        if ($web) {
            return 'https://t.me/' . $this->getTelegramBotName() . '?' . http_build_query([
                    'start' => $startData
                ]);
        } else {
            return 'tg://resolve?' . http_build_query([
                    'domain' => $this->getTelegramBotName(),
                    'start' => $startData
                ]);
        }
    }

    function getTelegramBotName()
    {
        return 'sushistudio_irk_bot';
    }

    function isChatStarted()
    {
        // die($this->phone);

        if (!isset($this->isChatStartedCache)) {
            $telegramService = $this->container->getTelegramService();
            $this->isChatStartedCache = $telegramService->isChatStarted($this->phone);
        }
        return $this->isChatStartedCache;
    }

    function sendCode(OtpModel $action)
    {
        $telegramService = $this->container->getTelegramService();

        $telegramService->sendMessageToPhone($action->getPhone(), $this->formatMessage($action));

        return true;
    }

    function formatMessage(OtpModel $action)
    {
        return 'Ваш код авторизации <b>' . $action->getSecureCode() . '</b>. Введите его на сайте или приложении Sushi-Studio';
    }

    function request($phone, $params = [])
    {
        $params += [
            'sendCode' => true
        ];

        $rateService = $this->container->getRateService();

        if ($this->isRequestCheckRateNeeded($phone)) {
            $rateService->checkRateOrThrow('telegram', $this->config['rateRules'], ['phone' => $phone]);
        }

        $otpService = $this->container->getOtpService();

        $action = $otpService->createAction([
            'NAME' => 'PHONE_CONFIRM',
            'PROVIDER' => $this->getProviderId(),
            'VALUE' => $phone,
        ], OtpPhoneConfirmModel::class);

        if ($params['sendCode']) {
            $action->send();
        } else {
            $action->setSended();
        }

        $action->save();

        return [];
    }
}
