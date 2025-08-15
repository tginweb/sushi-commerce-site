<?php

namespace Main;

use Bitrix\Main\Loader;
use Main\Api\Controller\SettingsController;
use Main\Api\Resolver\AppResolver;
use Main\Api\Resolver\EntityInfoResolver;
use Main\Api\Resolver\EntityResolver;
use Main\Api\Resolver\SessionResolver;
use Main\Api\Resolver\SettingsResolver;
use Main\Api\Resolver\WebSocketResolver;
use Main\Bitrix\IblockPropType\EnumPropType;
use Main\Bitrix\IblockPropType\HookSelect;
use Main\Bitrix\IblockPropType\RefElementWithDescription;
use Main\Dataloader\ElementLoader;
use Main\Dataloader\EnumLoader;
use Main\Dataloader\FieldEnumLoader;
use Main\Dataloader\FileLoader;
use Main\Dataloader\ImageLoader;
use Main\Dataloader\SectionLoader;
use Main\Dataloader\UfEnumLoader;
use Main\Dataloader\UserLoader;
use Main\Error\AccessError;
use Main\Error\BaseError;
use Main\Error\CommonError;
use Main\Error\ExternalServiceError;
use Main\Error\FormError;
use Main\Error\OtpError;
use Main\Error\RateError;
use Main\Graphql\Enum\MessageTypeEnum;
use Main\Graphql\Type\Action\ActionMobileType;
use Main\Graphql\Type\Action\ActionWebType;
use Main\Graphql\Type\CommandType;
use Main\Graphql\Type\ConditionType;
use Main\Graphql\Type\Data\ContactInputType;
use Main\Graphql\Type\Entity\EntityPropType;
use Main\Graphql\Type\File\FileDefType;
use Main\Graphql\Type\File\FileType;
use Main\Graphql\Type\IBlock\ElementConnectionType;
use Main\Graphql\Type\IBlock\ElementFilterInputType;
use Main\Graphql\Type\IBlock\ElementPropEnumType;
use Main\Graphql\Type\IBlock\ElementPropType;
use Main\Graphql\Type\IBlock\SectionConnectionType;
use Main\Graphql\Type\IBlock\SectionFilterInputType;
use Main\Graphql\Type\IBlock\SectionType;
use Main\Graphql\Type\Image\ImageThumbType;
use Main\Graphql\Type\Image\ImageType;
use Main\Graphql\Type\MessageType;
use Main\Graphql\Type\OtpType;
use Main\Graphql\Type\Query\QueryInfoType;
use Main\Graphql\Type\Query\QueryNavInputType;
use Main\Graphql\Type\ResponseStateType;
use Main\Graphql\Type\ResponseType;
use Main\Graphql\Type\SettingsItemType;
use Main\Graphql\Type\UfEnumType;
use Main\Graphql\Type\User\AppClientDebugParamsType;
use Main\Graphql\Type\User\AppClientType;
use Main\Graphql\Type\User\UserAuthConfirmType;
use Main\Graphql\Type\User\UserAvatarElementType;
use Main\Graphql\Type\User\UserAvatarType;
use Main\Graphql\Type\User\UserFamilyInputType;
use Main\Graphql\Type\User\UserFamilyType;
use Main\Graphql\Type\User\UserFilterInputType;
use Main\Graphql\Type\User\UserGroupType;
use Main\Graphql\Type\User\UserProfileTypeType;
use Main\Graphql\Type\User\UserSafeType;
use Main\Graphql\Type\User\UserSessionType;
use Main\Graphql\Type\User\UserType;
use Main\Graphql\Types;
use Main\Lib\Common\BaseModule;
use Main\Lib\Common\Invoker;
use Main\Service\AppClientService;
use Main\Service\AppService;
use Main\Service\AuthConfirmService;
use Main\Service\AvatarService;
use Main\Service\BitrixAdminPanelService;
use Main\Service\CacheService;
use Main\Service\CommandService;
use Main\Service\CompilerService;
use Main\Service\ConfigService;
use Main\Service\CorsService;
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
use Main\Service\LoggerService;
use Main\Service\MongoService;
use Main\Service\OtpService;
use Main\Service\RateService;
use Main\Service\RedisService;
use Main\Service\RequestService;
use Main\Service\RouterService;
use Main\Service\TokenService;
use Main\Service\UserService;
use Main\Service\WebSocketService;
use function TG\Main\AddEventHandler;

class Module extends BaseModule
{
    function __construct()
    {
        Loader::includeModule('main');
    }

    function boot()
    {
        $container = $this->container;
        $container->register(ConfigService::class);
        $container->register(EnvService::class);
        return $this;
    }

    function register($scopes = [])
    {
        Loader::includeModule('iblock');

        $container = $this->container;

        $container->register(CorsService::class);
        $container->register(RedisService::class);
        $container->register(FrontendService::class);

        $container->register(DebugService::class);
        $container->register(AppService::class);
        $container->register(CacheService::class);

        $container->register(GraphqlService::class);
        $container->register(GraphqlHelperService::class);
        $container->register(Registry::class);

        $container->register(LoggerService::class);

        $container->register(CommandService::class);

        $container->register(RequestService::class);
        $container->register(RateService::class);
        $container->register(RouterService::class);
        $container->register(DeviceDetectService::class);

        $container->register(CompilerService::class);
        $container->register(EventTemplaterService::class);

        $container->register(UserService::class);
        $container->register(AppClientService::class);
        $container->register(TokenService::class);
        $container->register(AvatarService::class);
        $container->register(AuthConfirmService::class);
        $container->register(OtpService::class);

        $container->register(EnumService::class);
        $container->register(CryptoService::class);

        $container->register(EntityService::class);
        $container->register(IBlockService::class);
        $container->register(HLBlockService::class);
        $container->register(FieldsService::class);

        $container->register(FileService::class);
        $container->register(ImageService::class);
        $container->register(MongoService::class);

        $container->register(WebSocketService::class);
        $container->register(BitrixAdminPanelService::class);

        new FieldEnumLoader();
        new FileLoader();
        new UfEnumLoader();

        new ElementLoader();
        new SectionLoader();
        new EnumLoader();
        new ImageLoader();
        new UserLoader();

        \Main\Bitrix\UserField\Html::init();
        \Main\Bitrix\UserField\Enum::init();

        AddEventHandler('iblock', 'OnIBlockPropertyBuildList', [HookSelect::class, 'GetIBlockPropertyDescription']);
        AddEventHandler('iblock', 'OnIBlockPropertyBuildList', [EnumPropType::class, 'GetIBlockPropertyDescription']);
        AddEventHandler('iblock', 'OnIBlockPropertyBuildList', [RefElementWithDescription::class, 'GetIBlockPropertyDescription']);

        $this->container->addFilter('entity:types', new Invoker([IblockService::class, 'onBuildEntityTypes']));

        parent::register();
    }

    function registerTypes()
    {
        Types::types([
            'Command' => CommandType::class,
            'ActionMobile' => ActionMobileType::class,
            'ActionWeb' => ActionWebType::class,
            'EntityProp' => EntityPropType::class,
            'File' => FileType::class,
            'FileDef' => FileDefType::class,
            'UfEnum' => UfEnumType::class,
            'Element' => CommandType::class,
            'ElementProp' => ElementPropType::class,
            'ElementPropEnum' => ElementPropEnumType::class,
            'ElementFilterInput' => ElementFilterInputType::class,
            'ElementConnection' => ElementConnectionType::class,
            'Section' => SectionType::class,
            'SectionFilterInput' => SectionFilterInputType::class,
            'SectionConnection' => SectionConnectionType::class,
            'Image' => ImageType::class,
            'ImageThumb' => ImageThumbType::class,
            'Otp' => OtpType::class,
            'User' => UserType::class,
            'UserFilterInput' => UserFilterInputType::class,
            'UserFamily' => UserFamilyType::class,
            'UserFamilyInput' => UserFamilyInputType::class,
            'UserAvatar' => UserAvatarType::class,
            'UserAvatarElement' => UserAvatarElementType::class,
            'UserSafe' => UserSafeType::class,
            'UserGroup' => UserGroupType::class,
            'UserProfile' => UserProfileTypeType::class,
            'UserSession' => UserSessionType::class,
            'UserAuthConfirm' => UserAuthConfirmType::class,
            'AppClient' => AppClientType::class,
            'AppClientDebugParams' => AppClientDebugParamsType::class,
            'SettingsItem' => SettingsItemType::class,
            'ErrorInterface' => [BaseError::class, 'getGraphQlInterfaceType'],
            'QueryInfo' => QueryInfoType::class,
            'Response' => ResponseType::class,
            'ResponseState' => ResponseStateType::class,
            'Message' => MessageType::class,
            'QueryNavInput' => QueryNavInputType::class,
            'ContactInput' => ContactInputType::class,
            'Condition' => ConditionType::class,
        ]);

        Registry::errors([
            'CommonError' => CommonError::class,
            'RateError' => RateError::class,
            'OtpError' => OtpError::class,
            'AccessError' => AccessError::class,
            'FormError' => FormError::class,
            'ExternalServiceError' => ExternalServiceError::class,
        ]);

        Registry::enums([
            'MessageTypeEnum' => MessageTypeEnum::class,
        ]);
    }

    function registerApiControllers()
    {
        new SettingsController();
        //new CacheController();
        //new DebugController();
        //new FileController();
        //new SessionController();
        //new TemplaterController();
    }

    function registerApiResolvers()
    {
        $router = $this->container->getGraphqlRouter();

        $router->addResolversGenerator(
            EntityInfoResolver::create()->addQueries(['list'])
        );

        new EntityInfoResolver();
        new AppResolver();
        new EntityResolver();
        new SessionResolver();
        new WebSocketResolver();
        new SettingsResolver();
    }
}



