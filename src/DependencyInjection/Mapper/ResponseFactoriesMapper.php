<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Mapper;

use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ResponseFactoriesMapper
{
    /**
     * @return array<int, string>
     */
    public static function map(ContainerBuilder $containerBuilder): array
    {
        return array_keys(
            $containerBuilder->findTaggedServiceIds('tuzex.responder.response_factory')
        );
    }
}
