<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterContextResponder;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\ContextResponder;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;
use Tuzex\Responder\Middleware\PublishFlashMessagesMiddleware;

final class RegisterResponderPassTest extends TestCase
{
    /**
     * @dataProvider provideData
     */
    public function testItRegistersMiddlewareWithResponseFactories(ContainerBuilder $containerBuilder, array $middlewareIds): void
    {
        $compilerPass = new RegisterContextResponder();
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

    public function provideData(): iterable
    {
        $data = [
            'anyone' => [],
            'one' => [
                PublishFlashMessagesMiddleware::class,
            ],
            'several' => [
                CreateResponseMiddleware::class,
                PublishFlashMessagesMiddleware::class,
            ],
        ];

        foreach ($data as $count => $middlewareIds) {
            yield $count => [
                'containerBuilder' => FakeContainerBuilderFactory::withMiddlewares(...$middlewareIds),
                'middlewareIds' => $middlewareIds,
            ];
        }
    }
}
