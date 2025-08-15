<?php

namespace User\Pub\Api;

use Bitrix;
use Bitrix\Sale\Fuser;
use Event\Core\Graphql\ClientNoticeType;
use Main\Entity\Otp\OtpEmailConfirmModel;
use Main\Entity\User\UserFamily;
use Main\Entity\User\UserModel;
use Main\Error\CommonError;
use Main\Error\UserError;
use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Type\User\AppClientType;
use Main\Graphql\Type\User\UserFamilyInputType;
use Main\Graphql\Type\User\UserSessionType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;
use Main\Lib\Date\DateDateDb;
use Main\Model\Response;
use Project\C1\Core\Entity\ServicePacket;
use TG\Main\Helper;
use User\Pub\Gql\MutationLogout;
use User\Pub\Gql\MutationUserBirthdayType;
use User\Pub\Gql\MutationUserChildType;
use User\Pub\Gql\MutationUserEmailType;
use User\Pub\Gql\MutationUserNameType;
use const TG\User\Pub\Api\FORMAT_DATE;

class UserResolver extends ResolversGenerator
{
    public $ns = 'user_';

    function getQueryMap()
    {
        return [
            'session' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(UserSessionType::class),
                    'resolve' => [$this, 'querySession']
                ];
            },
            'fetch' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(UserType::class),
                    'args' => [
                        'sessionWrite' => Types::boolean(),
                    ],
                    'resolve' => [$this, 'queryFetch']
                ];
            },
            'app_client' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(AppClientType::class),
                    'resolve' => [$this, 'queryAppClient']
                ];
            },
        ];
    }

    function getMutationMap()
    {
        return [
            'app_client' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(AppClientType::class),
                    'args' => [
                        'debugParams' => Types::JSON(),
                        'mobilePushToken' => Types::string(),
                        'webPushToken' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutationAppClient']
                ];
            },
            'profile_save' => function ($queryName, $queryParams) {
                return [
                    'type' => $this->typeResponseWithPayload('UserProfileSave', Types::get(UserType::class)),
                    'args' => [
                        'form' => Types::JSON(),
                    ],
                    'resolve' => [$this, 'mutationProfile']
                ];
            },
            'logout' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(MutationLogout::class),
                    'args' => [],
                    'resolve' => [$this, 'mutationLogout']
                ];
            },
            'profile_all_filled' => function ($queryName, $queryParams) {
                return [
                    'type' => $this->typeResponseWithPayload('UserProfileAllFilled', [
                        'notice' => Types::get(ClientNoticeType::class),
                    ]),
                    'resolve' => [$this, 'mutationAllFilled']
                ];
            },
            'profile_name' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(MutationUserNameType::class),
                    'args' => [
                        'name' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutationName']
                ];
            },
            'profile_birthday' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(MutationUserBirthdayType::class),
                    'args' => [
                        'birthday' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutationBirthday']
                ];
            },
            'profile_email' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(MutationUserEmailType::class),
                    'args' => [
                        'action' => Types::string(),
                        'email' => Types::string(),
                        'code' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutationEmail']
                ];
            },
            'profile_child' => function ($queryName, $queryParams) {
                return [
                    'type' => Types::get(MutationUserChildType::class),
                    'args' => [
                        'action' => Types::string(),
                        'child' => Types::get(UserFamilyInputType::class),
                    ],
                    'resolve' => [$this, 'mutationChild']
                ];
            },
        ];
    }

    function queryFetch($rootValue, $args)
    {
        global $USER;

        \Bitrix\Main\Loader::includeModule('main');
        $user = $this->container->getUser();
        return $user ? $user : null;
    }

    function queryAppClient($rootValue, $args)
    {
        return $this->container->getAppClientService()->getCurrentAppClient(true);
    }

    function querySession($rootValue, $args)
    {
        $userId = $this->container->getUserId();
        $fuserId = Fuser::getId();

        return [
            'SESSION_ID' => session_id(),
            'USER_ID' => $userId,
            'FUSER_ID' => $fuserId,
        ];
    }

    function mutationAppClient($rootValue, $args, $ctx, $info, Response $response)
    {

        $appClient = $this->container->getAppClientService()->getCurrentAppClient(true);

        if ($appClient) {

            if ($args['debugParams'])
                $appClient->assignDebugParams($args['debugParams']);

            if ($args['mobilePushToken'])
                $appClient->setPushToken($args['mobilePushToken']);

            if ($args['webPushToken'])
                $appClient->setWebPushToken($args['webPushToken']);

            $appClient->save();
        }

        return $appClient;
    }

    function mutationAllFilled($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();

        $user = $this->container->getUser();

        try {

            $phone = $user->getPhone();

            if (!$user)
                throw new UserError('Вы не авторизованы');

            if ($user['UF_PROFILE_GIFT_USED'])
                throw new UserError('Подарок за внесение данных профиля уже получен');

            if (!$user['NAME'] || preg_match('/клиент|гость/', $user['NAME']))
                throw new UserError('Не указано имя');

            if (!$user->getEmailReal())
                throw new UserError('Не указан e-mail');

            if (!$phone)
                throw new UserError('Не указан телефон');

            if (!$user['PERSONAL_BIRTHDAY'])
                throw new UserError('Не указана дата рождения');

            $serviceParams = [
                'phone' => $phone,
                'type' => 'profile-fill-new',
                'amount' => 300
            ];

            $serviceRes = $this->container->get1СService()->apiAddBonus($serviceParams);

            if (!preg_match('/OK\:/', $serviceRes)) {

                $packetUid = 'bonus_add_fill_profile:' . $phone;

                $foundPacket = ServicePacket::query()->filter([
                    'UF_UID' => $packetUid
                ])->first();

                if (!$foundPacket) {
                    $packet = ServicePacket::create([
                        'UF_TYPE' => 'bonus_add_fill_profile',
                        'UF_UID' => 'bonus_add_fill_profile:' . $phone,
                        'UF_DATA' => $serviceParams,
                        'UF_RESPONSE' => $serviceRes,
                        'UF_SENDED' => 0
                    ]);
                    $packet->save();
                }
            }

            $logItem = [
                'phone' => $phone,
                'serviceRes' => $serviceRes
            ];

            file_put_contents(__DIR__ . '/bonus_add.log', json_encode($logItem, JSON_UNESCAPED_UNICODE) . "\n\n", FILE_APPEND);

            $user['UF_PROFILE_GIFT_USED'] = 1;
            $user->save();

            $card = $this->container->getSaleClientCardService()->getCurrentUserCard();

            if ($card) {
                $card['UF_BONUSES'] = $card['UF_BONUSES'] + 300;
                $card->save();
            }

            //$card->fetch(true);

            $notice = $this->container->getClientEmitService()->createEmit(
                'profileFillGift',
                [
                    'targetUserId' => $this->container->getUserId(),
                    'eventData' => [
                        'bonuses' => 300
                    ]
                ]
            )->queueAdd()->getNotice();

            $response->addActionMobile([
                'action' => [
                    'url' => 'sale://reloadClientCard'
                ]
            ]);

            $response->setPayloadData('notice', $notice);
        } catch (CommonError $e) {
            $response->addException($e);
        }

        return $response->getGraphqlJson();
    }

    function mutationName($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $user = $this->container->getUser();
        $user['NAME'] = $args['name'];
        $user->save();
        $response->setField('user', $user);
        return $response->getGraphqlJson();
    }

    function mutationChild($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $user = $this->container->getUser();

        $child = $args['child'];

        switch ($args['action']) {
            case 'add':
                $childModel = UserFamily::create([
                    'UF_USER_ID' => $user['ID'],
                    'UF_NAME' => $child['NAME'],
                    'UF_BIRTHDAY' => DateDateDb::parseFromDate($child['BIRTHDAY'])
                ]);
                $childModel->save();
                $response->addSuccess('Успешно добавлен');
                break;
            case 'update':
                if ($child['ID']) {
                    $childModel = UserFamily::query()->filter(['ID' => $child['ID']])->first();
                    if ($childModel) {
                        $childModel['UF_NAME'] = $child['NAME'];
                        $childModel->save();
                        $response->addSuccess('Данные обновлены');
                    }
                }
                break;
            case 'delete':
                if ($child['ID']) {
                    $childModel = UserFamily::query()->filter(['ID' => $child['ID']])->first();
                    if ($childModel) {
                        $childModel->delete();
                        $response->addSuccess('Данные обновлены');
                    }
                }
                break;
        }

        $response->setField('user', $user);

        return $response->getGraphqlJson();
    }

    function mutationBirthday($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $user = $this->container->getUser();

        if ($args['birthday']) {
            $user['PERSONAL_BIRTHDAY'] = $args['birthday'];

            list($dd, $mm, $yy) = explode('.', $args['birthday']);
            $date = new \DateTime($yy . '-' . $mm . '-' . $dd . " 00:00:01");

            $this->container->get1СService()->getProviderSoap()->apiPhoneControl([
                'phone' => $user->getPhone(),
                'birthday' => $date->format('c')
            ]);

            $user->save();
        }

        $response->setField('user', $user);
        return $response->getGraphqlJson();
    }

    function mutationEmail($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $currentUserId = $this->container->getUserId();
        $user = $this->container->getUserOrThrow();

        $action = $args['action'];
        $email = $this->normalizeEmail($args['email']);

        if (!$email) {
            throw new UserError('Неверный формат e-mail', 'WRONG_FORMAT');
        }

        $saService = $this->container->getOtpService();

        if ($action === 'request') {

            $this->container->getRateService()->checkRateOrThrow('USER_EMAIL_CHANGE', null, ['email' => $email]);

            $foundUser =
                UserModel::query()->filter(['LOGIN' => $email])->first() ?:
                    UserModel::query()->filter(['EMAIL' => $email])->first();

            if ($foundUser) {
                if ($foundUser['ID'] == $currentUserId) {
                    throw new UserError('E-mail уже подтвержден', 'USED_CURRENT');
                } else {
                    throw new UserError('E-mail уже используется другим пользователем', 'USED_OTHER');
                }
            }

            $action = $saService->createAction([
                'NAME' => 'EMAIL_CONFIRM',
                'VALUE' => $email,
            ], OtpEmailConfirmModel::class);

            $action->send($response);

            if ($response->isSuccess()) {
                $action->save();
            }
        } else if ($action === 'confirm') {

            $code = $args['code'];

            $action = $saService->loadActionAndCheck([
                'NAME' => 'EMAIL_CONFIRM',
                'VALUE' => $email
            ], $code);

            $actionEmail = $email;

            $foundUser =
                UserModel::query()->filter(['LOGIN' => $actionEmail])->first() ?:
                    UserModel::query()->filter(['EMAIL' => $actionEmail])->first();

            if ($foundUser) {
                if ($foundUser['ID'] == $currentUserId) {
                    throw new UserError('E-mail уже подтвержден', 'USED_CURRENT');
                } else {
                    throw new UserError('E-mail уже используется другим пользователем', 'USED_OTHER');
                }
            }

            $arFields = [
                'EMAIL' => $actionEmail
            ];

            $user->update($arFields);
            $response->addSuccess('E-mail успешно изменен');

            $this->container->getRateService()->checkRate('USER_EMAIL_CHANGE_SUCCESS', null, ['email' => $email], true);
        }

        return $response->getGraphqlJson();
    }

    function mutationLogout($rootValue, $args, $ctx, $info, Response $response)
    {
        global $USER;

        $response = $this->getResponse();

        $appClient = $this->container->getAppClientService()->getCurrentAppClient();

        if ($appClient) {
            $appClient->logout();
        }

        $USER->Logout();

        return $response->getGraphqlJson();
    }

    function mutationProfile($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();

        $user = $this->container->getUserOrThrow();

        $form = $args['form'];

        $response->setAction(['url' => 'router://user']);

        $arFields = [];

        if (isset($form['NAME'])) {
            $arFields['NAME'] = $form['NAME'];
        }

        if (isset($form['PERSONAL_BIRTHDAY']) && !$user['PERSONAL_BIRTHDAY']) {
            $arFields['PERSONAL_BIRTHDAY'] = \CDatabase::FormatDate($form['PERSONAL_BIRTHDAY'], "DD.MM.YYYY", FORMAT_DATE);
        }

        $user->update($arFields);

        $response->setPayload($user);
        $response->addSuccess('Профиль успешно обновлен', ['notify' => true]);

        return $response->getGraphqlJson();
    }

    function normalizePhone($phone)
    {
        return \Main\Helper\Format::validateMobile($phone);
    }

    function normalizeEmail($email)
    {
        return \Main\Helper\Format::validateEmail($email);
    }
}
