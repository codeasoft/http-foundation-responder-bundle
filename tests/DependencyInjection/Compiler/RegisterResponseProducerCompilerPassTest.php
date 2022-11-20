<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test\DependencyInjection\Compiler;

use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Termyn\Bundle\SmartReply\Test\FakeContainerBuilderFactory;
use Termyn\SmartReply\Middleware\ResponseProducer;
use Termyn\SmartReply\Response\ResponseFactory\JsonResponseFactory;
use Termyn\SmartReply\Response\ResponseFactory\TextResponseFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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
        $this->assertTrue($middlewareDefinition->hasTag('termyn.responder.middleware'));
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
