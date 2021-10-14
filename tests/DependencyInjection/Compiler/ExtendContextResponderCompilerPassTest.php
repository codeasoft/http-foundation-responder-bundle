<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\ExtendContextResponderCompilerPass;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\ContextResponder;
use Tuzex\Responder\Middleware\FlashMessageEmitter;

final class ExtendContextResponderCompilerPassTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testItRegistersMiddlewaresToResponder(ContainerBuilder $containerBuilder, array $middlewareIds): void
    {
        $compilerPass = new ExtendContextResponderCompilerPass();
        $compilerPass->process($containerBuilder);

        $responderDefinition = $containerBuilder->getDefinition(ContextResponder::class);
        $responderMethodCalls = $responderDefinition->getMethodCalls()[0];

        $responderMethodName = $responderMethodCalls[0];
        $responderMethodArguments = array_map(
            fn (Reference $reference): string => $reference->__toString(),
            $responderMethodCalls[1]
        );

        $this->assertSame('extend', $responderMethodName);
        $this->assertSame($middlewareIds, $responderMethodArguments);
    }

    public function testItThrowsExceptionIfResponderIsNotRegistered(): void
    {
        $containerBuilder = new ContainerBuilder();
        $compilerPass = new ExtendContextResponderCompilerPass();

        $this->expectException(ServiceNotFoundException::class);
        $compilerPass->process($containerBuilder);
    }

    public function provideData(): iterable
    {
        $data = [
            'anyone' => [],
            'one' => [
                FlashMessageEmitter::class,
            ],
        ];

        foreach ($data as $count => $middlewareIds) {
            $containerBuilder = FakeContainerBuilderFactory::withResponderAndMiddlewares(...$middlewareIds);

            yield $count => [
                'containerBuilder' => $containerBuilder,
                'middlewareIds' => $middlewareIds,
            ];
        }
    }
}
