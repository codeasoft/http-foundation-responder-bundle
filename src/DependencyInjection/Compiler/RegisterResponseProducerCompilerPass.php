<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder\DependencyInjection\Compiler;

use Codea\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Codea\Bundle\Responder\DependencyInjection\Mapper\ResponseFactoriesMapper;
use Codea\Responder\Middleware\ResponseProducer;
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
