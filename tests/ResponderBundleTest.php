<?php

declare(strict_types=1);

namespace Codea\Bundle\Responder\Test;

use Codea\Bundle\Responder\DependencyInjection\Compiler\RegisterMiddlewareResponderCompilerPas;
use Codea\Bundle\Responder\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Codea\Bundle\Responder\DependencyInjection\Compiler\ResetFlashMessagePublisherCompilerPass;
use Codea\Bundle\Responder\ResponderBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ResponderBundleTest extends TestCase
{
    private ResponderBundle $responderBundle;

    protected function setUp(): void
    {
        $this->responderBundle = new ResponderBundle();

        parent::setUp();
    }

    /**
     * @dataProvider provideCompilerPasses
     */
    public function testItRegistersCompilerPasses(string $compilerPassId): void
    {
        $this->responderBundle->build($containerBuilder = new ContainerBuilder());

        $this->assertCount(1, array_filter(
            $containerBuilder->getCompilerPassConfig()->getPasses(),
            fn (CompilerPassInterface $compilerPass): bool => $compilerPass instanceof $compilerPassId
        ));
    }

    public function provideCompilerPasses(): array
    {
        $compilerPasses = [
            RegisterResponseProducerCompilerPass::class,
            RegisterMiddlewareResponderCompilerPas::class,
            ResetFlashMessagePublisherCompilerPass::class,
        ];

        return array_map(fn (string $compilerPass): array => [
            'compilerPassId' => $compilerPass,
        ], $compilerPasses);
    }
}
