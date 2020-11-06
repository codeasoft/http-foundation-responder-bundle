<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compilation;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tuzex\Bundle\Responder\DependencyInjection\Mapping\ResultTransformersMapper;
use Tuzex\Responder\Middleware\ProcessResultMiddleware;

final class RegisterProcessResultMiddlewareCompilerPass implements CompilerPassInterface
{
    private ResultTransformersMapper $resultsTransformerMapper;

    public function __construct()
    {
        $this->resultsTransformerMapper = new ResultTransformersMapper();
    }

    public function process(ContainerBuilder $containerBuilder): void
    {
        $middlewareClass = ProcessResultMiddleware::class;
        $resultTransformersReference = $this->resultsTransformerMapper->map($containerBuilder);

        $containerBuilder->setDefinition($middlewareClass, new Definition($middlewareClass, $resultTransformersReference));
    }
}
