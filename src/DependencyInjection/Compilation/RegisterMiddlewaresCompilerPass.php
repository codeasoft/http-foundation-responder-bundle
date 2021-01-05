<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\DependencyInjection\Compilation;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Mapping\MiddlewaresMapper;
use Tuzex\Responder\Middleware\TransformResultMiddleware;
use Tuzex\Responder\Middlewares;

final class RegisterMiddlewaresCompilerPass implements CompilerPassInterface
{
    private MiddlewaresMapper $middlewaresMapper;

    public function __construct()
    {
        $this->middlewaresMapper = new MiddlewaresMapper();
    }

    public function process(ContainerBuilder $containerBuilder): void
    {
        $middlewaresDefinition = new Definition(Middlewares::class, [
            new Reference(TransformResultMiddleware::class),
        ]);

        $middlewaresDefinition->addMethodCall('add', $this->middlewaresMapper->map($containerBuilder));

        $containerBuilder->setDefinition(Middlewares::class, $middlewaresDefinition);
    }
}
