<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterCreateResponseMiddlewarePass;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;
use Tuzex\Responder\Response\Factory\JsonResponseFactory;
use Tuzex\Responder\Response\Factory\TextResponseFactory;

final class RegisterCreateResponseMiddlewarePassTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testItRegistersMiddlewareWithResponseFactories(ContainerBuilder $containerBuilder, array $responseFactoryIds): void
    {
        $compilerPass = new RegisterCreateResponseMiddlewarePass();
        $compilerPass->process($containerBuilder);

        $middlewareDefinition = $containerBuilder->getDefinition(CreateResponseMiddleware::class);
        $middlewareArgumentIds = array_map(
            fn (Reference $reference): string => $reference->__toString(),
            $middlewareDefinition->getArguments()
        );

        $this->assertSame($responseFactoryIds, $middlewareArgumentIds);
    }

    public function provideData(): iterable
    {
        $data = [
            'anyone' => [],
            'one' => [
                TextResponseFactory::class,
            ],
            'several' => [
                TextResponseFactory::class,
                JsonResponseFactory::class,
            ],
        ];

        foreach ($data as $count => $factoryIds) {
            yield $count => [
                'containerBuilder' => FakeContainerBuilderFactory::withResponseFactories(...$factoryIds),
                'responseFactoryIds' => $factoryIds,
            ];
        }
    }
}
