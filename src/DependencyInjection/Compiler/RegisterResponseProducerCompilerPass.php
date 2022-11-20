<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Termyn\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Termyn\Bundle\SmartReply\DependencyInjection\Mapper\ResponseFactoriesMapper;
use Termyn\SmartReply\Middleware\ResponseProducer;

final class RegisterResponseProducerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $responseProducerId = ResponseProducer::class;
        $responseFactoryIds = ResponseFactoriesMapper::map($container);

        $container->setDefinition($responseProducerId, DefinitionFactory::create($responseProducerId, $responseFactoryIds))
            ->addTag('termyn.responder.middleware');
    }
}
