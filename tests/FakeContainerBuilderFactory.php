<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Responder\ContextResponder;
use Tuzex\Responder\Middleware\ResponseProducer;

final class FakeContainerBuilderFactory
{
    public static function withMiddlewares(string ...$middlewareIds): ContainerBuilder
    {
        return self::create($middlewareIds, 'tuzex.responder.middleware');
    }

    public static function withResponderAndMiddlewares(string ...$middlewareIds): ContainerBuilder
    {
        $responderId = ContextResponder::class;
        $responderServiceIds = [
            ResponseProducer::class,
        ];

        $container = self::withMiddlewares(...array_merge($middlewareIds, [ResponseProducer::class]));
        $container->setDefinition($responderId, DefinitionFactory::create($responderId, $responderServiceIds));

        return $container;
    }

    public static function withResponseFactories(string ...$responseFactoryIds): ContainerBuilder
    {
        return self::create($responseFactoryIds, 'tuzex.responder.response_factory');
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
