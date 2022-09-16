<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\DependencyInjection\Mapper;

use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ResponseFactoriesMapper
{
    /**
     * @return array<int, string>
     */
    public static function map(ContainerBuilder $containerBuilder): array
    {
        return array_keys(
            $containerBuilder->findTaggedServiceIds('codea.responder.response_factory')
        );
    }
}
