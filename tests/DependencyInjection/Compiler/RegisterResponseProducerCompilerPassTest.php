<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Middleware\ResponseProducer;
use Tuzex\Responder\Response\ResponseFactory\JsonResponseFactory;
use Tuzex\Responder\Response\ResponseFactory\TextResponseFactory;

final class RegisterResponseProducerCompilerPassTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testItRegistersProducerWithResponseFactories(ContainerBuilder $containerBuilder, array $responseFactoryIds): void
    {
        $compilerPass = new RegisterResponseProducerCompilerPass();
        $compilerPass->process($containerBuilder);

        $middlewareDefinition = $containerBuilder->getDefinition(ResponseProducer::class);
        $middlewareFactoryIds = array_map(
            fn (Reference $reference): string => $reference->__toString(),
            $middlewareDefinition->getArguments()
        );

        $this->assertSame($responseFactoryIds, $middlewareFactoryIds);
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
