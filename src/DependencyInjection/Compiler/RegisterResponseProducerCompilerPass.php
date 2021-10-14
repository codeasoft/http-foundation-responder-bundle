<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Responder\Middleware\ResponseProducer;

final class RegisterResponseProducerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $producerId = ResponseProducer::class;
        $factoryIds = array_keys(
            $containerBuilder->findTaggedServiceIds('tuzex.responder.response_factory')
        );

        $containerBuilder->setDefinition($producerId, DefinitionFactory::create($producerId, $factoryIds));
    }
}
