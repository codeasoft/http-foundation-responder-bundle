<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void
{
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $parameters->set(Option::SETS, [
        SetList::COMMON,
        SetList::CLEAN_CODE,
        SetList::DEAD_CODE,
        SetList::PHP_71,
        SetList::PSR_12,
        SetList::SYMFONY,
    ]);

    $parameters->set(Option::SKIP, [
        PhpCsFixer\Fixer\Basic\BracesFixer::class => null,
        PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer::class => null,
        PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer::class => [
            __DIR__ . '/src/DependencyInjection/Configuration.php',
        ],
        Symplify\CodingStandard\Fixer\Spacing\StandaloneLinePromotedPropertyFixer::class => null,
        SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff::class . 'MissingTraversableTypeHintSpecification' => null,
        SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff::class . 'MissingTraversableTypeHintSpecification' => null,
        SlevomatCodingStandard\Sniffs\Classes\DisallowMultiPropertyDefinitionSniff::class . '.DisallowedMultiPropertyDefinition' => null,
    ]);
};
