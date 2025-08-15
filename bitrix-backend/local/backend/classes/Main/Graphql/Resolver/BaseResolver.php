<?php

namespace Main\Graphql\Resolver;

use Main\DI\Containerable;
use Main\Graphql\Type\Query\QueryNavInputType;
use Main\Graphql\Types;
use Main\Model\Response;

class BaseResolver
{
    use Containerable;

    const NAMESPACE = null;
    static $_middlewares;
    static $response;
    static $index = 1;
    public $module;

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function registerQuery($name, $info)
    {
        $gql = $this->getGraphql();

        $info['middlewares'] = $this->getStaticMiddlewares();

        $gql->query($name, $info);
    }

    /**
     * @return \Main\Registry
     */
    public function getGraphql()
    {
        return $this->container->getRegistryService();
    }

    static function getStaticMiddlewares()
    {
        if (!isset(static::$_middlewares)) {
            static::$_middlewares = [];

            $middlewares = static::middlewares();

            foreach ($middlewares as $middleware) {
                static::$_middlewares[] = $middleware;
            }
        }

        return static::$_middlewares;
    }

    static function middlewares()
    {
        return [];
    }

    public function registerMutation($name, $info)
    {
        $gql = $this->getGraphql();

        $info['middlewares'] = $this->getStaticMiddlewares();

        $gql->mutation($name, $info);
    }

    /**
     * @return Response
     */
    public static function response()
    {
        if (!isset(self::$response))
            self::$response = new Response();

        return self::$response;
    }

    public function createResponse()
    {
        return new Response();
    }

    public function getQuerySingleArgs($filterType)
    {
        return [
            'id' => Types::int(),
            'filter' => $filterType,
            'nav' => Types::get(QueryNavInputType::class),
            'viewmode' => Types::string(),
        ];
    }

    public function getQueryRecordsetArgs($filterType)
    {
        $args = [
            'id' => Types::int(),
            'where' => Types::JSON(),
            'nav' => Types::get(QueryNavInputType::class),
            'viewmode' => Types::string(),
            'nocache' => Types::boolean(),
        ];

        if (is_array($filterType)) {
            $args = $filterType + $args;
        } else {
            $args['filter'] = $filterType;
        }

        return $args;
    }

    function cache()
    {

    }
}
