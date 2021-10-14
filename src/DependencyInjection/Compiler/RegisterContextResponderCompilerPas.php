<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Responder\ContextResponder;
use Tuzex\Responder\Middleware\ResponseProducer;

final class RegisterContextResponderCompilerPas implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder): void
    {
        $responderId = ContextResponder::class;
        $serviceIds = [
            ResponseProducer::class,
        ];

        $containerBuilder->setDefinition($responderId, DefinitionFactory::create($responderId, $serviceIds));
    }
}
