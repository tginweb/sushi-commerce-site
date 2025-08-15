<?php

namespace Shop\Pub\Api\Resolver;

use Main\Entity\IBlock\ElementQuery;
use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Main\Model\Response;
use Shop\Core\Entity\BasketClientItem;
use Shop\Core\Entity\OrderModel;
use Shop\Core\Error\VOrderCouponError;
use Shop\Core\Error\VOrderSubmitError;
use Shop\Core\Graphql\CouponType;
use Shop\Core\Graphql\DeliveryCalculateType;
use Shop\Core\Graphql\OrderType;
use Shop\Core\Graphql\VorderCurrentType;
use Shop\Core\Graphql\VorderInputType;
use Shop\Core\Graphql\VorderSummaryType;
use Shop\Core\Graphql\VorderType;

class VorderResolver extends ResolversGenerator
{
    public $ns = 'sale_vorder_';

    function getQueryMap()
    {
        return parent::getQueryMap() + [
                'current' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::get(VorderCurrentType::class),
                        //'type' => Types::JSON(),
                        'args' => [
                            'check' => Types::boolean(),
                        ],
                        'log' => [
                            'enable' => true,
                            'withResponseData' => true
                        ],
                        'resolve' => [$this, 'queryCurrent']
                    ];
                },
                'summary' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::get(VorderSummaryType::class),
                        'log' => [
                            'enable' => true,
                            'withResponseData' => true
                        ],
                        'resolve' => [$this, 'queryVorderSummary']
                    ];
                },
                'basket_products' => function ($queryName, $queryParams) {
                    return [
                        'type' => Types::listOf(Types::getType('ProductElement')),
                        'resolve' => [$this, 'queryBasketProducts']
                    ];
                },
            ];
    }

    function getMutationMap()
    {
        return parent::getMutationMap() + [
                'new' => function ($queryName, $queryParams) {
                    return [
                        'payload' => [
                            'vorder' => Types::get(VorderType::class),
                        ],
                        'resolve' => [$this, 'mutationNew']
                    ];
                },
                'apply' => function ($queryName, $queryParams) {
                    return [
                        'payload' => [
                            'vorder' => Types::get(VorderType::class),
                        ],
                        'args' => [
                            'order' => Types::get(VorderInputType::class),
                            'params' => Types::JSON(),
                        ],
                        'resolve' => [$this, 'mutationApply']
                    ];
                },
                'basket' => function ($queryName, $queryParams) {
                    return [
                        'payload' => [
                            'vorder' => Types::get(VorderType::class),
                        ],
                        'args' => [
                            'order' => Types::get(VorderInputType::class),
                            'action' => Types::string(),
                            'build' => Types::JSON(),
                        ],
                        'resolve' => [$this, 'mutationBasket']
                    ];
                },
                'reserve' => function ($queryName, $queryParams) {
                    return [
                        'payload' => [
                            'vorder' => Types::get(VorderType::class),
                            'calc' => Types::get(DeliveryCalculateType::class),
                        ],
                        'args' => [
                            'order' => Types::get(VorderInputType::class),
                        ],
                        'resolve' => [$this, 'mutationReserve']
                    ];
                },
                'coupon' => function ($queryName, $queryParams) {
                    return [
                        'payload' => [
                            'vorder' => Types::get(VorderType::class),
                            'coupon' => Types::get(CouponType::class),
                        ],
                        'args' => [
                            'order' => Types::get(VorderInputType::class),
                            'action' => Types::string(),
                            'couponCode' => Types::string()
                        ],
                        'resolve' => [$this, 'mutationCoupon']
                    ];
                },
                'submit' => function ($queryName, $queryParams) {
                    return [
                        'payload' => [
                            'vorder' => Types::get(VorderType::class),
                            'orderId' => Types::int(),
                            'orderUrl' => Types::string(),
                            'order' => Types::get(OrderType::class),
                        ],
                        'args' => [
                            'order' => Types::get(VorderInputType::class),
                        ],
                        'resolve' => [$this, 'mutationSubmit']
                    ];
                },
                'sync' => function ($queryName, $queryParams) {
                    return [
                        'payload' => [
                            'vorder' => Types::get(VorderType::class),
                        ],
                        'args' => [
                            'order' => Types::get(VorderInputType::class),
                        ],
                        'resolve' => [$this, 'mutationSync']
                    ];
                },
            ];
    }

    function queryCurrent($rootValue, $args, $ctx)
    {
        //$res = $this->container->getIblockService()->getIBlock('bonus_level');
        //$this->container->getIblockService()->getProps(1);
        //$res = $this->container->getIblockService()->getProps(1, ['COMPONENT_IS']);
        //$res = $this->container->getIblockService()->getProps(['bonus_level', 'catalog'], [254, 255]);
        //$res = $this->container->getIblockService()->getPropertyEnums(1, ['COMPONENT_IS', 'HOLIDAY']);
        //$res = $this->container->getIblockService()->getPropertyEnums(1, []);
        //return $res;

        $data = $this->container->getIblockService()->getProps('vacancy');

        //die(json_encode($data));
        $vorder = $this->container->getVorder();

        if ($args['check']) {
            $vorder->check(true);
            $vorder->recalculate();
            $vorder->save();
        }

        $vorder->validate();

        return $vorder;
    }

    function queryVorderSummary($rootValue, $args, $ctx)
    {
        $vorder = $this->container->getVorder();

        return $vorder->getSummary();
    }

    function queryBasketProducts($rootValue, $args, $ctx)
    {
        $vorder = $this->container->getVorder();

        $basketProductsIds = array_values($vorder->getBasket()->getBasketItemsCollectionInstance()->getProductIds());
        $favProductIds = $this->container->getFavService()->getElementsIds();

        $ids = array_unique(array_merge($basketProductsIds, $favProductIds));
        $idsReady = [];

        if (!empty($ids)) {
            $buildIblockId = 37;

            $rs = \CIBlockElement::getList([], ['ID' => $ids], false, false, ['ID', 'IBLOCK_ID']);

            while ($row = $rs->fetch()) {
                if ($row['IBLOCK_ID'] == $buildIblockId) {
                    $idsReady[$row['ID']] = $row['ID'];
                }
            }
        }

        return !empty($idsReady) ? ElementQuery::getComplexList($idsReady) : [];
    }

    function mutationNew($rootValue, $args, $ctx, $info, Response $response)
    {
        $vorder = $this->container->getVorder();
        $vorder->new();
        $response->setPayload($vorder);
    }

    function mutationBasket($rootValue, $args, $ctx, $info, Response $response)
    {
        $vorderInput = $args['order'];

        $vorder = $this->container->getVorder();
        $basketService = $this->container->getBasketService();
        $basket = $vorder->getBasket();
        $basketService->setEventContextName('mutation');
        $basketService->mutationSync($vorderInput['basket'], $basket);

        if ($args['action'] === 'add') {
            if (!empty($args['build'])) {
                $buildProductId = $args['build'][0]['productId'];
                $clientItem = new BasketClientItem([
                    'PRODUCT_ID' => $buildProductId,
                    'QUANTITY' => 1,
                ]);
                $basketItem = $clientItem->createBasketItem($basket);
                $basketItem->save();
                $basket->save();
            }
        }

        $vorder->recalculate();
        $vorder->reloadBasket();
        $vorder->validate($response);
        $response->setPayloadData('vorder', $vorder);
    }

    function mutationAuth($rootValue, $args, $ctx, $info, Response $response)
    {
        $this->updateFromClientInput($inputOrder, $action);
        if ($profile = $this->getUserProfile()) {
            $order->loadProfileProps($profile);
            $this->setProfileId($profile['ID']);
            $this->load(true);
        }
        $this->save();
    }

    function mutationApply($rootValue, $args, $ctx, $info, Response $response)
    {
        $vorder = $this->container->getVorder();

        $vorder->updateFromClientInput($args['order']);
        $vorder->save();
        //$vorder->validate($response);

        $response->setPayloadData('vorder', $vorder);
    }

    function mutationReserve($rootValue, $args, $ctx, $info, Response $response)
    {
        $vorder = $this->container->getVorder();
        $vorder->setResult($response);
        $vorder->updateFromClientInput($args['order']);
        $reserveResult = $vorder->reserve($args, $response);

        $vorder->save();
        $vorder->validate();

        $response->setPayloadData('vorder', $vorder);

        $profileId = $args['profileId'];

        if ($profileId && $reserveResult['deliveryFreeFromPrice']) {
            $profile = $this->container->getOrderProfileService()->getProfileByUserCached(null, $profileId);
            if ($profile) {
                $profile->setPropValue('DELIVERY_FREE_FROM_PRICE', $reserveResult['deliveryFreeFromPrice']);
                $profile->setPropValue('DELIVERY_FREE_UPDATED_TIME', time());
                $profile->updateChangedProps();
            }
        }
    }

    function mutationCoupon($rootValue, $args, $ctx, $info, Response $response)
    {
        $vorder = $this->container->getVorder();
        $vorder->setResult($response);
        $order = $vorder->getOrder();
        $response->setPayloadData('vorder', $vorder);

        switch ($args['action']) {
            case 'apply':
                $vorder->updateFromClientInput($args['order']);
                $res = $order->couponApply($args['couponCode']);
                if ($res === true) {
                    $vorder->recalculate();
                    $vorder->save();
                    $response->setPayloadData('coupon', $order->getCoupon());
                    $response->emit('vorder.recalculated');
                    $response->addSuccess('Промокод успешно добавлен');
                } else {
                    throw VOrderCouponError::forApply();
                }
                break;
            case 'delete':
                $vorder->updateFromClientInput($args['order']);
                $res = $order->couponDelete($args['couponCode']);
                if ($res === true) {
                    $vorder->recalculate();
                    $vorder->save();
                    $response->emit('vorder.recalculated');
                    $response->addSuccess('Промокод успешно удален');
                }
                break;
        }
    }

    function mutationSubmit($rootValue, $args, $ctx, $info, Response $response)
    {
        $userId = $this->container->getUserId();
        $vorder = $this->container->getVorder();

        $vorder->setResult($response);
        $vorder->updateFromClientInput($args['order']);

        $vorder->save();

        $response->setPayloadData('vorder', $vorder);
        $vorder->validate();

        if ($response->haveErrors()) {
            return;
        }

        $vorder->confirm();

        if ($response->haveErrors()) {
            return;
        }

        if ($orderId = $vorder->order->getId()) {
            $order = OrderModel::query()->getById($orderId);
        }

        if (!$order) {
            throw VOrderSubmitError::forCommon();
        }

        $response->addSuccess('Заказ успешно оформлен');
        $vorder->new();

        $url = $userId ? '/personal/order?context=confirm-success' : '/orders-active?context=confirm-success';

        $response->setPayloadData('orderId', $order->getId());
        $response->setPayloadData('orderUrl', $url);
        $response->setPayloadData('order', $order);
    }
}



