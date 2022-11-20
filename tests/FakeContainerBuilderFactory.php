<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test;

use Termyn\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Termyn\SmartReply\Middleware\ResponseProducer;
use Termyn\SmartReply\MiddlewareResponder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class FakeContainerBuilderFactory
{
    public static function withMiddlewares(string ...$middlewareIds): ContainerBuilder
    {
        return self::create($middlewareIds, 'termyn.responder.middleware');
    }

    public static function withResponderAndMiddlewares(string ...$middlewareIds): ContainerBuilder
    {
        $responderId = MiddlewareResponder::class;
        $responderServiceIds = [
            ResponseProducer::class,
        ];

        $container = self::withMiddlewares(...array_merge($middlewareIds, [ResponseProducer::class]));
        $container->setDefinition($responderId, DefinitionFactory::create($responderId, $responderServiceIds));

        return $container;
    }

    public static function withResponseFactories(string ...$responseFactoryIds): ContainerBuilder
    {
        return self::create($responseFactoryIds, 'termyn.responder.response_factory');
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
