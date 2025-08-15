<?php

namespace Review\Pub\Api\Resolver;

use Company\Core\Entity\Office;
use Main\Entity\IBlock\ElementModel;
use Main\Graphql\Generator\ElementResolversGenerator;
use Main\Graphql\Types;
use Main\Model\Response;
use Main\Service\IBlockService;
use Review\Core\Graphql\ReviewType;
use Shop\Core\Entity\OrderModel;
use function TG\Review\Pub\Api\Resolver\ConvertTimeStamp;

class Resolver extends ElementResolversGenerator
{
    public $ns = 'review_';

    function getQueryMap()
    {
        return parent::getQueryMap() + [];
    }

    function getMutationMap()
    {
        return [
            'service_review' => function ($queryName, $queryParams) {
                return [
                    'payload' => Types::get(ReviewType::class),
                    'args' => [
                        'model' => Types::JSON(),
                    ],
                    'resolve' => [$this, 'mutationServiceReview']
                ];
            },
            'order_guest_review' => function ($queryName, $queryParams) {
                return [
                    'payload' => [
                        'redirectUrl' => Types::string(),
                        'departmentId' => Types::int(),
                        'departmentName' => Types::string(),
                    ],
                    'args' => [
                        'type' => Types::string(),
                        'orderServiceId' => [
                            'type' => Types::int(),
                            'description' => 'Order service id',
                        ]
                    ],
                    'resolve' => [$this, 'mutationOrderGuestReview'],
                ];
            }
        ];
    }

    function mutationOrderGuestReview($rootValue, $args, $ctx, $info, Response $response)
    {
        $orderServiceId = $args['orderServiceId'];
        $positive = $args['type'] === 'pos';

        if ($orderServiceId) {

            /** @var OrderModel $order */
            $order = OrderModel::query()->filter([
                'ACCOUNT_NUMBER' => $orderServiceId
            ])->first();
        }

        if ($order) {

            $user = $order->getUserModel();

            $element = ElementModel::query()->filter([
                'PROPERTY_CONTEXT_ID' => $order->getId(),
            ])->first();

            if (!$element) {
                $element = ElementModel::create([
                    'NAME' => 'Отзыв на заказ',
                    'CODE' => time(),
                ]);
            }

            $enumTargetId = IBlockService::i()->getPropertyEnumIdByXmlId('TARGET', 'order', ElementModel::getIblockIdOrThrow());

            $element->setPropValue('TARGET', $enumTargetId);
            $element->setPropValue('RATING', $positive ? 5 : 1);

            $element->setPropValue('CONTEXT_ID', intval($order->getId()));
            $element->setPropValue('AUTHOR_USER_ID', $order->getUserId());

            $element->setPropValue('AUTHOR_PHONE', $user ? $user->getPhone() : $order->getPhone());
            $element->setPropValue('AUTHOR_NAME', $user ? $user->getNameFull() : $order->getBuyerName());

            $element->setFieldValue('DETAIL_TEXT', ($positive ? 'положительный' : 'негативный') . ' отзыв по смс на заказ № ' . $orderServiceId);
            $element->setFieldValue('ACTIVE', 'N');
            $element->setFieldValue('ACTIVE_FROM', \ConvertTimeStamp(time(), "FULL"));

            $element->save();

            if ($positive) {
                $department = $order->getDepartment();
                if (!$department) {
                    $departments = Office::query()->filter([
                        'PROPERTY_ROLES_XML_ID' => 'delivery',
                        '!PROPERTY_GIS_URL' => ''
                    ])->withViewList()->getList();
                    $department = $departments[array_rand($departments->all())];
                }
                $response->setPayloadData('departmentId', $department['ID']);
                $response->setPayloadData('departmentName', $department['NAME']);
                $redirectUrl = $department->getProp('GIS_URL');
            } else {
                $redirectUrl = 'https://t.me/sushistudio_bot?start=' . $orderServiceId;
            }
        } else {
            if ($positive) {
                $departments = Office::query()->filter([
                    'PROPERTY_ROLES_XML_ID' => 'delivery',
                    '!PROPERTY_GIS_URL' => ''
                ])->withViewList()->getList();
                $department = $departments[array_rand($departments->all())];
                $response->setPayloadData('departmentId', $department['ID']);
                $response->setPayloadData('departmentName', $department['NAME']);
                $redirectUrl = $department->getProp('GIS_URL');
            } else {
                $redirectUrl = 'https://t.me/sushistudio_bot';
            }
        }

        $response->setPayloadData('redirectUrl', $redirectUrl);

        return $response->getGraphqlJson();
    }

    function mutationServiceReview($rootValue, $args, $ctx, $info, Response $response)
    {

        $user = $this->container->getUser();
        $model = $args['model'];
        $response = $this->getResponse();

        $response->addError('Error order not found');
        return $response->getGraphqlJson();


        $element = ElementModel::create([
            'NAME' => 'Отзыв на сервис',
            'CODE' => time(),
        ]);

        $element->setPropValue('TARGET', 'service', 'XML_ID');

        $element->setFieldValue('DETAIL_TEXT', $model['TEXT']);
        $element->setFieldValue('ACTIVE', 'N');
        $element->setFieldValue('ACTIVE_FROM', ConvertTimeStamp(time(), "FULL"));

        $element->setPropValue('RATING', $model['RATING']);

        $element->setPropValue('AUTHOR_NAME', $model['AUTHOR_NAME']);
        $element->setPropValue('AUTHOR_PHONE', $model['AUTHOR_PHONE']);

        if ($user) {
            $element->setPropValue('AUTHOR_USER_ID', $user['ID']);
        }

        $element->save();

        $response->addSuccess('Отзыв успешно отправлен');
        $response->emitEntity('updated', $element);
        $response->emitEntity('payload', $element);

        $response->setPayload($element);

        return $response->getJson();
    }


    function queryProductRecordset($rootValue, $args)
    {
        global $USER;

        $query = ElementModel::query()
            ->setClientQuery($args)
            ->withViewList()
            ->pushFilter([
                "LOGIC" => "OR",
                'ACTIVE' => 'Y',
                'PROPERTY_AUTHOR_USER_ID' => $USER->GetID()
            ])
            ->sort('ACTIVE_FROM', 'DESC')
            ->filterProps([
                'PROPERTY_TARGET_XML_ID' => 'product'
            ]);

        if ($args['elementId'])
            $query->filterProps([
                'PROPERTY_ELEMENT_ID_VALUE' => $args['elementId'],
            ]);

        return $query->getGraph();
    }
}
