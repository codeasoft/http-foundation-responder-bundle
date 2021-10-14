<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\ReferenceMapper;
use Tuzex\Responder\ContextResponder;
use Tuzex\Responder\Middleware\ResponseProducer;

final class ExtendContextResponderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $responderId = ContextResponder::class;

        $responderDefinition = $containerBuilder->getDefinition($responderId);
        $middlewareIds = $this->findMiddlewareIds($containerBuilder);

        $responderDefinition->addMethodCall('extend', ReferenceMapper::map(...$middlewareIds));
    }

    private function findMiddlewareIds(ContainerBuilder $containerBuilder): array
    {
        $ids = $containerBuilder->findTaggedServiceIds('tuzex.responder.middleware');

        unset($ids[ResponseProducer::class]);

        return array_keys($ids);
    }
}
