<?php

namespace User\Pub\Api;

use Bitrix;
use Main\Entity\Otp\OtpPhoneConfirmModel;
use Main\Entity\User\UserModel;
use Main\Error\FormError;
use Main\Error\UserError;
use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Type\User\AppClientType;
use Main\Graphql\Type\User\UserAuthConfirmType;
use Main\Graphql\Types;
use Main\Model\Response;
use Shop\Core\Entity\OrderModel;
use Shop\Core\Entity\OrderProfile;
use TG\Captcha\Core\Graphql\CaptchaInputType;
use TG\Main\Helper;
use function TG\User\Pub\Api\randString;

class AuthResolver extends ResolversGenerator
{
    public $ns = 'user_auth_';

    function getQueryMap()
    {
        return [

        ];
    }

    function getMutationMap()
    {
        return [
            'create_sa' => function ($queryName, $queryParams) {
                return [
                    'args' => [
                        'phone' => Types::string(),
                        'clientIds' => Types::JSON(),
                        'code' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutationCreateSa']
                ];
            },
            'login_start' => function ($queryName, $queryParams) {
                return [
                    'payload' => [
                        'confirmModes' => Types::nonNullListOf(Types::getNonNull(UserAuthConfirmType::class)),
                    ],
                    'args' => [
                        'phone' => Types::string(),
                        'captcha' => Types::get(CaptchaInputType::class),
                    ],
                    'resolve' => [$this, 'mutationLoginStart']
                ];
            },
            'login_request' => function ($queryName, $queryParams) {
                return [
                    'payload' => [
                        'id' => Types::string(),
                        'sid' => Types::string(),
                    ],
                    'args' => [
                        'confirmMode' => Types::string(),
                        'phone' => Types::string(),
                        'captcha' => Types::get(CaptchaInputType::class),
                    ],
                    'resolve' => [$this, 'mutationLoginRequest']
                ];
            },
            'login_confirm' => function ($queryName, $queryParams) {

                return [
                    'payload' => [
                        'appClient' => Types::get(AppClientType::class),
                        'sessionId' => Types::string(),
                        'userId' => Types::int(),
                        'redirect' => Types::JSON(),
                    ],
                    'args' => [
                        'confirmMode' => Types::string(),
                        'phone' => Types::string(),
                        'captcha' => Types::get(CaptchaInputType::class),
                        'code' => Types::string(),
                        'sid' => Types::string(),
                        'clientId' => Types::string(),
                        'disableBasketTransfer' => Types::boolean(),
                        'pushToken' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutationLoginConfirm']
                ];
            },
        ];
    }

    function mutationCreateSa($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = new Response();

        $saService = $this->container->getOtpService();

        $phone = $this->normalizePhone($args['phone']);

        $clientIds = $args['clientIds'];

        $action = $saService->createAction([
            'NAME' => 'PHONE_CONFIRM',
            'PROVIDER' => 'telegram',
            'VALUE' => $phone,
            'TTL' => 60 * 100
        ], OtpPhoneConfirmModel::class);

        $action->save();

        $response->setPayloadData('code', $action->getSecureCode());

        return $response->getGraphqlJson();
    }

    function normalizePhone($phone)
    {
        return \Main\Helper\Format::validateMobile($phone);
    }

    function mutationLoginStart($parent, $args, $ctx, $info, Response $response)
    {
        $phone = $this->normalizePhone($args['phone']);

        $response->startErrorMessagesGroup('form');

        if (!$phone) throw new FormError('field_format_invalid', '', ['fieldName' => 'phone', 'fieldLabel' => 'Телефон']);

        $confirmModes = $this->container->getAuthConfirmService()->getProviders();

        foreach ($confirmModes as $confirmMode) {
            $confirmMode->init($phone);
        }

        return [
            'confirmModes' => $confirmModes
        ];
    }

    function mutationLoginRequest($parent, $args, $ctx, $info, Response $response)
    {

        $confirmMode = $args['confirmMode'];

        if (!$this->container->getApp()->isMobileApp() && $confirmMode === 'call') {
            if ($args['captcha']['ANSWER'] !== 'da1s') {
                //throw new UserError('Некорректный способ подтверждения');
            }
        }

        $confirmProvider = $this->container->getAuthConfirmService()->getProvider($confirmMode);

        if (!$confirmProvider)
            throw new UserError('Некорректный способ подтверждения');

        $phone = $this->normalizePhone($args['phone']);

        if (!$phone)
            throw new UserError('Некорректный формат номера телефона');

        $confirmProvider->request($phone, [], $response);

        return [];
    }

    function mutationLoginConfirm($parent, $args, $ctx, $info, Response $response)
    {
        global $USER;
        global $DISABLE_BASKET_TRANSFER;

        $DISABLE_BASKET_TRANSFER = $args['disableBasketTransfer'];

        $saService = $this->container->getOtpService();

        $phone = $this->normalizePhone($args['phone']);

        if (!$phone) {
            throw new UserError('Некорректный формат номера телефона ' . $args['phone']);
        }

        $saService->loadActionAndCheck(['NAME' => 'PHONE_CONFIRM', 'VALUE' => $phone], $args['code']);

        $user = UserModel::query()->filter([
            'LOGIC' => 'OR',
            'LOGIN' => $phone,
            'PERSONAL_PHONE' => $phone,
        ])->first();

        if (!$user) {
            $createFields = [
                'phone' => $phone,
            ];
            $user = $this->createUser($createFields);
        }

        $loggerService = $this->container->getLoggerService();

        if (!$user)
            throw new UserError('Произошла ошибка');

        $guestProfiles = $this->container->getOrderProfileService()->getProfilesByUserCached($this->container->getUserGustableId(), true);
        $guestOrders = $this->container->getOrderService()->getActiveOrders();

        if (!$USER->Authorize($user['ID'], true, true)) {
            throw new UserError('Ошибка авторизации');
        }

        $clientService = $this->container->getAppClientService();

        $appClient = null;

        if ($clientService->getClientId()) {

            $appClient = $clientService->getCurrentAppClient(true);

            if ($appClient) {
                $appClient->authorize($user['ID']);
            }

            /** @var OrderModel $order */
            foreach ($guestOrders as $order) {
                if (
                    $order->getField('USER_ID') != $user['ID'] &&
                    $phone === $this->normalizePhone($order->getPropVal('PHONE'))
                ) {
                    $order->setFieldNoDemand('USER_ID', $user['ID']);
                    $order->save();
                }
            }

            /** @var OrderProfile $guestProfile */
            foreach ($guestProfiles as $guestProfile) {
                if ($guestProfile['USER_ID'] < 0) {
                    $guestProfile['USER_ID'] = $user['ID'];
                    $guestProfile->save(['USER_ID']);
                }
            }

            if ($args['pushToken']) {
                $this->container->getPushService()->updatePushToken($args['pushToken']);
            }
        }

        $response->addSuccess('Вы успешно авторизованы');

        $loggerService->addEvent([
            'name' => 'USER_AUTH',
            'log' => false,
            'data' => [
                'USER_ID' => $user['ID'],
                'USER_PHONE' => $user->getPhone(),
                'USER_EMAIL' => $user->getEmail(),
            ]
        ])->queueAdd();

        return [
            'userId' => $user['ID'],
            'sessionId' => session_id(),
            'appClient' => $appClient
        ];
    }

    function createUser($params, $pass = null)
    {
        $user = new \CUser;

        $pass = $pass ?: randString();

        $tmpEmailDomain = $this->container->getUserService()->getAutoCreateEmailDomain();

        $arFields = [
            "NAME" => '',
            "ACTIVE" => "Y",
            "PASSWORD" => $pass,
            "CONFIRM_PASSWORD" => $pass,
        ];

        if ($params['phone']) {
            $arFields['LOGIN'] = $params['phone'];
            $arFields['EMAIL'] = 'buyer_' . $params['phone'] . '@' . $tmpEmailDomain;
        } else if ($params['email']) {
            $arFields['LOGIN'] = $params['email'];
            $arFields['EMAIL'] = $params['email'];
        } else {
        }

        $userId = $user->Add($arFields);

        if ($userId) {
            return UserModel::getById($userId);
        }
    }
}
