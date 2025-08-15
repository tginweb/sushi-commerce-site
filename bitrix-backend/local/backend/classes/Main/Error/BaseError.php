<?php

namespace Main\Error;

use GraphQL\Error;
use GraphQL\Error\ProvidesExtensions;
use GraphQL\Type\Definition\InterfaceType;
use Main\DI\Containerable;
use Main\Graphql\Enum\ErrorNameEnum;
use Main\Graphql\Enum\ErrorTypeEnum;
use Main\Graphql\Type\ErrorType;
use Main\Graphql\Types;
use Main\Helper\Str;

abstract class BaseError extends Error\UserError implements ProvidesExtensions
{
    use Containerable;

    const TYPE = null;
    public $fields = [];
    public $type;

    public $messageGroupId;

    function __construct($name = '', $message = '', $fields = [])
    {
        $this->type = static::TYPE;

        if (!$name)
            $name = static::TYPE;

        $fields['name'] = $name;

        $messages = $fields['messages'] ?? [];
        $fieldMessage = $fields['fieldMessage'] ?? null;

        if (is_array($message)) {
            $messages = $message;
            $message = join(', ', $message);
        }

        $fields['messages'] = $messages;

        $this->fields = $fields;

        if ($name) {
            if (!$message) {
                $message = $this->computeMessage();
            }
            if (!$fieldMessage) {
                $fieldMessage = $this->computeFieldMessage();
                $this->fields['fieldMessage'] = $fieldMessage;
            }
        }

        parent::__construct($message, $fields['code'] ?? null);
    }

    function computeMessage()
    {
        $message = $this->getNameInfo('message', '');
        if ($message) {
            $message = $this->container->getCompilerService()->compileVars($message, $this->fields);
        }
        return $message;
    }

    public function getNameInfo($field = null, $default = null)
    {
        $name = $this->getName();

        if ($name) {
            $info = static::getNameEnumValues()[$name] ?? [
                'message' => null
            ];
            return $field ? (!empty($info[$field]) ? $info[$field] : $default) : $info;
        }
    }

    function getName()
    {
        return $this->getField('name');
    }

    function getField($name, $def = null)
    {
        return $this->fields[$name] ?? $def;
    }

    public static function getNameEnumValues()
    {
        return [];
    }

    function computeFieldMessage()
    {
        $message = $this->getNameInfo('fieldMessage', '');
        if ($message) {
            $message = $this->container->getCompilerService()->compileVars($message, $this->fields);
        }
        return $message;
    }

    public static function getFieldsDefaults()
    {
        return [
            'alert' => true,
            'alertDuration' => null,
            'rel' => null,
            'data' => []
        ];
    }

    public static function getGraphQlType()
    {
        return new ErrorType([
            'name' => static::getGraphQlTypeName(),
            'interfaces' => [Types::getType('ErrorInterface', true)],
            'fields' => static::getGraphQlFieldsInfo()
        ]);
    }

    public static function getGraphQlTypeName()
    {
        return static::typeToGraphqlType(static::TYPE);
    }

    public static function typeToGraphqlType($name)
    {
        return Str::camelCase($name) . 'Error';
    }

    public static function getGraphQlFieldsInfo()
    {
        return [
            'type' => Types::get(ErrorTypeEnum::class),
            'name' => Types::get(ErrorNameEnum::class),
            'message' => Types::string(),
            'messages' => Types::nonNullListOf(Types::string()),
            'fieldMessage' => Types::string(),
            'rel' => Types::JSON(),
            'data' => Types::JSON(),
        ];
    }

    public static function getGraphQlInterfaceType()
    {
        return new InterfaceType([
            'name' => 'ErrorInterface',
            'fields' => static::getGraphQlFieldsInfo(),
            'resolveType' => function ($value) {
                $type = static::typeToGraphqlType($value['type']);
                return Types::getType($type, true);
            }
        ]);
    }

    function getData()
    {
        return $this->getField('data', []);
    }

    function haveMessage()
    {
        return !!$this->getMessage();
    }

    function messageGroupBy()
    {
        return false;
    }

    function getMessageFields($message = null)
    {
        return [
            'type' => 'error',
            'message' => $message ?: $this->getMessage(),
            'alert' => $this->fields['alert'] ?? true,
            'rel' => $this->fields['rel'] ?? '',
        ];
    }

    function getExtensions(): ?array
    {
        $extensions = $this->fields + [
                'type' => static::TYPE
            ];
        return $extensions;
    }

    public function getJson()
    {
        return $this->getFields() + [
                'type' => $this->getType(),
                'message' => $this->getMessage(),
                'messages' => $this->getField('messages'),
                'fieldMessage' => $this->getField('fieldMessage'),
                'code' => $this->getCode(),
            ];
    }

    function getFields()
    {
        return $this->fields;
    }

    function getType()
    {
        return static::TYPE;
    }

    public function setMessageGroup($groupId)
    {
        $this->messageGroupId = $groupId;
    }

    public function getMessageGroup()
    {
        return $this->messageGroupId;
    }
}
