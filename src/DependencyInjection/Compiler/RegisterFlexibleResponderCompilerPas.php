<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tuzex\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Tuzex\Bundle\Responder\DependencyInjection\Mapper\MiddlewaresMapper;
use Tuzex\Responder\FlexibleResponder;
use Tuzex\Responder\Middleware\ResponseProducer;

final class RegisterFlexibleResponderCompilerPas implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $responderId = FlexibleResponder::class;
        $responderArguments = $this->mapResponderArguments($container);

        $container->setDefinition($responderId, DefinitionFactory::create($responderId, $responderArguments));
    }

    private function mapResponderArguments(ContainerBuilder $container): array
    {
        $middlewareIds = MiddlewaresMapper::map($container);

        return $this->putResponderProducerBeforeOtherMiddlewares($middlewareIds);
    }

    private function putResponderProducerBeforeOtherMiddlewares(array $middlewareIds): array
    {
        uksort($middlewareIds, fn (string $middlewareId): int => (ResponseProducer::class === $middlewareId) ? -1 : 1);

        return $middlewareIds;
    }
}
