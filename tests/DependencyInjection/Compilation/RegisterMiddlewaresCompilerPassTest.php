<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compilation;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compilation\RegisterMiddlewaresCompilerPass;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Middleware\ProcessResultMiddleware;
use Tuzex\Responder\Middlewares;

final class RegisterMiddlewaresCompilerPassTest extends TestCase
{
    /**
     * @dataProvider provideMiddlewareIds
     */
    public function testItRegisterMiddlewareWithResultTransformers(array $middlewareIds): void
    {
        $containerBuilder = FakeContainerBuilderFactory::withMiddlewares($middlewareIds);

        $compilerPass = new RegisterMiddlewaresCompilerPass();
        $compilerPass->process($containerBuilder);

        $middlewaresDefinition = $containerBuilder->getDefinition(Middlewares::class);
        $middlewaresAddMethodCalls = $middlewaresDefinition->getMethodCalls()[0];

        $this->assertSame('add', $middlewaresAddMethodCalls[0]);
        $this->assertSame($middlewareIds, array_map(
            fn (Reference $reference): string => $reference->__toString(),
            $middlewaresAddMethodCalls[1]
        ));
    }

    public function provideMiddlewareIds(): array
    {
        return [
            'anyone' => [
                'middlewares' => [],
            ],
            'one' => [
                'middlewares' => [
                    ProcessResultMiddleware::class,
                ],
            ],
            'several' => [
                'middlewares' => [
                    ProcessResultMiddleware::class,
                    ProcessResultMiddleware::class,
                ],
            ],
        ];
    }
}
