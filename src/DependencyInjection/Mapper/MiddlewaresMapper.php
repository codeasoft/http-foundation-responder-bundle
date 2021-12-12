<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Mapper;

use Symfony\Component\DependencyInjection\ContainerBuilder;

final class MiddlewaresMapper
{
    /**
     * @return array<string, string>
     */
    public static function map(ContainerBuilder $containerBuilder): array
    {
        $middlewareIds = array_keys(
            $containerBuilder->findTaggedServiceIds('tuzex.responder.middleware')
        );

        return array_combine($middlewareIds, $middlewareIds);
    }
}
