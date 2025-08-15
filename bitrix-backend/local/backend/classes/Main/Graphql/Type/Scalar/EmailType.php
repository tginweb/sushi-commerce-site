<?php

namespace Main\Graphql\Type\Scalar;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class EmailType extends ScalarType
{
    // Note: name can be omitted. In this case it will be inferred from class name
    // (suffix "Type" will be dropped)
    public string $name = 'Email';

    public function serialize($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvariantViolation("Could not serialize following value as email: " . Utils::printSafe($value));
        }

        return $this->parseValue($value);
    }

    public function parseValue($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Error("Cannot represent following value as email: " . Utils::printSafeJson($value));
        }

        return $value;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        // Throw GraphQL\Error\Error vs \UnexpectedValueException to locate the error in the query
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        if (!filter_var($valueNode->value, FILTER_VALIDATE_EMAIL)) {
            throw new Error("Not a valid email", [$valueNode]);
        }

        return $valueNode->value;
    }
}
