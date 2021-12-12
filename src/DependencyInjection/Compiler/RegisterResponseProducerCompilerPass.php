<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Bundle\Responder\DependencyInjection\Mapper\ResponseFactoriesMapper;
use Tuzex\Responder\Middleware\ResponseProducer;

final class RegisterResponseProducerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $responseProducerId = ResponseProducer::class;
        $responseFactoryIds = ResponseFactoriesMapper::map($container);

        $container->setDefinition($responseProducerId, DefinitionFactory::create($responseProducerId, $responseFactoryIds))
            ->addTag('tuzex.responder.middleware');
    }
}
