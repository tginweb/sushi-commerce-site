<?php

namespace Shop\Pub\Api\Resolver;

use Main\Graphql\Generator\EntityResolversGenerator;
use Main\Graphql\Type\IBlock\ElementFilterInputType;
use Main\Graphql\Types;
use Main\Lib\Date\DateTime;
use Main\Model\Response;
use Shop\Core\Entity\OrderProfile;
use Shop\Core\Enum\TimeModeEnum;
use Shop\Core\Graphql\DeliveryCalculateType;
use Shop\Core\Graphql\OrderAttributesValueInput;
use Shop\Core\Graphql\OrderProfileInput;
use Shop\Core\Graphql\OrderProfileType;

class ProfileResolver extends EntityResolversGenerator
{
    public $ns = 'sale_profile_';

    public string $modelClass = OrderProfile::class;

    public string $modelTypeClass = OrderProfileType::class;
    public string $filterTypeClass = ElementFilterInputType::class;

    public string $modelInputTypeClass = OrderProfileInput::class;

    function getQueryMap()
    {
        return parent::getQueryMap() + [

            ];
    }

    function getMutationMap()
    {
        return [
            'default' => function ($queryName, $queryParams) {
                return [
                    'payload' => Types::get($this->modelTypeClass),
                    'args' => [
                        'id' => Types::int(),
                    ],
                    'resolve' => [$this, 'mutateDefault']
                ];
            },
            'delete' => function ($queryName, $queryParams) {
                return [
                    'payload' => Types::int(),
                    'args' => [
                        'id' => Types::int(),
                    ],
                    'resolve' => [$this, 'mutateDelete']
                ];
            },
            'save' => function ($queryName, $queryParams) {
                return [
                    'payload' => Types::get($this->modelTypeClass),
                    'args' => [
                        'id' => Types::int(),
                        'attrs' => Types::get(OrderAttributesValueInput::class),
                    ],
                    'resolve' => [$this, 'mutateSave']
                ];
            },
            'calc_delivery' => function ($queryName, $queryParams) {
                return [
                    'payload' => Types::get(DeliveryCalculateType::class),
                    'args' => [
                        'profileId' => Types::int(),
                        'attrs' => Types::get(OrderAttributesValueInput::class),
                        'basket' => Types::JSON(),
                        'timeMode' => Types::get(TimeModeEnum::class),
                        'timeNeed' => Types::int(),
                    ],
                    'resolve' => [$this, 'mutateCalcDelivery']
                ];
            },
        ];
    }

    function mutateCalcDelivery($rootValue, $args, $ctx, $info, Response $response)
    {
        $profileId = $args['profileId'];
        $profileAttrs = $args['attrs'];
        $basket = $args['basket'];
        $timeMode = $args['timeMode'];
        $timeNeed = $args['timeNeed'];

        $vorder = $this->container->getVorder();

        if (!empty($args['basket'])) {
            $vorder->updateFromClientInput([
                'basket' => $args['basket'],
            ]);
        }

        $order = $vorder->getOrder();

        if (empty($profileAttrs)) {
            $profiles = $this->container->getOrderProfileService()->getProfilesByUserCached()->prepareForClient();
            $profile = $profiles[$profileId];
            if ($profile) {
                //$profileAttrs = $profile->getAttributesValues();
            }
        }

        if (!empty($profileAttrs)) {
            foreach ($profileAttrs as $propCode => $propValue) {
                $order->setPropVal($propCode, $propValue);
            }
        }

        if ($timeMode === TimeModeEnum::CUSTOM) {
            $time = DateTime::fromTimestamp($timeNeed);
        } else {
            $time = DateTime::now();
        }

        $order->setPropVal('TIME_MODE', $timeMode);
        $order->setPropVal('DATE', $time->formatToDate());
        $order->setPropVal('TIME', $time->formatToTime());
        //$order->setPropVal('TIME', '05:40');

        $user = $this->container->getUser();
        $phone = null;

        if ($user) {
            $phone = $user->getPhone();
        }

        if (!$phone) {
            $phone = $order->getPhone();
        }

        $order->setPropVal('PHONE', $phone);

        $calc = $this->container->getDeliveryService()->deliveryCalculate('courier', $order);
        $response->setPayload($calc);
    }

    function queryList($rootValue, $args, $ctx)
    {
        $userId = $this->container->getUserGustableId();
        if (!$userId)
            return [];
        return array_values($this->container->getOrderProfileService()->getProfilesByUserCached($userId)->prepareForClient()->all());
    }

    function querySingle($rootValue, $args, $ctx)
    {
        $userId = $this->container->getUserGustableId();
        if (!$userId)
            return null;
        $profiles = $this->container->getOrderProfileService()->getProfilesByUserCached($userId)->prepareForClient();
        return $profiles[$args['id']];
    }

    function mutateDefault($rootValue, $args, $ctx, $info, Response $response)
    {
        $response = $this->getResponse();
        $service = $this->container->getOrderProfileService();

        $profileId = $args['id'];
        $profiles = $service->getProfilesByUserCached();

        foreach ($profiles as $profile) {
            if ($profile['ID'] == $profileId) {
                $profile->updateValues([
                    'PROFILE_DEFAULT' => 'Y'
                ]);
            } else {
                $profile->updateValues([
                    'PROFILE_DEFAULT' => 'N'
                ]);
            }
        }

        $response->addSuccess('Адрес по умолчанию изменен');
        return $response->getGraphqlJson();
    }

    function mutateDelete($rootValue, $args, $ctx, $info, Response $response)
    {
        $service = $this->container->getOrderProfileService();
        $profile = $service->getProfileByUserCached(null, $args['id']);

        if ($profile) {
            $profile->deleteValues();
            $profile->delete();

            $profiles = $service->getProfilesByUserCached(null, true);

            if ($profiles->count()) {

                $defaultProfileId = null;

                foreach ($profiles as $profile) {
                    if ($profile->isDefault()) {
                        $defaultProfileId = $profile->getId();
                    }
                }

                if (!$defaultProfileId) {
                    $defaultProfileId = $profiles->first()['ID'];
                }

                $response->addSuccess('Профиль удален');

                $service->unsetUserProfilesDefault();

                if ($defaultProfileId && $profiles[$defaultProfileId]) {
                    $profiles[$defaultProfileId]->updateValues([
                        'PROFILE_DEFAULT' => 'Y'
                    ]);
                }
            }
        }

        $response->emitEntity('deleted', $profile);
        return $response->getGraphqlJson();
    }

    function mutateSave($rootValue, $args, $ctx, $info, Response $response)
    {
        $service = $this->container->getOrderProfileService();
        $userId = $this->container->getUserGustableIdOrThrow();

        $inputAttrs = $args['attrs'];
        $props = $inputAttrs;

        if (empty($args['id'])) {
            $currentDateTime = new \Bitrix\Main\Type\DateTime();
            $profile = OrderProfile::create([
                'NAME' => 'Профиль',
                'USER_ID' => $userId,
                'PERSON_TYPE_ID' => 1,
                'DATE_UPDATE' => $currentDateTime
            ]);
            $profile->updateValues($props);
            $profile = $service->getProfileByUserCached(null, $profile['ID']);
            $service->unsetUserProfilesDefault();
            $profile->updateValues([
                'PROFILE_DEFAULT' => 'Y'
            ]);
            $response->emitEntity('created', $profile);
            $response->addSuccess('Профиль успешно создан');
        } else {
            $profile = $service->getProfileByUserCached(null, $args['id']);
            if ($profile) {
                $address = $profile->getNameComputed();
                $profile['NAME'] = $address;
                $profile->updateValues($props);
                $profile->save(['NAME']);
                $response->emitEntity('updated', $profile);
                $response->addSuccess('Профиль успешно обновлен');
            }
        }

        /*
        if ($profile) {

            if ($userId > 0) {
                BuyerStatistic::calculate($userId, 'RUB', SITE_ID);
            }

            $newAddress = $profile->getNameComputed();

            if ($prevAddress !== $newAddress || $profile->needFetchServiceData()) {
                $res = $this->container->getOrderProfileService()->fetchProfileServiceData(
                    $profile,
                    null,
                    [
                        'request' => 10,
                        'connect' => 5
                    ]
                );
                if ($res) {
                    $profile->updateChangedProps();
                }
            }
        }
        */

        $response->setPayload($profile);
    }
}



