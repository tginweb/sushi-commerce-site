<?php

namespace Event\Pub\Api\Resolver;

use Event\Core\Graphql\ClientNoticeType;
use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Main\Model\Response;

class ClientNotice extends ResolversGenerator
{
    public $ns = 'notice_pub_';

    function getQueryMap()
    {
        return [
            'list' => function () {
                return [
                    'type' => Types::nonNullListOf(Types::getNonNull(ClientNoticeType::class)),
                    'resolve' => [$this, 'queryList']
                ];
            }
        ];
    }

    function getMutationMap()
    {
        return [
            'sync_readed' => function () {
                return [
                    'payload' => Types::listOf(Types::get(ClientNoticeType::class)),
                    'args' => [
                        'ids' => Types::JSON(),
                    ],
                    'resolve' => [$this, 'mutationSyncReaded']
                ];
            }
        ];
    }

    function mutationSyncReaded($rootValue, $args, $ctx, $info, Response $response)
    {
        $ids = $args['ids'];
        if (!empty($ids))
            $this->container->getClientNoticeService()->saveCurrentUserNoticesReaded($ids);
    }

    function queryList($rootValue, $args)
    {
        return $this->container->getClientNoticeService()->getCurrentUserNotices();
    }
}



