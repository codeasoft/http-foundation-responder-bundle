<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterFlexResponderPass;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\FlexResponder;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;
use Tuzex\Responder\Middleware\PublishFlashMessagesMiddleware;

final class RegisterFlexResponderPassTest extends TestCase
{
    /**
     * @dataProvider provideMiddlewareIds
     */
    public function testItRegistersMiddlewareWithResultTransformers(array $middlewareIds): void
    {
        $containerBuilder = FakeContainerBuilderFactory::withMiddlewares($middlewareIds);

        $compilerPass = new RegisterFlexResponderPass();
        $compilerPass->process($containerBuilder);

        $responderDefinition = $containerBuilder->getDefinition(FlexResponder::class);
        $responderAddMethodCalls = $responderDefinition->getMethodCalls()[0];

        $this->assertSame('extend', $responderAddMethodCalls[0]);
        $this->assertSame($middlewareIds, array_map(
            fn (Reference $reference): string => $reference->__toString(), $responderAddMethodCalls[1])
        );
    }

    public function provideMiddlewareIds(): array
    {
        return [
            'anyone' => [
                'middlewares' => [],
            ],
            'one' => [
                'middlewares' => [
                    PublishFlashMessagesMiddleware::class,
                ],
            ],
            'several' => [
                'middlewares' => [
                    CreateResponseMiddleware::class,
                    PublishFlashMessagesMiddleware::class,
                ],
            ],
        ];
    }
}
