<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compilation;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Tuzex\Bundle\Responder\DependencyInjection\Mapping\ResultTransformersMapper;
use Tuzex\Responder\Middleware\TransformResultMiddleware;

final class RegisterTransformResultMiddlewareCompilerPass implements CompilerPassInterface
{
    private ResultTransformersMapper $resultsTransformersMapper;

    public function __construct()
    {
        $this->resultsTransformersMapper = new ResultTransformersMapper();
    }

    public function process(ContainerBuilder $containerBuilder): void
    {
        $middlewareClass = TransformResultMiddleware::class;
        $resultTransformersReference = $this->resultsTransformersMapper->map($containerBuilder);

        $containerBuilder->setDefinition($middlewareClass, new Definition($middlewareClass, $resultTransformersReference));
    }
}
