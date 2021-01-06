<?php

declare(strict_types=1);

namespace Tuzex\Bundle\Responder\Test\DependencyInjection\Compilation;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Reference;
use Tuzex\Bundle\Responder\DependencyInjection\Compilation\RegisterTransformResultMiddlewareCompilerPass;
use Tuzex\Bundle\Responder\Test\FakeContainerBuilderFactory;
use Tuzex\Responder\Middleware\TransformResultMiddleware;
use Tuzex\Responder\Result\Payload\JsonDataTransformer;
use Tuzex\Responder\Result\Payload\TextTransformer;

final class RegisterTransformResultMiddlewareCompilerPassTest extends TestCase
{
    /**
     * @dataProvider provideResultTransformerIds
     */
    public function testItRegisterMiddlewareWithResultTransformers(array $trasformerIds): void
    {
        $containerBuilder = FakeContainerBuilderFactory::withResultTransformers($trasformerIds);

        $compilerPass = new RegisterTransformResultMiddlewareCompilerPass();
        $compilerPass->process($containerBuilder);

        $this->assertSame($trasformerIds, array_map(
            fn (Reference $reference): string => $reference->__toString(),
            $containerBuilder->getDefinition(TransformResultMiddleware::class)->getArguments()
        ));
    }

    public function provideResultTransformerIds(): array
    {
        return [
            'anyone' => [
                'transformers' => [],
            ],
            'one' => [
                'transformers' => [
                    TextTransformer::class,
                ],
            ],
            'several' => [
                'transformers' => [
                    TextTransformer::class,
                    JsonDataTransformer::class,
                ],
            ],
        ];
    }
}
