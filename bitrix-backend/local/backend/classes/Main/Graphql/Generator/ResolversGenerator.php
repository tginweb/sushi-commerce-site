<?php

namespace Main\Graphql\Generator;

use GraphQL\Type\Definition\ObjectType;
use Main\DI\Containerable;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Types;
use Main\Graphql\Wrapper\MutationWrapper;
use Main\Model\Response;

class ResolversGenerator
{
    use Containerable;

    static $response;

    public $ns;
    public $nsCamelized;

    public array $queries = [];
    public array $mutations = [];

    public bool $cache = false;
    public array $cacheConfig = [];

    function __construct($ns = null)
    {
        if ($ns)
            $this->ns = $ns;
    }

    static function create($ns = null)
    {
        return new static($ns);
    }

    function addAll()
    {
        $this->addAllQueries();
        $this->addAllMutations();
        return $this;
    }

    function addAllQueries()
    {
        $this->addQueries(array_keys($this->getQueryMap()));
        return $this;
    }

    function addQueries($queries = [])
    {
        foreach ($queries as $name => $params) {
            if (is_numeric($name)) {
                $name = $params;
                $params = [];
            }
            if (!is_array($params)) {
                $params = [];
            }
            $this->addQuery($name, $params);
        }
        return $this;
    }

    function addQuery($name, $params = [])
    {
        $this->queries[$name] = !is_array($params) ? [] : $params;
        return $this;
    }

    function getQueryMap()
    {
        return [];
    }

    function addAllMutations()
    {
        $this->addMutations(array_keys($this->getMutationMap()));
        return $this;
    }

    function addMutations($mutations = [])
    {
        foreach ($mutations as $name => $params) {
            if (is_numeric($name)) {
                $name = $params;
                $params = [];
            }
            if (!is_array($params)) {
                $params = [];
            }
            $this->addMutation($name, $params);
        }
        return $this;
    }

    function addMutation($name, $params = [])
    {
        $this->mutations[$name] = !is_array($params) ? [] : $params;
        return $this;
    }

    function getMutationMap()
    {
        return [];
    }

    function cache($config = [])
    {
        if ($config === false) {
            $this->cache = false;
        } else if ($config === true) {
            $this->cache = true;
            $this->cacheConfig = [];
        } else {
            $this->cache = true;
            $this->cacheConfig = $config;
        }
        return $this;
    }

    function getQueryCacheConfig($queryName = null)
    {
        $queryParams = $this->getQueryParams($queryName);

        if (!isset($queryParams))
            return false;

        $globalCache = $this->isCachable();
        $globalCacheConfig = $this->getCacheConfig();

        $queryCachable = $queryParams['cache'] ?? null;

        if ($globalCache && $queryCachable !== false || $queryCachable) {
            $queryCachable = is_array($queryCachable) ? $queryCachable : [];
            $config = $queryCachable + $globalCacheConfig;
            return $config;
        }

        return false;
    }

    function getQueryParams($name = null)
    {
        return $this->queries[$name] ?? null;
    }

    function isCachable()
    {
        return $this->cache;
    }

    function getCacheConfig()
    {
        return $this->cacheConfig;
    }

    function generateQueries()
    {
        $result = [];
        foreach ($this->getQueryMapNamespaced() as $nameFull => $query) {
            $queryInfo = $query['constructor']($nameFull, $query['params']);
            $result[$nameFull] = $queryInfo;
        }
        return $result;
    }

    function generateMutations()
    {
        $result = [];
        foreach ($this->getMutationMapNamespaced() as $nameFull => $query) {
            $queryInfo = $query['constructor']($nameFull, $query['params']);
            $wrapper = new MutationWrapper($queryInfo, $nameFull);
            $result[$nameFull] = $wrapper->getDefinition();
        }
        return $result;
    }

    function getQueryMapNamespaced()
    {
        $res = [];
        $ns = $this->getNs();

        foreach ($this->getQueryMap() as $name => $constructor) {
            if ($this->isQueryEnable($name)) {
                $params = $this->getQueryParams($name);
                $res[$ns . $name] = [
                    'name' => $name,
                    'params' => $params,
                    'constructor' => $constructor,
                ];
            }
        }

        return $res;
    }

    function getNs()
    {
        return $this->ns;
    }

    function isQueryEnable($name)
    {
        return isset($this->queries[$name]);
    }

    function getMutationMapNamespaced()
    {
        $res = [];
        $ns = $this->getNs();

        foreach ($this->getMutationMap() as $name => $constructor) {
            if ($this->isMutationEnable($name)) {
                $params = $this->getMutationParams($name);
                $res[$ns . $name] = [
                    'name' => $name,
                    'params' => $params,
                    'constructor' => $constructor,
                ];
            }
        }

        return $res;
    }

    function isMutationEnable($name)
    {
        return isset($this->mutations[$name]);
    }

    function getMutationParams($name = null)
    {
        return $this->mutations[$name] ?? null;
    }

    function typeResponseWithPayload($name, $fields = [])
    {
        $payload = is_array($fields) ? new ObjectType([
            'name' => $name . 'Payload',
            'fields' => $fields
        ]) : $fields;

        return new ObjectType([
            'name' => $name,
            'fields' => [
                'payload' => $payload,
                'state' => Types::getNonNull(ResponseStateType::class)
            ]
        ]);
    }

    function getMutationPayloadedType($name, $fields)
    {
        if (is_array($fields)) {
            $payload = new ObjectType([
                'name' => $name . 'Payload',
                'fields' => $fields
            ]);
        } else {
            $payload = $fields;
        }
        $rootFields = [
            'state' => Types::getNonNull(ResponseStateType::class)
        ];
        if ($payload) {
            $rootFields['payload'] = $payload;
        }
        return Types::ensureType($name, $rootFields);
    }


    function getResponse()
    {
        return self::response();
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
}
