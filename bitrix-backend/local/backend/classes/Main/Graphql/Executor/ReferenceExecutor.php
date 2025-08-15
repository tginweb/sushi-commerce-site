<?php declare(strict_types=1);

namespace Main\Graphql\Executor;

use ArrayObject;
use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Executor\Executor;
use GraphQL\Executor\ReferenceExecutor as VendorReferenceExecutor;
use GraphQL\Executor\Values;
use GraphQL\Language\AST\FieldNode;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Main\DI\Containerable;
use SplObjectStorage;
use TG\Main\Helper;
use Throwable;
use function assert;

/**
 * @phpstan-import-type FieldResolver from Executor
 * @phpstan-import-type Path from ResolveInfo
 * @phpstan-import-type ArgsMapper from Executor
 *
 * @phpstan-type Fields ArrayObject<string, ArrayObject<int, FieldNode>>
 */
class ReferenceExecutor extends VendorReferenceExecutor
{
    use Containerable;


    var $timers = [];

    /**
     * Resolves the field on the given root value.
     *
     * In particular, this figures out the value that the field returns
     * by calling its resolve function, then calls completeValue to complete promises,
     * serialize scalars, or execute the sub-selection-set for objects.
     *
     * @param mixed $rootValue
     * @param list<string|int> $path
     * @param list<string|int> $unaliasedPath
     * @param mixed $contextValue
     *
     * @phpstan-param Path $path
     * @phpstan-param Path $unaliasedPath
     *
     * @param ArrayObject<int, FieldNode> $fieldNodes
     *
     * @return array<mixed>|Throwable|mixed|null
     * @throws InvariantViolation
     *
     * @throws Error
     */
    protected function resolveField(
        ObjectType  $parentType,
                    $rootValue,
        ArrayObject $fieldNodes,
        string      $responseName,
        array       $path,
        array       $unaliasedPath,
                    $contextValue
    )
    {

        $exeContext = $this->exeContext;

        $fieldNode = $fieldNodes[0];
        assert($fieldNode instanceof FieldNode, '$fieldNodes is non-empty');

        $fieldName = $fieldNode->name->value;
        $fieldDef = $this->getFieldDef($exeContext->schema, $parentType, $fieldName);
        if ($fieldDef === null || !$fieldDef->isVisible()) {
            return static::$UNDEFINED;
        }

        $path[] = $responseName;
        $unaliasedPath[] = $fieldName;

        $returnType = $fieldDef->getType();
        // The resolve function's optional 3rd argument is a context value that
        // is provided to every resolve function within an execution. It is commonly
        // used to represent an authenticated user, or request-specific caches.
        // The resolve function's optional 4th argument is a collection of
        // information about the current execution state.
        $info = new ResolveInfo(
            $fieldDef,
            $fieldNodes,
            $parentType,
            $path,
            $exeContext->schema,
            $exeContext->fragments,
            $exeContext->rootValue,
            $exeContext->operation,
            $exeContext->variableValues,
            $unaliasedPath
        );

        $resolveFn = $fieldDef->resolveFn
            ?? $parentType->resolveFieldFn
            ?? $this->exeContext->fieldResolver;

        $argsMapper = $fieldDef->argsMapper
            ?? $parentType->argsMapper
            ?? $this->exeContext->argsMapper;

        $cacheItem = null;

        $this->onQueryStart($path);

        $pathName = join('.', $path);

        $debugRequest = $this->container->getDebugService()->isDebugRequest();

        if (count($path) < 2) {

            try {
                // Build a map of arguments from the field.arguments AST, using the
                // variables scope to fulfill any variable references.
                // @phpstan-ignore-next-line generics of SplObjectStorage are not inferred from empty instantiation
                $this->fieldArgsCache[$fieldDef] ??= new SplObjectStorage();

                $args = $this->fieldArgsCache[$fieldDef][$fieldNode] ??= $argsMapper(Values::getArgumentValues(
                    $fieldDef,
                    $fieldNode,
                    $this->exeContext->variableValues
                ), $fieldDef, $fieldNode, $contextValue);

            } catch (Throwable $error) {
                $result = $error;
            }

            $configCacheEnable = $this->container->getConfigService()->get('GRAPHQL.CACHE_ENABLE', false);

            if ($configCacheEnable) {
                if (!($result instanceof Throwable)) {
                    $cacheParams = $fieldDef->config['cache'];
                    if (isset($cacheParams) && $cacheParams !== false && empty($args['nocache'])) {
                        $cacheParams = !is_bool($cacheParams) ? $cacheParams : [];
                        $cacheArgs = empty($cacheParams['argsIgnore']) ? $args : [];
                        $cacheItem = $this->container->getCacheService()->getProvider()->getGraphqlCacheItem($cacheParams, $path, $cacheArgs);

                        $debugInfo = [
                            'key' => $cacheItem->getKey(),
                            'woa' => !!$cacheParams['argsIgnore'],
                        ];

                        $ttl = $cacheParams['ttl'] ?? 3600;
                        $ttlFormatted = \Main\Helper\Date::secToStr($ttl);

                        if ($debugRequest) {
                            $debugInfo += [
                                'ttl' => $cacheParams['ttl'],
                                'ttlFormatted' => $ttlFormatted,
                                'args' => $cacheArgs
                            ];
                        }

                        $this->container->getGraphqlService()->queryExtension($pathName, [
                            'cache' => $debugInfo
                        ]);
                    }
                }
            }
        }

        if (!$cacheItem || !$cacheItem->isHit()) {


            // Get the resolve function, regardless of if its result is normal
            // or abrupt (error).
            $result = $this->resolveFieldValueOrError(
                $fieldDef,
                $fieldNode,
                $resolveFn,
                $argsMapper,
                $rootValue,
                $info,
                $contextValue
            );

            $result = $this->completeValueCatchingError(
                $returnType,
                $fieldNodes,
                $info,
                $path,
                $unaliasedPath,
                $result,
                $contextValue
            );

        } else {
            $result = $cacheItem->get();
            $this->container->getGraphqlService()->queryExtension($pathName, 'cache.cached', true);
        }

        $cacheSave = $cacheItem && !$cacheItem->isHit();

        if ($cacheSave) {
            $promise = $this->getPromise($result);
            if ($promise !== null) {
                $promise->then(function (&$resolved) use ($returnType, $fieldNodes, $info, $path, $cacheItem, $cacheSave) {
                    $cacheItem->set($resolved);
                    $this->container->getCacheService()->getProvider()->saveItem($cacheItem);
                    $this->onQueryEnd($path);
                    return $resolved;
                });
            } else {
                $cacheItem->set($result);
                $this->container->getCacheService()->getProvider()->saveItem($cacheItem);
                $this->onQueryEnd($path);
            }
        } else {
            $this->onQueryEnd($path);
        }

        return $result;
    }

    function onQueryStart($path)
    {
        $this->timers[join('-', $path)] = microtime(true);
    }

    function onQueryEnd($path)
    {
        $queryStart = $this->timers[join('-', $path)] ?: 0;
        $this->container->getGraphqlService()->setResolverDebugInfo($path, [
            'queryDuration' => microtime(true) - $queryStart
        ]);
    }
}
