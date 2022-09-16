<?php

declare(strict_types=1);

namespace Codea\Bundle\SmartReply\Test\DependencyInjection\Compiler;

use Codea\Bundle\SmartReply\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Codea\Bundle\SmartReply\Test\FakeContainerBuilderFactory;
use Codea\SmartReply\Middleware\ResponseProducer;
use Codea\SmartReply\Response\ResponseFactory\JsonResponseFactory;
use Codea\SmartReply\Response\ResponseFactory\TextResponseFactory;
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
        $this->assertTrue($middlewareDefinition->hasTag('codea.responder.middleware'));
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
