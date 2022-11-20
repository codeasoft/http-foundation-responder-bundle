<?php

declare(strict_types=1);

namespace Termyn\Bundle\SmartReply\Test;

use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\RegisterMiddlewareResponderCompilerPas;
use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\RegisterResponseProducerCompilerPass;
use Termyn\Bundle\SmartReply\DependencyInjection\Compiler\ResetFlashMessagePublisherCompilerPass;
use Termyn\Bundle\SmartReply\SmartReplyBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SmartReplyBundleTest extends TestCase
{
    private SmartReplyBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new SmartReplyBundle();

        parent::setUp();
    }

    /**
     * @dataProvider provideCompilerPasses
     */
    public function testItRegistersCompilerPasses(string $compilerPassId): void
    {
        $this->bundle->build($containerBuilder = new ContainerBuilder());

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
