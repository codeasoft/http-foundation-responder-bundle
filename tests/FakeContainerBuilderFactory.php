<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class FakeContainerBuilderFactory
{
    public static function withMiddlewares(array $serviceIds): ContainerBuilder
    {
        return self::create($serviceIds, 'tuzex.responder.middleware');
    }

    public static function withResponseFactory(array $serviceIds): ContainerBuilder
    {
        return self::create($serviceIds, 'tuzex.responder.response_factory');
    }

    private static function create(array $serviceIds, string $tag): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        foreach ($serviceIds as $serviceId) {
            $serviceDefinition = new Definition($serviceId);
            $serviceDefinition->addTag($tag);

            $containerBuilder->setDefinition($serviceId, $serviceDefinition);
        }

        return $containerBuilder;
    }
}
