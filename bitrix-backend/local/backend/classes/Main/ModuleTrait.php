<?php

namespace Main;

use Aura\Router\Map;
use Bitrix\Main\Context;
use Main\Entity\User\SessionModel;
use Main\Entity\User\UserModel;
use Main\Error\AccessError;
use Main\Service\AppClientService;
use Main\Service\AppService;
use Main\Service\AuthConfirmService;
use Main\Service\AvatarService;
use Main\Service\BitrixAdminPanelService;
use Main\Service\CacheService;
use Main\Service\CommandService;
use Main\Service\CompilerService;
use Main\Service\ConfigService;
use Main\Service\CryptoService;
use Main\Service\DebugService;
use Main\Service\DeviceDetectService;
use Main\Service\EntityService;
use Main\Service\EnumService;
use Main\Service\EnvService;
use Main\Service\EventTemplaterService;
use Main\Service\FieldsService;
use Main\Service\FileService;
use Main\Service\FrontendService;
use Main\Service\GraphqlHelperService;
use Main\Service\GraphqlService;
use Main\Service\HLBlockService;
use Main\Service\IBlockService;
use Main\Service\ImageService;
use Main\Service\MongoService;
use Main\Service\OtpService;
use Main\Service\RateService;
use Main\Service\RedisService;
use Main\Service\RequestService;
use Main\Service\RouterService;
use Main\Service\SettingsService;
use Main\Service\TokenService;
use Main\Service\UserService;
use Main\Service\WebSocketService;
use Review\Core\Service\ReviewService;
use voku\helper\Hooks;

trait ModuleTrait
{

    static $staticCache = [];

    /**
     * @return BitrixAdminPanelService
     */
    function getBitrixAdminPanelService()
    {
        return $this->get(BitrixAdminPanelService::class);
    }

    /**
     * @return EnumService
     */
    function getEnumService()
    {
        return $this->get(EnumService::class);
    }

    /**
     * @return FieldsService
     */
    function getFieldsService()
    {
        return $this->get(FieldsService::class);
    }

    /**
     * @return IBlockService
     */
    function getHlBlockService()
    {
        return $this->get(HLBlockService::class);
    }

    /**
     * @return EnvService
     */
    function getEnvService()
    {
        return $this->get(EnvService::class);
    }

    /**
     * @return EntityService
     */
    function getEntityService()
    {
        return $this->get(EntityService::class);
    }

    /**
     * @return CommandService
     */
    function getCommandService()
    {
        return $this->get(CommandService::class);
    }

    /**
     * @return MongoService
     */
    function getMongoService()
    {
        return $this->get(MongoService::class);
    }

    /**
     * @return Registry
     */
    function getRegistryService()
    {
        return $this->get(Registry::class);
    }

    /**
     * @return GraphqlService
     */
    function getGraphqlService()
    {
        return $this->get(GraphqlService::class);
    }

    /**
     * @return GraphqlHelperService
     */
    function getGraphqlHelperService()
    {
        return $this->get(GraphqlHelperService::class);
    }


    /**
     * @return \Main\Graphql\Router
     */
    function getGraphqlRouter()
    {
        return $this->get(\Main\Graphql\Router::class);
    }


    /**
     * @return AppClientService
     */
    function getAppClientService()
    {
        return $this->get(AppClientService::class);
    }

    /**
     * @return AuthConfirmService
     */
    function getAuthConfirmService()
    {
        return $this->get(AuthConfirmService::class);
    }

    /**
     * @return TokenService
     */
    function getUserTokenService()
    {
        return $this->get(TokenService::class);
    }

    /**
     * @return AvatarService
     */
    function getUserAvatarService()
    {
        return $this->get(AvatarService::class);
    }

    /**
     * @return UserService
     */
    function getUserService()
    {
        return $this->get(UserService::class);
    }

    /**
     * @return ReviewService
     */
    function getReviewService()
    {
        return $this->get(ReviewService::class);
    }

    /**
     * @return RedisService
     */
    function getRedisService()
    {
        return $this->get(RedisService::class);
    }

    /**
     * @return RateService
     */
    function getRateService()
    {
        return $this->get(RateService::class);
    }

    /**
     * @return DeviceDetectService
     */
    function getDeviceDetectService()
    {
        return $this->get(DeviceDetectService::class);
    }

    /**
     * @return AppService
     */
    function getAppService()
    {
        return $this->get(AppService::class);
    }

    /**
     * @return OtpService
     */
    function getOtpService()
    {
        return $this->get(OtpService::class);
    }

    /**
     * @return DebugService
     */
    function getDebugService()
    {
        return $this->get(DebugService::class);
    }

    /**
     * @return CompilerService
     */
    function getCompilerService()
    {
        return $this->get(CompilerService::class);
    }

    /**
     * @return RouterService
     */
    function getRouterService()
    {
        return $this->get(RouterService::class);
    }

    /**
     * @return RequestService
     */
    function getRequestService()
    {
        return $this->get(RequestService::class);
    }

    /**
     * @return CryptoService
     */
    function getCryptoService()
    {
        return $this->get(CryptoService::class);
    }

    /**
     * @return CompilerService
     */
    function getCompiler()
    {
        return $this->get(CompilerService::class);
    }

    /**
     * @return IBlockService
     */
    function getIblockService()
    {
        return $this->get(IBlockService::class);
    }


    /**
     * @return EventTemplaterService
     */
    function getTemplaterService()
    {
        return $this->get(EventTemplaterService::class);
    }

    /**
     * @return FileService
     */
    function getFileService()
    {
        return $this->get(FileService::class);
    }

    /**
     * @return ImageService
     */
    function getImageService()
    {
        return $this->get(ImageService::class);
    }

    /**
     * @return WebSocketService
     */
    function getWebSocketService()
    {
        return $this->get(WebSocketService::class);
    }

    /**
     * @return SettingsService
     */
    function getSettingsService()
    {
        return $this->get(SettingsService::class);
    }

    /**
     * @return FrontendService
     */
    function getFrontendService()
    {
        return $this->get(FrontendService::class);
    }


    /**
     * @return CacheService
     */
    function getCacheService()
    {
        return $this->getCacherService();
    }

    /**
     * @return CacheService
     */
    function getCacherService()
    {
        return $this->get(CacheService::class);
    }

    /**
     * @return ConfigService
     */
    function getConfigService()
    {
        return $this->get(ConfigService::class);
    }

    /**
     * @return Map
     */
    function getRouter()
    {
        return $this->getRouterService()->getRouter();
    }

    /**
     * @return UserModel
     */
    function getUser()
    {
        return $this->getSession()->getUser();
    }

    /**
     * @return SessionModel
     */
    function getSession()
    {
        return SessionModel::getCurrent();
    }

    /**
     * @return UserModel
     */
    function getUserOrThrow()
    {
        return $this->getSession()->getUserOrThrow();
    }

    /**
     * @return Int
     */
    function getUserId()
    {
        global $USER;
        return intval($USER->GetID());
    }

    /**
     * @return Int
     */
    function getUserIdOrThrow($errorClass = AccessError::class)
    {
        global $USER;
        $userId = $USER->GetID();

        if (!$userId)
            throw new $errorClass(null, 'NOT_AUTHORIZED');

        return $userId;
    }

    /**
     * @return boolean
     */
    function isAuthorized()
    {
        global $USER;
        return $USER->IsAuthorized();
    }

    /**
     * @return Int
     */
    function getGuestId()
    {
        return $this->getSession()->getGuestId();
    }

    /**
     * @return Int
     */
    function getUserGustableId()
    {
        return $this->getSession()->getUserGustableId();
    }

    /**
     * @return Int
     */
    function getUserGustableIdOrThrow()
    {
        return $this->getSession()->getUserGustableIdOrThrow();
    }

    /**
     * @return Boolean
     */
    function isDebugRequest()
    {
        return $this->getDebugService()->isDebugRequest();
    }

    function getHooks()
    {
        return Hooks::getInstance();
    }

    function doAction($tag, $arg = null)
    {
        return Hooks::getInstance()->do_action($tag, $arg);
    }

    function doActionRefArray($tag, $arg = [])
    {
        return Hooks::getInstance()->do_action_ref_array($tag, $arg);
    }

    function applyFilters($tag, $value, ...$args)
    {
        return Hooks::getInstance()->apply_filters($tag, $value, ...$args);
    }

    function applyFiltersRef($tag, ...$args)
    {
        return Hooks::getInstance()->apply_filters_ref_array($tag, ...$args);
    }

    function addActions($tags, $function_to_add, $priority = 50, $include_path = null)
    {
        foreach ((array)$tags as $tag) {
            return Hooks::getInstance()->add_action($tag, $function_to_add, $priority, $include_path = null);
        }
    }

    function addAction($tag, $function_to_add, $priority = 50, $include_path = null)
    {
        return Hooks::getInstance()->add_action($tag, $function_to_add, $priority, $include_path = null);
    }

    function addFilter($tag, $function_to_add, $priority = 50, $include_path = null)
    {
        return Hooks::getInstance()->add_filter($tag, $function_to_add, $priority, $include_path = null);
    }

    function applyFiltersCached($tag, $value, $resultCallback = null, $initialCallback = null)
    {
        static $cache = [];

        if (!isset($cache[$tag])) {
            $hooks = Hooks::getInstance();
            if ($initialCallback) {
                $value = $initialCallback($value);
            }
            $result = $hooks->apply_filters($tag, $value);
            if ($resultCallback) {
                $result = $resultCallback($result);
            }
            $cache[$tag] = $result;
        }

        return $cache[$tag];
    }

    function isAdminSection()
    {
        return Context::getCurrent()->getRequest()->isAdminSection();
    }

    function cache($id, $callback)
    {
        if (!isset(self::$staticCache[$id])) {
            self::$staticCache[$id] = $callback();
        }
        return self::$staticCache[$id];
    }
}

