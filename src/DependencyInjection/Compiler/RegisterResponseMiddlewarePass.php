<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;

final class RegisterResponseMiddlewarePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $middlewareId = CreateResponseMiddleware::class;
        $factoryIds = array_keys(
            $container->findTaggedServiceIds('tuzex.responder.response_factory')
        );

        $container->setDefinition($middlewareId, $this->define($middlewareId, $factoryIds));
    }

    private function define(string $middlewareId, array $factoryIds): Definition
    {
        return new Definition(
            $middlewareId,
            array_map(
                fn (string $factoryId): Reference => new Reference($factoryId),
                $factoryIds
            )
        );
    }
}
