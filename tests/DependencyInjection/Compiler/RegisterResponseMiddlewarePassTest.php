<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compiler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compiler\RegisterResponseMiddlewarePass;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Middleware\CreateResponseMiddleware;
use Tuzex\Responder\Response\ContentResponseFactory;
use Tuzex\Responder\Response\JsonResponseFactory;

final class RegisterResponseMiddlewarePassTest extends TestCase
{
    /**
     * @dataProvider provideResponseFactoryIds
     */
    public function testItRegistersMiddlewareWithResultTransformers(array $responseFactoryIds): void
    {
        $containerBuilder = FakeContainerBuilderFactory::withResponseFactory($responseFactoryIds);

        $compilerPass = new RegisterResponseMiddlewarePass();
        $compilerPass->process($containerBuilder);

        $middlewareDefinition = $containerBuilder->getDefinition(CreateResponseMiddleware::class);
        $middlewareTransformers = $middlewareDefinition->getArguments();

        $this->assertSame(
            $responseFactoryIds,
            array_map(
                fn (Reference $reference): string => $reference->__toString(),
                $middlewareTransformers
            )
        );
    }

    public function provideResponseFactoryIds(): array
    {
        return [
            'anyone' => [
                'responseFactoryIds' => [],
            ],
            'one' => [
                'responseFactoryIds' => [
                    ContentResponseFactory::class,
                ],
            ],
            'several' => [
                'responseFactoryIds' => [
                    ContentResponseFactory::class,
                    JsonResponseFactory::class,
                ],
            ],
        ];
    }
}
