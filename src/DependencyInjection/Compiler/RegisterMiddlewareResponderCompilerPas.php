<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder\DependencyInjection\Compiler;

use Codea\Bundle\Responder\DependencyInjection\Helper\DefinitionFactory;
use Codea\Bundle\Responder\DependencyInjection\Mapper\MiddlewaresMapper;
use Codea\Responder\Middleware\ResponseProducer;
use Codea\Responder\MiddlewareResponder;
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
