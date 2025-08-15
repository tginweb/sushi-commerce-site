<?php

namespace Main\Service;

use Bitrix\Main\Application;
use Bitrix\Main\Diag\SqlTrackerQuery;
use GraphQL\Error\DebugFlag;
use GraphQL\Error\FormattedError;
use GraphQL\Executor\ExecutionResult;
use GraphQL\Executor\Promise\Promise;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema;
use Main\Graphql\ExecutionContext;
use Main\Graphql\Type\Root\Mutation;
use Main\Graphql\Type\Root\Query;
use Main\Graphql\Types;
use Overblog\DataLoader\DataLoader;
use Overblog\DataLoader\Promise\Adapter\Webonyx\GraphQL\SyncPromiseAdapter;
use TG\Main\Helper;


class GraphqlService extends BaseService
{
    public $routeSources = [];
    public $queryStart = 0;
    public $resolversDebug = [];
    public $debugData = [];
    public $timers = [];
    public $timersTime = [];

    public $queryExtensions = [];

    function register()
    {

    }

    function getDebugFlagOutside($error)
    {
        $debug = DebugFlag::NONE;

        if ($this->container->getDebugService()->isDebugRequest()) {
            $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE;
            $debug |= DebugFlag::INCLUDE_TRACE;
        }

        return $debug;
    }

    function processRequest($data)
    {
        $this->queryStart = microtime(true);

        $isAdmin = $this->container->getAppService()->isAdmin();

        if ($data['_query']) {
            $query = $data['_query'];
        } else {
            $query = $data;
        }

        $debug = $this->getDebugFlag($data);

        $config = $this->container->getConfigService();
        $schema = $this->getSchema();

        $context = new ExecutionContext();

        FormattedError::setInternalErrorMessage('Внутренняя ошибка сервера');

        $loggerEnable = $config->get('LOGGER.ENABLE', false);

        if ($loggerEnable && !$isAdmin) {

            $loggerService = $this->container->getLoggerService();

            $loggerEvent = $loggerService->addEvent([
                'type' => 'log',
                'message' => '',
                'name' => 'API_GQL',
                'request' => [
                    'type' => 'gql'
                ],
            ])->queueAdd();

            $loggerService->setRootEvent($loggerEvent);
            $context['loggerEvent'] = $loggerEvent;
            $loggerService->setParentEvent($loggerEvent);
        }

        if ($query[0]) {

            $queries = $query;

            $output = [];

            foreach ($queries as $key => $query) {
                $promise = GraphQL::promiseToExecute(
                    $context['promiseAdapter'],
                    $schema,
                    $query['query'],
                    null,
                    $context,
                    (array)$query['variables']
                );

                if ($context['promiseAdapter'] instanceof SyncPromiseAdapter) {
                    $result = $context['promiseAdapter']->wait($promise)->toArray($debug);
                } else {
                    $result = $promise->then(static function (ExecutionResult $r) use ($debug) {
                        return $r->toArray($debug);
                    });
                }

                if ($result instanceof Promise) {
                    $result = DataLoader::await($result);
                }

                $result = $this->formatResponse($result);

                if ($result['errors'] && $result['loggerEvent']) {
                    $result['loggerEvent']->setType('error');
                    $result['loggerEvent']->setResponseErrors($this->prepareGraphqlErrorsForLogger($output['errors']));
                }

                $output[$key] = $result;
            }
        } else {

            $promise = GraphQL::promiseToExecute(
                $context['promiseAdapter'],
                $schema,
                $query['query'],
                null,
                $context,
                (array)$query['variables']
            );

            if ($context['promiseAdapter'] instanceof SyncPromiseAdapter) {
                $output = $context['promiseAdapter']->wait($promise)->toArray($debug);
            } else {
                $output = $promise->then(static function (ExecutionResult $r) use ($debug) {
                    return $r->toArray($debug);
                });
            }

            if ($output instanceof Promise) {
                $output = DataLoader::await($output);
            }

            $output = $this->formatResponse($output);

            if ($output['errors'] && $context['loggerEvent']) {
                $context['loggerEvent']->setType('error');
                $context['loggerEvent']->setResponseErrors($this->prepareGraphqlErrorsForLogger($output['errors']));
            }


        }


        if ($loggerEvent) {
            $loggerEvent->end();
        }

        return $output;
    }

    function getDebugFlag()
    {
        $debug = DebugFlag::NONE;
        if ($this->container->getDebugService()->isDebugRequest()) {
            $debug = DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE;
        }
        return $debug;
    }

    function getSchema()
    {
        $router = $this->container->getGraphqlRouter();

        $types = [];
        foreach (Types::$typesInclude as $typeName => $typeClass) {
            $types[] = Types::getType($typeName, true);
        }

        return new Schema([
            'query' => new Query($router),
            'mutation' => new Mutation($router),
            'typeLoader' => function ($name) {
                return Types::getType($name, true);
            },
            'resolveField' => function ($root, $args, $context, ResolveInfo $info) {
                die('dd');
            },
            'types' => $types
        ]);
    }

    function formatResponse($data)
    {
        global $DB;

        $duration = microtime(true) - $this->queryStart;

        $data['extensions']['timers'] = $this->timers;
        $data['extensions']['resolversDebug'] = $this->resolversDebug;
        $data['extensions']['debug'] = $this->debugData;
        $data['extensions']['duration'] = $duration;

        $data['extensions']['queries'] = $this->queryExtensions;

        $dbTracker = Application::getConnection()->getTracker();

        $queries = array_filter($dbTracker->getQueries(), function (SqlTrackerQuery $query) {
            $sql = $query->getSql();
            if (preg_match('/^SET/', $sql)) {
                return false;
            }
            return true;
        });

        $totalTime = 0;

        foreach ($queries as $query) {
            $totalTime += $query->getTime();
        }

        $data['extensions']['db'] = [
            'count' => count($queries),
            'time' => round($totalTime, 3),
            'queries' => array_map(function (SqlTrackerQuery $query) {
                return [
                    'sql' => $query->getSql(),
                    'time' => round($query->getTime(), 3),
                    'trace' => $query->getTrace(),
                ];
            }, $queries),
        ];

        return $data;
    }

    function queryExtension($name, $infoName, $infoValue = null)
    {
        $info = $this->queryExtensions[$name] ?? [];
        if (is_array($infoName)) {
            $info = $infoName + $info;
        } else {
            \Main\Helper\Common::arraySetNestedValue($info, $infoName, $infoValue);
        }
        $this->queryExtensions[$name] = $info;
    }

    function prepareGraphqlErrorsForLogger($errors)
    {
        $traceLimit = 200;
        foreach ($errors as &$error) {
            $error['trace'] = array_slice($error['trace'], 0, $traceLimit);
        }
        return $errors;
    }

    function timerCollectorStart($path)
    {
        $val = \Main\Helper\Common::arrayGetNestedValue($this->timersTime, $path);
        if ($val) {
            $this->timerCollectorEnd($path);
        }
        \Main\Helper\Common::arraySetNestedValue($this->timersTime, $path, microtime(true), true);
    }

    function timerCollectorEnd($path)
    {
        $val = \Main\Helper\Common::arrayGetNestedValue($this->timersTime, $path) ?: 0;
        if ($val) {
            $duration = microtime(true) - $val;
            $this->timerCollectorInc($path, $duration);
        }
        \Main\Helper\Common::arraySetNestedValue($this->timersTime, $path, 0, true);
    }

    function timerCollectorInc($path, $incTime)
    {
        $val = \Main\Helper\Common::arrayGetNestedValue($this->timers, $path) ?: 0;
        \Main\Helper\Common::arraySetNestedValue($this->timers, $path, $val + $incTime, true);
    }

    function debugDataPush($path, $data)
    {
        $state = \Main\Helper\Common::arrayGetNestedValue($this->debugData, $path) ?: [];

        $state[] = $data;

        \Main\Helper\Common::arraySetNestedValue($this->debugData, $path, $state, true);
    }

    function debugDataMerge($path, $data)
    {
        $state = \Main\Helper\Common::arrayGetNestedValue($this->debugData, $path) ?: [];

        $state = array_merge_recursive($state, $data);

        \Main\Helper\Common::arraySetNestedValue($this->debugData, $path, $state, true);
    }

    function setResolverDebugInfo($path, $data)
    {
        $state = \Main\Helper\Common::arrayGetNestedValue($this->resolversDebug, $path) ?: [];

        $state = $data + $state;

        \Main\Helper\Common::arraySetNestedValue($this->resolversDebug, $path, $state, true);
    }

    function filterNull($data)
    {
        $res = [];
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $res[$key] = $this->filterNull($val);
            } else if (!is_null($val)) {
                $res[$key] = $val;
            }
        }
        return $res;
    }

    function build()
    {
        foreach ($this->routeSources as $source) {
            if (is_string($source)) {
                if (class_exists($source)) {
                    $source::register($this);
                }
            } else if (is_object($source)) {
                $source->register($this);
            } else if (is_callable($source)) {
                $source($this);
            }
        }

        $router = $this->container->getGraphqlRouter();
        $router->registerResolversGenerator();

        return $this;
    }

    function getRegistry()
    {
        return $this->container->getRegistryService();
    }

    function getGraphqlRouter()
    {
        return $this->container->getGraphqlRouter();
    }
}
