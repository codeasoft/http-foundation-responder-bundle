<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\DependencyInjection\Compiler;

use Termyn\Bundle\SmartReply\DependencyInjection\Helper\DefinitionFactory;
use Termyn\Bundle\SmartReply\DependencyInjection\Mapper\MiddlewaresMapper;
use Termyn\SmartReply\Middleware\ResponseProducer;
use Termyn\SmartReply\MiddlewareResponder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RegisterMiddlewareResponderCompilerPas implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $responderId = MiddlewareResponder::class;
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
        uksort($middlewareIds, fn (string $middlewareId): int => ($middlewareId === ResponseProducer::class) ? -1 : 1);

        return $middlewareIds;
    }
}
