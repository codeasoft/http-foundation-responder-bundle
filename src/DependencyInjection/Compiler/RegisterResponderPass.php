<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;
use Tuzex\Responder\Responder;

final class RegisterResponderPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $responderId = Responder::class;
        $middlewareIds = array_keys(
            $container->findTaggedServiceIds('tuzex.responder.middleware')
        );

        $container->setDefinition($responderId, $this->define($responderId, $middlewareIds));
    }

    private function define(string $responderId, array $middlewareIds): Definition
    {
        $responderDefinition = new Definition($responderId, [
            new Reference(CreateResponseMiddleware::class),
        ]);

        $responderMiddlewareReferences = array_map(
            fn (string $middlewareId): Reference => new Reference($middlewareId),
            $middlewareIds
        );

        return $responderDefinition->addMethodCall('extend', $responderMiddlewareReferences);
    }
}
