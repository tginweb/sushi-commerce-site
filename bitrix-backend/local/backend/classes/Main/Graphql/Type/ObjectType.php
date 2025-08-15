<?php

namespace Main\Graphql\Type;

use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\ObjectType as VendorType;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Support\Arrayable;
use Main\DI\Containerable;

class ObjectType extends VendorType
{
    use Containerable;

    const NAME = null;

    const OBJECT_ACCESS_FIELDS = false;

    public function __construct($config = [])
    {
        $config += [
            'name' => static::NAME,
            'description' => '',
            'fields' => [$this, 'getFieldsInfo'],
            'resolveField' => [$this, 'resolveField']
        ];
        parent::__construct($config);
    }

    public static function getTypeName()
    {
        return static::NAME;
    }

    public function resolveField($parent, $args, $context, ResolveInfo $info)
    {
        $fieldName = $info->fieldName;

        /*
        if ($this->isExtendedField($fieldName)) {
            return $this->resolveExtendedField($parent, $args, $context, $info);
        }
        */

        $method = 'resolve_' . ucfirst($fieldName);

        if (method_exists($this, $method)) {
            return $this->{$method}($parent, $args, $context, $info);
        } else if (is_array($parent) || (is_object($parent) && $parent instanceof Arrayable)) {
            return $parent[$fieldName];
        } else if (is_object($parent)) {

            if (method_exists($parent, 'get' . $fieldName)) {
                return $parent->{'get' . $fieldName}();
            } else if (method_exists($parent, 'getField')) {
                return $parent->getField($fieldName);
            } else if (static::OBJECT_ACCESS_FIELDS) {
                return $parent->{$fieldName};
            }
        }
    }

    public function isExtendedField($fieldName)
    {
        return isset($this->extendedFields[$fieldName]);
    }

    public function resolveExtendedField($parent, $args, $context, ResolveInfo $info)
    {
        $alterField = $this->extendedFields[$info->fieldName];

        if (isset($alterField)) {
            if (isset($alterField['resolve'])) {
                return $alterField['resolve']($parent, $args, $context, $info);
            }
        }
    }

    function getFieldsInfo()
    {
        return [];
    }

    function resolveResultProcess($result, $element, $propName, $context, $info = null)
    {
        if ($info) {
            $type = $info->fieldDefinition->config['type'];
            switch ($type) {
                case 'Int':
                    $result = isset($result) ? ($result ? intval($result) : 0) : null;
                    break;
                case 'Float':
                    $result = isset($result) ? ($result ? floatval($result) : 0) : null;
                    break;
            }
        }

        if (!empty($info->fieldDefinition->config['compile'])) {
            $result = $this->container->getCompiler()->compileVars($result);
        }

        $this->container->applyFilters('gql:resolve.field', $result, $element, $propName);

        return $result;
    }

    function autotypeValue($val)
    {
        if (is_int($val)) {
            return intval($val);
        } else if (is_float($val)) {
            return floatval($val);
        } else {
            return $val;
        }
    }

    function enum($suffix, $list = [])
    {
        return new EnumType([
            'name' => static::NAME . $suffix,
            'values' => $list
        ]);
    }

    function statusEnum($list = [])
    {
        return new EnumType([
            'name' => static::NAME . 'StatusEnum',
            'values' => array_merge(['success', 'error', 'warning', 'info', 'rate_limit'], $list)
        ]);
    }
}
