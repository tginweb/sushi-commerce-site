<?php

namespace Event\Pub\Api\Resolver;

use Main\Graphql\Generator\ResolversGenerator;
use Main\Graphql\Types;
use Main\Model\Response;

class ClientPush extends ResolversGenerator
{
    public $ns = 'notice_pub_push_';

    function getMutationMap()
    {
        return [
            'send_queue' => function () {
                return [
                    'type' => Types::boolean(),
                    'resolve' => [$this, 'mutationSendQueue']
                ];
            },
            'update_token' => function () {
                return [
                    'type' => Types::boolean(),
                    'args' => [
                        'pushToken' => Types::string(),
                    ],
                    'resolve' => [$this, 'mutationUpdatePushToken']
                ];
            }
        ];
    }


    function mutationSendQueue($rootValue, $args, $ctx, $info, Response $response)
    {
        $this->container->getClientPushService()->queueSend();
        return true;
    }

    function mutationUpdatePushToken($rootValue, $args, $ctx, $info, Response $response)
    {

        $appClient = $this->container->getAppClientService()->getCurrentAppClient();


        if ($appClient) {
            $appClient->setPushToken($args['pushToken']);
            $appClient->save();
        }
        return true;
    }
}



