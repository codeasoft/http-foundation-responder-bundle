<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder\DependencyInjection\Mapper;

use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MiddlewaresMapper
{
    /**
     * @return array<int, string>
     */
    public static function map(ContainerBuilder $containerBuilder): array
    {
        return array_keys(
            $containerBuilder->findTaggedServiceIds('codea.responder.middleware')
        );
    }
}
