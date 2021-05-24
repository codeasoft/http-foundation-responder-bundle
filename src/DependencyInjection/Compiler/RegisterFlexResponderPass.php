<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Responder\FlexResponder;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;

final class RegisterFlexResponderPass implements CompilerPassInterface
{
    private function setup(Definition $responder, array $middlewareIds): Definition
    {
        return $responder->addMethodCall(
            'extend',
            array_map(
                fn (string $middlewareId): Reference => new Reference($middlewareId),
                $middlewareIds
            )
        );
    }

    public function process(ContainerBuilder $container): void
    {
        $responderId = FlexResponder::class;
        $middlewareIds = array_keys(
            $container->findTaggedServiceIds('tuzex.responder.middleware')
        );

        $container->setDefinition($responderId, $this->define($responderId, $middlewareIds));
    }

    private function define(string $responderId, array $middlewareIds): Definition
    {
        $responder = new Definition($responderId, [
            new Reference(CreateResponseMiddleware::class),
        ]);

        return $this->setup($responder, $middlewareIds);
    }
}
