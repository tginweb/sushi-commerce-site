<?php

declare(strict_types=1);

namespace Main\Graphql\Rule;

use GraphQL\Language\AST\NodeKind;
use GraphQL\Language\AST\VariableDefinitionNode;
use GraphQL\Language\Visitor;
use GraphQL\Validator\Rules\QuerySecurityRule;
use GraphQL\Validator\ValidationContext;

class CacheRule extends QuerySecurityRule
{
    /**
     * A map from variable names to their definition nodes.
     *
     * @var VariableDefinitionNode[]
     */
    public $varDefMap;

    public $fieldNodeAndDefs;

    public function getVisitor(ValidationContext $context)
    {
        $this->context = $context;

        return $this->invokeIfNeeded(
            $context,
            [
                NodeKind::SELECTION_SET => [
                    'enter' => function ($node) use ($context) {
                        return Visitor::skipNode();

                        $def = $context->getFieldDef();


                        if ($def->name === 'productElements') {

                            return Visitor::stop();

                            return [
                                'kind' => 'OrderStatus',
                                'ID' => 'ZZ',
                                'NAME' => 'NNN'
                            ];
                        }

                    },
                ],
            ]
        );
    }

    protected function isEnabled()
    {
        return true;
    }
}
