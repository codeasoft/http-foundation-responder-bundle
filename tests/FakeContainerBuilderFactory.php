<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class FakeContainerBuilderFactory
{
    public static function withResultTransformers(array $serviceIds): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();

        foreach ($serviceIds as $serviceId) {
            $serviceDefinition = new Definition($serviceId);
            $serviceDefinition->addTag('tuzex.responder.result_transformer');

            $containerBuilder->setDefinition($serviceId, $serviceDefinition);
        }

        return $containerBuilder;
    }

    public static function withMiddlewares(array $serviceIds): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->prependExtensionConfig('tuzex', [
            'responder' => [
                'middlewares' => $serviceIds,
            ],
        ]);

        return $containerBuilder;
    }
}
