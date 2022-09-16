<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\DependencyInjection\Compiler;

use Codea\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Codea\Bundle\SmartReply\DependencyInjection\Mapper\ResponseFactoriesMapper;
use Codea\SmartReply\Middleware\ResponseProducer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterResponseProducerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $responseProducerId = ResponseProducer::class;
        $responseFactoryIds = ResponseFactoriesMapper::map($container);

        $container->setDefinition($responseProducerId, DefinitionFactory::create($responseProducerId, $responseFactoryIds))
            ->addTag('codea.responder.middleware');
    }
}
