<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\DependencyInjection\Helper;

use Symfony\Component\DependencyInjection\Definition;

final class DefinitionFactory
{
    public static function create(string $class, array $arguments = []): Definition
    {
        return new Definition($class, ReferencesResolver::resolve(...$arguments));
    }
}
